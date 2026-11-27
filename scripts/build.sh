#!/bin/sh

set -eu

SCRIPT_DIR=$(CDPATH='' cd -- "$(dirname -- "$0")" && pwd)
ROOT_DIR=$(CDPATH='' cd -- "$SCRIPT_DIR/.." && pwd)
CSS_MINIFIER="/opt/dctfrw/util/bin/minify_css.sh"
CSS_MINIFIER_LIBRARY="/opt/dctfrw/util/lib/common.inc"
DIST_TEMP=
PUBLIC_TEMP=
REFERENCES_TEMP=
DIST_BACKUP=
PUBLIC_BACKUP=
REFERENCES_BACKUP=
DIST_DEST=
PUBLIC_DEST=
REFERENCES_DEST=
DIST_HAD_OLD=false
PUBLIC_HAD_OLD=false
REFERENCES_HAD_OLD=false
PUBLISHING=false
INSTALLED_ALL=false
PUBLISH_REFERENCES=false

rollback() {
  [ "$PUBLISHING" = true ] || return 0
  if [ "$INSTALLED_ALL" = true ]; then
    PUBLISHING=false
    return 0
  fi
  if [ -n "$DIST_BACKUP" ] && [ -e "$DIST_BACKUP" ]; then
    rm -rf -- "$DIST_DEST"
    if mv -- "$DIST_BACKUP" "$DIST_DEST"; then DIST_BACKUP=; fi
  elif [ "$DIST_HAD_OLD" = false ]; then
    rm -rf -- "$DIST_DEST"
  fi
  if [ -n "$PUBLIC_BACKUP" ] && [ -e "$PUBLIC_BACKUP" ]; then
    rm -rf -- "$PUBLIC_DEST"
    if mv -- "$PUBLIC_BACKUP" "$PUBLIC_DEST"; then PUBLIC_BACKUP=; fi
  elif [ "$PUBLIC_HAD_OLD" = false ]; then
    rm -rf -- "$PUBLIC_DEST"
  fi
  if [ "$PUBLISH_REFERENCES" = true ]; then
    if [ -n "$REFERENCES_BACKUP" ] && [ -e "$REFERENCES_BACKUP" ]; then
      rm -rf -- "$REFERENCES_DEST"
      if mv -- "$REFERENCES_BACKUP" "$REFERENCES_DEST"; then REFERENCES_BACKUP=; fi
    elif [ "$REFERENCES_HAD_OLD" = false ]; then
      rm -rf -- "$REFERENCES_DEST"
    fi
  fi
  PUBLISHING=false
}

cleanup() {
  rollback
  [ -z "$DIST_TEMP" ] || rm -rf -- "$DIST_TEMP"
  [ -z "$PUBLIC_TEMP" ] || rm -rf -- "$PUBLIC_TEMP"
  [ -z "$REFERENCES_TEMP" ] || rm -rf -- "$REFERENCES_TEMP"
  if [ "$INSTALLED_ALL" = true ]; then
    [ -z "$DIST_BACKUP" ] || rm -rf -- "$DIST_BACKUP"
    [ -z "$PUBLIC_BACKUP" ] || rm -rf -- "$PUBLIC_BACKUP"
    [ -z "$REFERENCES_BACKUP" ] || rm -rf -- "$REFERENCES_BACKUP"
  fi
  rm -rf -- "$STAGING"
}

DEPLOY=false
if [ "${1:-}" = "--deploy" ]; then
  DEPLOY=true
  shift
  php "$SCRIPT_DIR/validate.php" --assert-production
fi
if [ "$#" -gt 1 ]; then
  printf '%s\n' 'Usage: sh scripts/build.sh [--deploy] [OUTPUT_ROOT]' >&2
  exit 2
fi
if [ "$#" -eq 1 ]; then
  OUTPUT_ROOT=$1
else
  OUTPUT_ROOT=$ROOT_DIR
fi
if [ -z "$OUTPUT_ROOT" ]; then
  printf '%s\n' 'build: OUTPUT_ROOT must not be empty' >&2
  exit 2
fi
if ! command -v php >/dev/null 2>&1; then
  printf '%s\n' 'build: PHP CLI is required' >&2
  exit 1
