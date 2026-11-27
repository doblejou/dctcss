#!/bin/sh

set -eu

SCRIPT_DIR=$(CDPATH='' cd -- "$(dirname -- "$0")" && pwd)
ROOT_DIR=$(CDPATH='' cd -- "$SCRIPT_DIR/.." && pwd)
PUBLIC_DIR=${1:-"$ROOT_DIR/public"}

if ! command -v node >/dev/null 2>&1 || ! command -v npm >/dev/null 2>&1; then
  printf '%s\n' 'check: Node or npm not installed; Playwright browser checks skipped'
  exit 0
fi
NODE_PATH=$(npm root --global)
export NODE_PATH
if ! node -e 'require("playwright")' >/dev/null 2>&1; then
  printf '%s\n' 'check: Playwright not installed; browser checks skipped'
  exit 0
fi
PLAYWRIGHT_RUNNER="$SCRIPT_DIR/playwright-browser.cjs"
node "$SCRIPT_DIR/button-hover-check.cjs" "$PUBLIC_DIR"
node "$SCRIPT_DIR/recipes-check.cjs" "$PUBLIC_DIR"

TEMP_DIR=$(mktemp -d -- "${TMPDIR:-/tmp}/dctcss-browser.XXXXXX")
trap 'rm -rf "$TEMP_DIR"' EXIT HUP INT TERM
node "$SCRIPT_DIR/table-text-check.cjs" "$PUBLIC_DIR" "$TEMP_DIR/table-check"

