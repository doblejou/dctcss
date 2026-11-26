#!/bin/sh

set -eu

SCRIPT_DIR=$(CDPATH='' cd -- "$(dirname -- "$0")" && pwd)
ROOT_DIR=$(CDPATH='' cd -- "$SCRIPT_DIR/.." && pwd)
PUBLIC_DIR=${1:-"$ROOT_DIR/public"}
OUTPUT_DIR=${2:-}
MODE=${3:-}
BASELINE_DIR="$SCRIPT_DIR/visual-baselines"
THRESHOLD=0.002

if [ -n "$MODE" ] && [ "$MODE" != "--update-baselines" ]; then
  printf '%s\n' 'Usage: sh scripts/visual-check.sh [PUBLIC_DIR [OUTPUT_DIR [--update-baselines]]]' >&2
  exit 2
fi

if ! command -v node >/dev/null 2>&1 || ! command -v npm >/dev/null 2>&1; then
  printf '%s\n' 'check: Node or npm not installed; Playwright visual captures skipped'
  exit 0
fi
NODE_PATH=$(npm root --global)
export NODE_PATH
if ! node -e 'require("playwright")' >/dev/null 2>&1; then
  printf '%s\n' 'check: Playwright not installed; visual captures skipped'
  exit 0
fi
PLAYWRIGHT_RUNNER="$SCRIPT_DIR/playwright-browser.cjs"

if [ "$MODE" != "--update-baselines" ] && ! command -v compare >/dev/null 2>&1; then
  printf '%s\n' 'visual-check: ImageMagick compare is required when Playwright is available' >&2
  exit 1
fi

APPLICATION="$PUBLIC_DIR/application.html"
if [ ! -f "$APPLICATION" ]; then
  printf 'visual-check: reference application is missing: %s\n' "$APPLICATION" >&2
  exit 1
fi

if [ -z "$OUTPUT_DIR" ]; then
  OUTPUT_DIR=$(mktemp -d -- "${TMPDIR:-/tmp}/dctcss-visual.XXXXXX")
else
  mkdir -p -- "$OUTPUT_DIR"
  OUTPUT_DIR=$(CDPATH='' cd -- "$OUTPUT_DIR" && pwd)
fi
WORK_DIR=$(mktemp -d -- "${TMPDIR:-/tmp}/dctcss-visual-work.XXXXXX")
trap 'rm -rf "$WORK_DIR"' EXIT HUP INT TERM
if [ "$MODE" = "--update-baselines" ]; then
  mkdir -p -- "$BASELINE_DIR"
fi

for theme in light dark; do
  page="$WORK_DIR/application-$theme.html"
  # PHP and JavaScript are intentionally single-quoted against shell expansion.
  # shellcheck disable=SC2016
  php -r '
$html = file_get_contents($argv[1]);
$base = "file://" . rtrim((string) realpath($argv[2]), DIRECTORY_SEPARATOR) . "/";
$theme = $argv[3];
$html = str_replace("<html ", "<html data-dct-theme=\"" . htmlspecialchars($theme, ENT_QUOTES) . "\" ", $html);
$html = str_replace("<head>", "<head>\n    <base href=\"" . htmlspecialchars($base, ENT_QUOTES) . "\">", $html);
$document = new DOMDocument();
libxml_use_internal_errors(true);
$document->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
libxml_clear_errors();
$target = $document->getElementById("application-preview");
$body = $document->getElementsByTagName("body")->item(0);
if (!$target instanceof DOMElement || !$body instanceof DOMElement) exit(1);
$capture = $target->cloneNode(true);
while ($body->firstChild !== null) $body->removeChild($body->firstChild);
$body->appendChild($capture);
$html = $document->saveHTML();
file_put_contents($argv[4], $html);
' -- "$APPLICATION" "$PUBLIC_DIR" "$theme" "$page"

  for width in 390 1440; do
    target="$OUTPUT_DIR/application-$width-$theme.png"
    node "$PLAYWRIGHT_RUNNER" screenshot "file://$page" "$width" 1200 "$target" >/dev/null 2>&1
    if [ ! -s "$target" ]; then
      printf 'visual-check: Chromium did not create %s\n' "$target" >&2
      exit 1
    fi
    baseline="$BASELINE_DIR/application-$width-$theme.png"
    if [ "$MODE" = "--update-baselines" ]; then
      cp -- "$target" "$baseline"
      continue
    fi
    if [ ! -s "$baseline" ]; then
      printf 'visual-check: baseline is missing: %s\n' "$baseline" >&2
      exit 1
    fi
    status=0
    metric=$(compare -metric RMSE "$baseline" "$target" null: 2>&1) || status=$?
    if [ "$status" -gt 1 ]; then
      printf 'visual-check: ImageMagick failed for %s: %s\n' "$target" "$metric" >&2
      exit 1
    fi
    # PHP source is intentionally single-quoted against shell expansion.
    # shellcheck disable=SC2016
    if ! php -r '
$metric = $argv[1];
$threshold = (float) $argv[2];
if (preg_match("/\\((?<normalized>[0-9.eE+-]+)\\)/", $metric, $match) !== 1) exit(2);
exit((float) $match["normalized"] <= $threshold ? 0 : 1);
' -- "$metric" "$THRESHOLD"; then
      printf 'visual-check: regression in %s (RMSE %s, threshold %s)\n' "$target" "$metric" "$THRESHOLD" >&2
      exit 1
    fi
  done
done

printf 'Visual captures written to %s\n' "$OUTPUT_DIR"