fi
# PHP source is intentionally single-quoted so the shell cannot expand it.
# shellcheck disable=SC2016
ROOT_DIR=$(php -r '
$path = realpath($argv[1]);
if ($path === false) exit(1);
echo $path;
' -- "$ROOT_DIR")
OUTPUT_ROOT=$(php "$SCRIPT_DIR/validate.php" --resolve-output-root "$OUTPUT_ROOT")
mkdir -p -- "$OUTPUT_ROOT"
# En Linux, flock se conserva en la descripción de archivo compartida con fd 9
# hasta que termina este shell, incluida la limpieza o reversión.
exec 9<"$OUTPUT_ROOT"
php -r 'exit(flock(STDIN, LOCK_EX) ? 0 : 1);' <&9
php "$SCRIPT_DIR/validate.php" --canonical-output-root "$OUTPUT_ROOT" >/dev/null

STAGING=$(mktemp -d -- "${TMPDIR:-/tmp}/dctcss-build.XXXXXX")
trap cleanup EXIT
trap 'exit 1' HUP INT TERM
STAGE_DIST="$STAGING/dist"
STAGE_PUBLIC="$STAGING/public"
STAGE_REFERENCES="$STAGING/references"
mkdir -p -- "$STAGE_DIST" "$STAGE_PUBLIC/assets" "$STAGE_PUBLIC/downloads" "$STAGE_REFERENCES"

if ! command -v sha256sum >/dev/null 2>&1; then
  printf '%s\n' 'build: GNU sha256sum is required for distribution checksums' >&2
  exit 1
fi
if [ ! -x "$CSS_MINIFIER" ]; then
  printf 'build: CSS minifier is missing or not executable: %s\n' "$CSS_MINIFIER" >&2
  exit 1
fi
if [ ! -r "$CSS_MINIFIER_LIBRARY" ]; then
  printf 'build: CSS minifier library is missing or not readable: %s\n' "$CSS_MINIFIER_LIBRARY" >&2
  exit 1
fi

SITE_URL=$(php "$SCRIPT_DIR/validate.php" --print-site-url)
VERSION=$(tr -d '[:space:]' < "$ROOT_DIR/VERSION")
# shellcheck disable=SC2016
php -r '
$data = json_decode(file_get_contents($argv[1]), true, 512, JSON_THROW_ON_ERROR);
foreach (["css", "cssMinified", "api", "recipes", "changelog", "license", "checksums", "validator"] as $key) {
    $name = $data["artifacts"][$key] ?? "";
    if (!is_string($name) || preg_match("/^[A-Za-z0-9][A-Za-z0-9.-]*$/", $name) !== 1) exit(1);
    echo $name, "\n";
}
' -- "$ROOT_DIR/src/dctcss-api.json" > "$STAGING/artifact-names"
{
  IFS= read -r CSS_NAME
  IFS= read -r MINIFIED_NAME
  IFS= read -r API_NAME
  IFS= read -r RECIPE_NAME
  IFS= read -r CHANGELOG_NAME
  IFS= read -r LICENSE_NAME
  IFS= read -r CHECKSUM_NAME
  IFS= read -r VALIDATOR_NAME
} < "$STAGING/artifact-names"

latest_css_input="$ROOT_DIR/src/dct.css"
if [ "$ROOT_DIR/VERSION" -nt "$latest_css_input" ]; then
  latest_css_input="$ROOT_DIR/VERSION"
fi
{
  printf '/*! dctcss v%s | MIT License | Generated from src/; do not edit. */\n' "$VERSION"
  while IFS= read -r line || [ -n "$line" ]; do
    case "$line" in
      '/* dct-include: '*' */')
        relative_path=${line#'/* dct-include: '}
        relative_path=${relative_path%' */'}
        if [ "$ROOT_DIR/src/$relative_path" -nt "$latest_css_input" ]; then
          latest_css_input="$ROOT_DIR/src/$relative_path"
        fi
        printf '\n/* Source: %s */\n' "$relative_path"
        cat -- "$ROOT_DIR/src/$relative_path"
        ;;
      *) printf '%s\n' "$line" ;;
    esac
  done < "$ROOT_DIR/src/dct.css"
} > "$STAGE_DIST/$CSS_NAME"
touch -r "$latest_css_input" "$STAGE_DIST/$CSS_NAME"

"$CSS_MINIFIER" --app-root "$ROOT_DIR" --input "$STAGE_DIST/$CSS_NAME" --output "$STAGING/dct.min.body.css"
{
  printf '/*! dctcss v%s | MIT License | Minified build; do not edit. */\n' "$VERSION"
  cat -- "$STAGING/dct.min.body.css"
} > "$STAGE_DIST/$MINIFIED_NAME"
cp -- "$ROOT_DIR/src/dctcss-api.json" "$STAGE_DIST/$API_NAME"
cp -- "$ROOT_DIR/docs/RECIPES.md" "$STAGE_DIST/$RECIPE_NAME"
cp -- "$ROOT_DIR/CHANGELOG.md" "$STAGE_DIST/$CHANGELOG_NAME"
cp -- "$ROOT_DIR/LICENSE" "$STAGE_DIST/$LICENSE_NAME"
cp -- "$ROOT_DIR/scripts/dctcss-validate.php" "$STAGE_DIST/$VALIDATOR_NAME"
cp -- "$ROOT_DIR/src/dctcss-api.json" "$STAGE_REFERENCES/dctcss-api.json"
cp -- "$ROOT_DIR/docs/RECIPES.md" "$STAGE_REFERENCES/RECIPES.md"
cp -- "$ROOT_DIR/CHANGELOG.md" "$STAGE_REFERENCES/CHANGELOG.md"
(
  cd -- "$STAGE_DIST"
  sha256sum -- "$CSS_NAME" "$MINIFIED_NAME" "$API_NAME" "$RECIPE_NAME" "$CHANGELOG_NAME" "$LICENSE_NAME" "$VALIDATOR_NAME"
) > "$STAGE_DIST/$CHECKSUM_NAME"