for page in "$PUBLIC_DIR"/*.html; do
  name=$(basename -- "$page")
  instrumented="$TEMP_DIR/$name"
  # PHP and JavaScript are intentionally single-quoted against shell expansion.
  # shellcheck disable=SC2016
  php -r '
$html = file_get_contents($argv[1]);
$base = "file://" . rtrim((string) realpath($argv[2]), DIRECTORY_SEPARATOR) . "/";
$probe = <<<'"'"'HTML'"'"'
<script>
  window.__dctBrowserErrors = [];
  window.addEventListener("error", (event) => window.__dctBrowserErrors.push(event.message));
  window.addEventListener("unhandledrejection", (event) => window.__dctBrowserErrors.push(String(event.reason)));
  window.addEventListener("load", () => window.setTimeout(async () => {
    let interactions = true;
    const interactionDetails = {};
    const toggle = document.querySelector("[data-site-navbar-toggle]");
    if (toggle && !window.matchMedia("(min-width: 64rem)").matches) {
      const menu = document.getElementById(toggle.getAttribute("aria-controls"));
      toggle.click();
      interactionDetails.navbar = toggle.getAttribute("aria-expanded") === "true" && Boolean(menu) && !menu.hidden;
      interactions = interactions && interactionDetails.navbar;
    }
    const dialogButton = document.querySelector("button[aria-haspopup=dialog][aria-controls]");
    if (dialogButton) {
      const dialog = document.getElementById(dialogButton.getAttribute("aria-controls"));
      dialogButton.click();
      interactionDetails.dialogOpened = dialog?.open === true;
      interactions = interactions && interactionDetails.dialogOpened;
      dialog?.close();
      dialog?.dispatchEvent(new Event("close"));
      await new Promise((resolve) => window.setTimeout(resolve, 100));
      interactionDetails.dialogFocus = document.activeElement === dialogButton;
      interactionDetails.dialogActiveElement = document.activeElement?.outerHTML || null;
      interactions = interactions && interactionDetails.dialogFocus;
    }
    const copyButton = document.querySelector("[data-site-copy]");
    if (copyButton) {
      copyButton.click();
      await new Promise((resolve) => window.setTimeout(resolve, 50));
      interactionDetails.copy = !copyButton.hidden;
      interactions = interactions && interactionDetails.copy;
    }
    const noOverflow = document.documentElement.scrollWidth <= document.documentElement.clientWidth + 1;
    document.body.dataset.browserCheckDetails = JSON.stringify({ errors: window.__dctBrowserErrors, noOverflow, interactions, interactionDetails, scrollWidth: document.documentElement.scrollWidth, clientWidth: document.documentElement.clientWidth });
    document.body.dataset.browserCheck = window.__dctBrowserErrors.length === 0 && noOverflow && interactions ? "passed" : "failed";
  }, 100));
</script>
HTML;
$html = str_replace("<head>", "<head>\n    <base href=\"" . htmlspecialchars($base, ENT_QUOTES) . "\">", $html);
$html = str_replace("</head>", $probe . "\n  </head>", $html);
file_put_contents($argv[3], $html);
' "$page" "$PUBLIC_DIR" "$instrumented"

  for viewport in 390,844 844,900 1440,1000; do
    output=$(
      width=${viewport%,*}
      height=${viewport#*,}
      node "$PLAYWRIGHT_RUNNER" dump "file://$instrumented" "$width" "$height" 2>/dev/null
    )
    case "$output" in
      *'data-browser-check="passed"'*) ;;
      *)
        printf 'check: browser validation failed for %s at %s\n' "$name" "$viewport" >&2
        # PHP is intentionally single-quoted against shell expansion.
        # shellcheck disable=SC2016
        printf '%s' "$output" | php -r '$html = stream_get_contents(STDIN); if (preg_match("~data-browser-check-details=\"([^\"]+)~", $html, $match)) fwrite(STDERR, html_entity_decode($match[1], ENT_QUOTES) . "\n");'
        exit 1
        ;;
    esac
  done
done

node - "$PUBLIC_DIR" <<'JS'
const { chromium } = require("playwright");
const { resolve } = require("node:path");
const { pathToFileURL } = require("node:url");

(async () => {
  const browser = await chromium.launch();
  try {
    const page = await browser.newPage();
    await page.goto(pathToFileURL(resolve(process.argv[2], "fundamentals.html")).href);
    await page.evaluate(() => Object.defineProperty(navigator, "clipboard", {
      value: { writeText: async (text) => { window.__copiedText = text; } },
      configurable: true,
    }));
    const button = page.locator("[data-site-copy]").first();
    const original = await button.textContent();
    const codeId = await button.getAttribute("data-site-copy");
    await button.click();
    await page.waitForFunction(() => document.querySelector("[data-site-copy]").textContent === "Copiado");
    await button.click();
    await page.waitForFunction((label) => document.querySelector("[data-site-copy]").textContent === label, original);
    const copied = await page.evaluate((id) => window.__copiedText === document.getElementById(id).textContent, codeId);
    if (!copied) throw new Error("Copy did not preserve the snippet text");
    await page.evaluate(() => Object.defineProperty(navigator, "clipboard", {
      value: { writeText: async () => { throw new Error("Clipboard unavailable"); } },
    }));
    await button.click();
    const selected = await page.evaluate((id) => window.getSelection().toString() === document.getElementById(id).textContent, codeId);
    if (!selected || await button.textContent() !== original) throw new Error("Manual copy fallback failed");
    console.log("Repeated copy and manual fallback checks passed.");
  } finally {
    await browser.close();
  }
})().catch((error) => { console.error(error); process.exit(1); });
JS

PROBE="$TEMP_DIR/system-probe.html"
mkdir -- "$TEMP_DIR/downloads"
cp -- "$PUBLIC_DIR/downloads/dct.css" "$TEMP_DIR/downloads/dct.css"
cp -- "$PUBLIC_DIR/downloads/dct.min.css" "$TEMP_DIR/downloads/dct.min.css"
# The probe is self-contained and tests the public CSS rather than site presentation styles.
cat > "$PROBE" <<'HTML'
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="downloads/dct.css">
  <style>#box-model-probe::before, #box-model-probe::after { content: ""; }</style>
</head>
<body>
  <span id="box-model-probe"></span>
  <p class="dct-display" id="display">Display</p>
  <h1 class="dct-title" id="title">Title</h1>
  <p class="dct-subtitle" id="subtitle">Subtitle</p>
  <p class="dct-meta" id="meta">Metadata</p>
  <div class="dct-content"><h1 id="content-title">Content title</h1></div>
  <button class="dct-button" id="normal" type="button">Normal</button>
  <div data-dct-density="compact"><button class="dct-button" id="compact" type="button">Compact</button></div>
  <button class="dct-button dct-is-icon-only" id="icon" type="button" aria-label="Search"><svg class="dct-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M1 1h1"/></svg></button>
  <button class="dct-button dct-is-icon-only dct-is-small" id="icon-small" type="button" aria-label="Search"><svg class="dct-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M1 1h1"/></svg></button>
  <button class="dct-button dct-is-icon-only dct-is-medium" id="icon-medium" type="button" aria-label="Search"><svg class="dct-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M1 1h1"/></svg></button>
  <button class="dct-button dct-is-icon-only dct-is-large" id="icon-large" type="button" aria-label="Search"><svg class="dct-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M1 1h1"/></svg></button>
  <button class="dct-button" id="disabled" type="button" disabled>Disabled</button>
  <button class="dct-button dct-is-loading" id="loading" type="button" disabled aria-busy="true">Loading</button>
  <div dir="rtl"><button class="dct-button dct-is-loading" id="loading-rtl" type="button" disabled aria-busy="true">Loading RTL</button></div>
  <select class="dct-select" id="select"><option>Enabled select</option></select>
  <div class="dct-field-group">
    <div class="dct-field"><label class="dct-label" for="field-without-help">Mode</label><input class="dct-input" id="field-without-help" type="text"></div>
    <div class="dct-field"><label class="dct-label" for="field-with-help">Amount</label><input class="dct-input" id="field-with-help" type="text" aria-describedby="field-help"><p class="dct-help" id="field-help">Decimal values are accepted.</p></div>
  </div>
  <article class="dct-card dct-is-elevated" id="elevated"><div class="dct-card-body"><p>Elevated</p></div></article>
  <div class="dct-popover" id="popover" popover>Popover</div>
  <dialog class="dct-dialog" id="dialog" aria-label="Dialog" open><div class="dct-dialog-body">Dialog</div></dialog>
  <article class="dct-card" data-dct-theme="light" id="light"><div class="dct-card-body">Light</div></article>
  <article class="dct-card" data-dct-theme="dark" id="dark"><div class="dct-card-body">Dark</div></article>
  <div class="dct-message dct-is-info"><span class="dct-message-icon" id="message-info-icon" aria-hidden="true"><svg class="dct-icon" viewBox="0 0 24 24"><path d="M1 1h1"/></svg></span><div class="dct-message-body"><p class="dct-message-content" id="message-info-content">Info message</p></div></div>
  <div class="dct-message dct-is-success"><span class="dct-message-icon" id="message-success-icon" aria-hidden="true"><svg class="dct-icon" viewBox="0 0 24 24"><path d="M1 1h1"/></svg></span><div class="dct-message-body"><p class="dct-message-content" id="message-success-content">Success message</p></div></div>
  <div class="dct-message dct-is-warning" style="inline-size: min(16rem, 100%)"><span class="dct-message-icon" id="message-warning-icon" aria-hidden="true"><svg class="dct-icon" viewBox="0 0 24 24"><path d="M1 1h1"/></svg></span><div class="dct-message-body"><p class="dct-message-content" id="message-warning-content">Warning message with enough text to wrap across multiple lines.</p></div></div>
  <div class="dct-message dct-is-danger"><span class="dct-message-icon" id="message-danger-icon" aria-hidden="true"><svg class="dct-icon" viewBox="0 0 24 24"><path d="M1 1h1"/></svg></span><div class="dct-message-body"><p class="dct-message-content" id="message-danger-content">Danger message</p></div></div>
  <nav class="dct-navbar dct-is-inverse" id="inverse-navbar" aria-label="Primary navigation">
    <a class="dct-navbar-brand" id="inverse-brand" href="#">ExtremelyLongUnbrokenProductNameForOverflowValidation</a>
    <div class="dct-navbar-menu"><a class="dct-navbar-item" id="inverse-current" href="#" aria-current="page">Current</a><a class="dct-navbar-item" id="inverse-item" href="#">Projects</a><div class="dct-navbar-actions"><button class="dct-button" id="inverse-button" type="button">Account</button></div></div>
  </nav>
  <nav class="dct-navbar dct-is-inverse" id="inverse-loading-navbar" aria-label="Loading actions">
    <div class="dct-navbar-menu"><div class="dct-navbar-actions">
      <button class="dct-button dct-is-loading" id="inverse-loading" type="button" disabled aria-busy="true">Syncing</button>
      <button class="dct-button dct-is-secondary dct-is-loading" id="inverse-secondary-loading" type="button" disabled aria-busy="true">Updating</button>
      <button class="dct-button dct-is-primary dct-is-loading" id="inverse-primary-loading" type="button" disabled aria-busy="true">Saving</button>
      <button class="dct-button dct-is-danger dct-is-loading" id="inverse-danger-loading" type="button" disabled aria-busy="true">Deleting</button>
    </div></div>
  </nav>
  <div class="dct-table-wrap dct-is-elevated" id="elevated-table" tabindex="0" role="region" aria-labelledby="probe-caption"><table class="dct-table"><caption class="dct-table-caption" id="probe-caption">Long table</caption><tbody><tr><th scope="row">Item</th><td>Value</td></tr></tbody></table></div>
  <script>
    if (new URLSearchParams(location.search).has("dark")) document.documentElement.dataset.dctTheme = "dark";
    window.addEventListener("load", () => setTimeout(() => {
      const failures = [];
      const query = new URLSearchParams(location.search);
      if (query.has("scale")) document.documentElement.style.fontSize = `${query.get("scale")}%`;
      const check = (condition, label) => { if (!condition) failures.push(label); };
      const style = (id, pseudo) => getComputedStyle(document.getElementById(id), pseudo);
      const number = (value) => Number.parseFloat(value);
      const rgb = (value) => (value.match(/[\d.]+/g) || []).slice(0, 3).map(Number);
      const luminance = (value) => {
        const channels = rgb(value).map((channel) => { const normalized = channel / 255; return normalized <= 0.04045 ? normalized / 12.92 : ((normalized + 0.055) / 1.055) ** 2.4; });
        return 0.2126 * channels[0] + 0.7152 * channels[1] + 0.0722 * channels[2];
      };
      const contrast = (left, right) => { const values = [luminance(left), luminance(right)].sort((a, b) => b - a); return (values[0] + 0.05) / (values[1] + 0.05); };
      const ruleText = [];
      const collect = (rules) => {
        for (const rule of rules) {
          ruleText.push(rule.cssText);
          if (rule.cssRules) collect(rule.cssRules);
        }
      };
      for (const sheet of document.styleSheets) {
        try { collect(sheet.cssRules); } catch (error) { failures.push("read stylesheet rules"); }
      }
      const css = ruleText.join("\n");
      const selectors = [];
      const collectSelectors = (rules) => {
        for (const rule of rules) {
          if (rule.selectorText) selectors.push(rule.selectorText);
          if (rule.cssRules) collectSelectors(rule.cssRules);
        }
      };
      for (const sheet of document.styleSheets) {
        try { collectSelectors(sheet.cssRules); } catch (error) { failures.push("read stylesheet selectors"); }
      }
      const selectorParts = selectors.flatMap((selector) => selector.split(",").map((part) => part.trim()));
      check(css.includes(":hover"), "parsed hover rules");
      check(/\.dct-navbar-item[^,{]*:hover|:hover[^,{]*\.dct-navbar-item/.test(css), "parsed navbar hover rule");
      check(/\.dct-pagination-link[^,{]*:hover|:hover[^,{]*\.dct-pagination-link/.test(css), "parsed pagination hover rule");
      check(css.includes(":focus-visible"), "parsed focus-visible rules");
      const selectHover = selectorParts.find((selector) => selector.includes(".dct-select") && selector.includes(":hover"));
      check(Boolean(selectHover) && !selectHover.includes(":read-only"), "enabled select hover rule");
      check(css.includes("prefers-reduced-motion"), "parsed reduced-motion rules");
      check(css.includes("prefers-color-scheme: dark"), "parsed automatic dark-theme rule");
      check(number(style("display").fontSize) > number(style("title").fontSize), "display hierarchy");
      check(number(style("title").fontSize) > number(style("subtitle").fontSize), "title hierarchy");
      check(number(style("meta").fontSize) < number(style("subtitle").fontSize), "metadata hierarchy");
      check(number(style("content-title").fontSize) === number(style("display").fontSize), "content heading scale");
      check(number(style("compact").height) < number(style("normal").height), "compact controls");
      check(Math.abs(document.getElementById("field-without-help").getBoundingClientRect().height - document.getElementById("field-with-help").getBoundingClientRect().height) <= 0.5, "field controls keep equal height with optional help");
      check(Math.abs(number(style("icon").width) - number(style("icon").height)) <= 1, "square icon button");
      for (const id of ["icon-small", "icon-medium", "icon-large"]) {
        check(Math.abs(number(style(id).width) - number(style(id).height)) <= 1, `square ${id}`);
      }
      check(number(style("icon-small").width) < number(style("icon-medium").width) && number(style("icon-medium").width) < number(style("icon-large").width), "icon button size scale");
      check(style("disabled").cursor === "not-allowed" && number(style("disabled").opacity) < 1, "disabled state");
      check(style("box-model-probe", "::before").boxSizing === "border-box" && style("box-model-probe", "::after").boxSizing === "border-box", "pseudo-element box sizing");
      check(style("loading").color === "rgba(0, 0, 0, 0)" && style("loading", "::after").content !== "none", "loading state");
      for (const id of ["inverse-loading", "inverse-secondary-loading", "inverse-primary-loading", "inverse-danger-loading"]) {
        check(style(id).color === "rgba(0, 0, 0, 0)", `${id} hides visible text`);
        const background = id.includes("primary") || id.includes("danger") ? style(id).backgroundColor : style("inverse-loading-navbar").backgroundColor;
        check(contrast(style(id, "::after").borderTopColor, background) >= 3, `${id} spinner contrast`);
      }
      for (const id of ["loading", "loading-rtl"]) {
        const spinner = style(id, "::after");
        check(number(spinner.insetInlineStart) === 0 && number(spinner.insetInlineEnd) === 0 && number(spinner.insetBlockStart) === 0 && number(spinner.insetBlockEnd) === 0, `centered ${id}`);
      }
      check(style("elevated").boxShadow !== "none", "card elevation");
      check(style("popover").boxShadow !== "none", "popover elevation");
      check(style("dialog").boxShadow !== "none", "dialog elevation");
      check(new Set([style("popover").boxShadow, style("elevated").boxShadow, style("dialog").boxShadow]).size === 3, "elevation scale");
      check(style("light").backgroundColor !== style("dark").backgroundColor, "explicit theme surfaces");
      check(style("light").color !== style("dark").color, "explicit theme text");
      for (const variant of ["info", "success", "warning", "danger"]) {
        const iconRect = document.getElementById(`message-${variant}-icon`).getBoundingClientRect();
        const contentRect = document.getElementById(`message-${variant}-content`).getBoundingClientRect();
        check(Math.abs(iconRect.top - contentRect.top) <= 0.5, `message ${variant} icon aligned with first line`);
      }
      const warningContent = document.getElementById("message-warning-content");
      check(warningContent.getBoundingClientRect().height > number(style("message-warning-content").lineHeight) * 1.5, "multiline warning message");
      const inverseBackground = style("inverse-navbar").backgroundColor;
      check(contrast(style("inverse-brand").color, inverseBackground) >= 4.5, "inverse brand contrast");
      check(contrast(style("inverse-item").color, inverseBackground) >= 4.5, "inverse item contrast");
      check(contrast(style("inverse-current").color, style("inverse-current").backgroundColor) >= 4.5, "inverse current contrast");
      check(style("elevated-table").boxShadow !== "none" && style("elevated-table").overflowX === "auto", "elevated table surface and overflow");
      check(style("inverse-brand").overflowWrap === "anywhere", "inverse long text wrapping");
      document.getElementById("normal").focus();
      check(number(style("normal").outlineWidth) > 0 && style("normal").outlineStyle !== "none", "visible focus");
      for (const id of ["inverse-brand", "inverse-current", "inverse-item", "inverse-button", "elevated-table"]) {
        document.getElementById(id).focus();
        check(number(style(id).outlineWidth) > 0 && style(id).outlineStyle !== "none", `visible focus ${id}`);
      }
      if (query.has("reduced")) {
        check(matchMedia("(prefers-reduced-motion: reduce)").matches, "reduced-motion preference");
        const durations = style("normal").transitionDuration.split(",").map((value) => value.endsWith("ms") ? number(value) : number(value) * 1000);
        check(durations.every((duration) => duration <= 0.1), "reduced transitions");
        check(style("loading", "::after").animationName === "none", "reduced loading animation");
      }
      const noOverflow = document.documentElement.scrollWidth <= document.documentElement.clientWidth + 1;
      check(noOverflow, "probe horizontal overflow");
      document.body.dataset.browserCheckDetails = JSON.stringify({ failures, scrollWidth: document.documentElement.scrollWidth, clientWidth: document.documentElement.clientWidth });
      document.body.dataset.browserCheck = failures.length === 0 ? "passed" : "failed";
    }, 100));
  </script>
</body>
</html>
HTML

run_probe() {
  label=$1
  query=$3
  output=$(node "$PLAYWRIGHT_RUNNER" dump "file://$PROBE$query" 844 900 2>/dev/null)
  case "$output" in
    *'data-browser-check="passed"'*) ;;
    *)
      printf 'check: browser system probe failed (%s)\n' "$label" >&2
      # shellcheck disable=SC2016
      printf '%s' "$output" | php -r '$html = stream_get_contents(STDIN); if (preg_match("~data-browser-check-details=\"([^\"]+)~", $html, $match)) fwrite(STDERR, html_entity_decode($match[1], ENT_QUOTES) . "\n");'
      exit 1
      ;;
  esac
}

run_probe default '' ''
run_probe dark '' '?dark=1'
run_probe reduced-motion '--force-prefers-reduced-motion' '?reduced=1'
run_probe zoom-200 '' '?scale=200'
run_probe zoom-400 '' '?scale=400'

MINIFIED_PROBE="$TEMP_DIR/system-probe-minified.html"
sed 's#downloads/dct\.css#downloads/dct.min.css#' "$PROBE" > "$MINIFIED_PROBE"
PROBE=$MINIFIED_PROBE
run_probe minified '' ''
run_probe minified-dark '' '?dark=1'

printf '%s\n' 'Browser checks passed.'