cp --preserve=timestamps -- "$ROOT_DIR/site/site.css" "$STAGE_PUBLIC/assets/site.css"
cp -- "$ROOT_DIR/site/favicon.svg" "$STAGE_PUBLIC/assets/favicon.svg"
cp -- "$ROOT_DIR/site/site.js" "$STAGE_PUBLIC/assets/site.js"
install -m 0644 -- "$ROOT_DIR/site/.custom_apache_rules.conf" "$STAGE_PUBLIC/.custom_apache_rules.conf"
cp --preserve=timestamps -- "$STAGE_DIST/$CSS_NAME" "$STAGE_PUBLIC/downloads/$CSS_NAME"
cp -- "$STAGE_DIST/$MINIFIED_NAME" "$STAGE_PUBLIC/downloads/$MINIFIED_NAME"
cp -- "$STAGE_DIST/$API_NAME" "$STAGE_PUBLIC/downloads/$API_NAME"
php "$ROOT_DIR/site/build.php" "$STAGE_PUBLIC" "$SITE_URL" "$STAGE_DIST"
chmod 0755 -- "$STAGE_DIST" "$STAGE_PUBLIC" "$STAGE_PUBLIC/assets" "$STAGE_PUBLIC/downloads" "$STAGE_REFERENCES"
chmod 0644 -- "$STAGE_DIST"/* "$STAGE_PUBLIC"/*.html "$STAGE_PUBLIC"/*.txt "$STAGE_PUBLIC"/*.xml "$STAGE_PUBLIC/assets"/* "$STAGE_PUBLIC/downloads"/* "$STAGE_REFERENCES"/*

php "$SCRIPT_DIR/validate.php" "$STAGE_PUBLIC" "$STAGE_DIST" "$SITE_URL" "$STAGE_REFERENCES" >/dev/null
if [ "$DEPLOY" = false ]; then
  sh "$SCRIPT_DIR/audit-site.sh" "$STAGE_PUBLIC"
fi

DIST_DEST="$OUTPUT_ROOT/dist"
PUBLIC_DEST="$OUTPUT_ROOT/public"
if [ "$OUTPUT_ROOT" = "$ROOT_DIR" ]; then
  PUBLISH_REFERENCES=true
  REFERENCES_DEST="$ROOT_DIR/skills/dctcss/references"
fi
if [ ! -w "$OUTPUT_ROOT" ]; then
  printf '%s\n' 'build: OUTPUT_ROOT must be writable' >&2
  exit 1
fi

DIST_TEMP=$(mktemp -d -- "$OUTPUT_ROOT/.dist.new.XXXXXX")
PUBLIC_TEMP=$(mktemp -d -- "$OUTPUT_ROOT/.public.new.XXXXXX")
cp -R --preserve=mode -- "$STAGE_DIST/." "$DIST_TEMP/"
cp -R --preserve=mode,timestamps -- "$STAGE_PUBLIC/." "$PUBLIC_TEMP/"
chmod 0755 -- "$DIST_TEMP" "$PUBLIC_TEMP"
if [ "$PUBLISH_REFERENCES" = true ]; then
  REFERENCES_TEMP=$(mktemp -d -- "$ROOT_DIR/.references.new.XXXXXX")
  cp -R --preserve=mode -- "$STAGE_REFERENCES/." "$REFERENCES_TEMP/"
  chmod 0755 -- "$REFERENCES_TEMP"
fi
if [ "$PUBLISH_REFERENCES" = true ]; then
  php "$SCRIPT_DIR/validate.php" "$PUBLIC_TEMP" "$DIST_TEMP" "$SITE_URL" "$REFERENCES_TEMP" >/dev/null
else
  php "$SCRIPT_DIR/validate.php" "$PUBLIC_TEMP" "$DIST_TEMP" "$SITE_URL" "$STAGE_REFERENCES" >/dev/null
fi

DIST_BACKUP="$OUTPUT_ROOT/.dist.backup.$$"
PUBLIC_BACKUP="$OUTPUT_ROOT/.public.backup.$$"
if [ "$PUBLISH_REFERENCES" = true ]; then
  REFERENCES_BACKUP="$ROOT_DIR/skills/dctcss/.references.backup.$$"
fi
if [ -e "$DIST_BACKUP" ] || [ -e "$PUBLIC_BACKUP" ] || { [ -n "$REFERENCES_BACKUP" ] && [ -e "$REFERENCES_BACKUP" ]; }; then
  printf '%s\n' 'build: stale publication backup exists' >&2
  exit 1
fi
[ ! -e "$DIST_DEST" ] || DIST_HAD_OLD=true
[ ! -e "$PUBLIC_DEST" ] || PUBLIC_HAD_OLD=true
[ "$PUBLISH_REFERENCES" = false ] || [ ! -e "$REFERENCES_DEST" ] || REFERENCES_HAD_OLD=true
PUBLISHING=true
[ "$DIST_HAD_OLD" = false ] || mv -- "$DIST_DEST" "$DIST_BACKUP"
[ "$PUBLIC_HAD_OLD" = false ] || mv -- "$PUBLIC_DEST" "$PUBLIC_BACKUP"
[ "$PUBLISH_REFERENCES" = false ] || [ "$REFERENCES_HAD_OLD" = false ] || mv -- "$REFERENCES_DEST" "$REFERENCES_BACKUP"
mv -- "$DIST_TEMP" "$DIST_DEST"
DIST_TEMP=
mv -- "$PUBLIC_TEMP" "$PUBLIC_DEST"
PUBLIC_TEMP=
[ "$PUBLISH_REFERENCES" = false ] || mv -- "$REFERENCES_TEMP" "$REFERENCES_DEST"
REFERENCES_TEMP=
if [ "$PUBLISH_REFERENCES" = true ]; then
  cp -- "$ROOT_DIR/LICENSE" "$ROOT_DIR/skills/dctcss/LICENSE"
  chmod 0644 -- "$ROOT_DIR/skills/dctcss/LICENSE"
fi
INSTALLED_ALL=true
PUBLISHING=false
rm -rf -- "$DIST_BACKUP" "$PUBLIC_BACKUP"
[ -z "$REFERENCES_BACKUP" ] || rm -rf -- "$REFERENCES_BACKUP"
DIST_BACKUP=
PUBLIC_BACKUP=
REFERENCES_BACKUP=

CSS_SIZE=$(wc -c < "$DIST_DEST/$CSS_NAME" | tr -d ' ')
MINIFIED_SIZE=$(wc -c < "$DIST_DEST/$MINIFIED_NAME" | tr -d ' ')
# PHP receives its own positional argument.
# shellcheck disable=SC2016
GZIP_SIZE=$(php -r 'echo strlen(gzencode(file_get_contents($argv[1]), 9, ZLIB_ENCODING_GZIP));' -- "$DIST_DEST/$MINIFIED_NAME")
printf 'Built %s/dist/*\n' "$OUTPUT_ROOT"
printf 'Built %s/public/\n' "$OUTPUT_ROOT"
[ "$PUBLISH_REFERENCES" = false ] || printf 'Built %s/skills/dctcss/references/\n' "$OUTPUT_ROOT"
printf '\n%s\n' 'Resumen de tamaños CSS:'
printf '%s\n' '| Framework | Versión | Sin minificar | Minificado | Gzip | Comparación |'
printf '%s\n' '|---|---:|---:|---:|---:|---|'
printf '| dctcss | %s | %s bytes | %s bytes | %s bytes | referencia |\n' "$VERSION" "$CSS_SIZE" "$MINIFIED_SIZE" "$GZIP_SIZE"
while IFS='|' read -r framework version reference_size gzip_size reference; do
  case "$framework" in ''|'#'*) continue ;; esac
  case "$reference_size" in
    *[!0-9]*) printf '| %s | %s | n/d | %s | %s | orientativa o variable: %s |\n' "$framework" "$version" "$reference_size" "$gzip_size" "$reference" ;;
    *)
      if [ "$MINIFIED_SIZE" -le "$reference_size" ]; then
        difference=$(( (reference_size - MINIFIED_SIZE) * 100 / reference_size ))
        printf '| %s | %s | n/d | %s bytes | %s bytes | dctcss es %s%% menor |\n' "$framework" "$version" "$reference_size" "$gzip_size" "$difference"
      else
        difference=$(( (MINIFIED_SIZE - reference_size) * 100 / reference_size ))
        printf '| %s | %s | n/d | %s bytes | %s bytes | dctcss es %s%% mayor |\n' "$framework" "$version" "$reference_size" "$gzip_size" "$difference"
      fi
      ;;
  esac
done < "$ROOT_DIR/benchmarks/framework-sizes.txt"
