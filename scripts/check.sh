#!/bin/sh

set -eu

SCRIPT_DIR=$(CDPATH='' cd -- "$(dirname -- "$0")" && pwd)
ROOT_DIR=$(CDPATH='' cd -- "$SCRIPT_DIR/.." && pwd)
TEMP_DIR=$(mktemp -d -- "${TMPDIR:-/tmp}/dctcss-check.XXXXXX")
trap 'rm -rf "$TEMP_DIR"' EXIT HUP INT TERM
BUILD_ONE="$TEMP_DIR/build-one"
BUILD_TWO="$TEMP_DIR/build-two"

fail() {
  printf 'check: %s\n' "$1" >&2
  exit 1
}

expect_failure() {
  label=$1
  shift
  if "$@" >"$TEMP_DIR/negative.out" 2>"$TEMP_DIR/negative.err"; then
    fail "$label was accepted"
  fi
}

php "$SCRIPT_DIR/validate.php"
sh "$SCRIPT_DIR/test-contract-validator.sh"
sh "$SCRIPT_DIR/test-config.sh"
for php_file in "$SCRIPT_DIR"/*.php "$SCRIPT_DIR/validation"/*.php "$ROOT_DIR/site"/*.php "$ROOT_DIR/site/pages"/*.php; do
  php -l "$php_file" >/dev/null || fail "PHP syntax failed: $php_file"
done
for node_file in "$SCRIPT_DIR"/*.cjs "$ROOT_DIR/site/site.js"; do
  node --check "$node_file" || fail "Node syntax failed: $node_file"
done
if command -v shellcheck >/dev/null 2>&1; then
  shellcheck "$SCRIPT_DIR"/*.sh || fail 'ShellCheck failed'
else
  printf '%s\n' 'check: ShellCheck not installed; skipped'
fi
sh "$SCRIPT_DIR/build.sh" "$BUILD_ONE" >/dev/null &
first_build=$!
sh "$SCRIPT_DIR/build.sh" "$BUILD_ONE" >/dev/null &
second_build=$!
build_status=0
wait "$first_build" || build_status=1
wait "$second_build" || build_status=1
[ "$build_status" = 0 ] || fail 'concurrent builds to the same destination failed'
sh "$SCRIPT_DIR/build.sh" "$BUILD_TWO" >/dev/null
sh "$SCRIPT_DIR/test-contract-validator.sh" "$BUILD_ONE/dist/dctcss-validate.php" "$BUILD_ONE/dist/dctcss-api.json"
php "$BUILD_ONE/dist/dctcss-validate.php" "$BUILD_ONE/public" >/dev/null || fail 'generated public HTML violates dctcss contracts'

diff -qr -- "$BUILD_ONE/dist" "$BUILD_TWO/dist" >/dev/null || fail 'dist build is not reproducible'
diff -qr -- "$BUILD_ONE/public" "$BUILD_TWO/public" >/dev/null || fail 'public build is not reproducible'
diff -qr -- "$BUILD_ONE/dist" "$ROOT_DIR/dist" >/dev/null || fail 'dist is stale; run scripts/build.sh'
diff -qr -- "$BUILD_ONE/public" "$ROOT_DIR/public" >/dev/null || fail 'public is stale; run scripts/build.sh'
sh "$SCRIPT_DIR/browser-check.sh" "$BUILD_ONE/public" || fail 'browser checks failed'
mkdir -- "$TEMP_DIR/visual"
sh "$SCRIPT_DIR/visual-check.sh" "$BUILD_ONE/public" "$TEMP_DIR/visual" || fail 'visual captures failed'
if command -v node >/dev/null 2>&1 && command -v npm >/dev/null 2>&1 \
  && NODE_PATH=$(npm root --global) node -e 'require("playwright")' >/dev/null 2>&1; then
  for capture in application-390-light.png application-1440-light.png application-390-dark.png application-1440-dark.png; do
    [ -s "$TEMP_DIR/visual/$capture" ] || fail "visual check did not create $capture"
  done
fi
[ ! -e "$BUILD_ONE/skills" ] || fail 'alternate output created a skills tree'
[ ! -e "$BUILD_TWO/skills" ] || fail 'alternate output created a skills tree'

SITE_URL=$(php "$SCRIPT_DIR/validate.php" --print-site-url)
cp -R -- "$BUILD_ONE/public" "$TEMP_DIR/public-nested-main"
sed 's#<div class="dct-main dct-stack">#<main class="dct-main dct-stack">#' "$BUILD_ONE/public/application.html" > "$TEMP_DIR/public-nested-main/application.html"
expect_failure 'a nested main landmark' php "$SCRIPT_DIR/validate.php" "$TEMP_DIR/public-nested-main" "$BUILD_ONE/dist" "$SITE_URL" "$ROOT_DIR/skills/dctcss/references"

cp -R -- "$BUILD_ONE/public" "$TEMP_DIR/public-active-form"
sed 's#\(id="application-settings-title">Filtros del informe</h[1-6]>\)<div>#\1<form action="/missing">#' "$BUILD_ONE/public/application.html" > "$TEMP_DIR/public-active-form/application.html"
expect_failure 'a navigable reference application form' php "$SCRIPT_DIR/validate.php" "$TEMP_DIR/public-active-form" "$BUILD_ONE/dist" "$SITE_URL" "$ROOT_DIR/skills/dctcss/references"

cp -R -- "$ROOT_DIR/skills/dctcss/references" "$TEMP_DIR/references"
cp -R -- "$ROOT_DIR/skills/dctcss/references" "$TEMP_DIR/references-extra"
: > "$TEMP_DIR/references-extra/unexpected"
expect_failure 'an extra skill reference' php "$SCRIPT_DIR/validate.php" "$BUILD_ONE/public" "$BUILD_ONE/dist" "$SITE_URL" "$TEMP_DIR/references-extra"

cp -R -- "$ROOT_DIR/skills/dctcss/references" "$TEMP_DIR/references-missing"
rm -- "$TEMP_DIR/references-missing/RECIPES.md"
expect_failure 'a missing skill reference' php "$SCRIPT_DIR/validate.php" "$BUILD_ONE/public" "$BUILD_ONE/dist" "$SITE_URL" "$TEMP_DIR/references-missing"

cp -R -- "$ROOT_DIR/skills/dctcss/references" "$TEMP_DIR/references-changed"
printf '\nchanged\n' >> "$TEMP_DIR/references-changed/RECIPES.md"
expect_failure 'a changed skill reference' php "$SCRIPT_DIR/validate.php" "$BUILD_ONE/public" "$BUILD_ONE/dist" "$SITE_URL" "$TEMP_DIR/references-changed"

cp -R -- "$BUILD_ONE/public" "$TEMP_DIR/public-with-references"
mkdir -- "$TEMP_DIR/public-with-references/references"
expect_failure 'publicly exposed skill references' php "$SCRIPT_DIR/validate.php" "$TEMP_DIR/public-with-references" "$BUILD_ONE/dist" "$SITE_URL" "$ROOT_DIR/skills/dctcss/references"

sed 's#references/dctcss-api.json#references/missing.json#g' "$ROOT_DIR/skills/dctcss/SKILL.md" > "$TEMP_DIR/skill-broken-link.md"
expect_failure 'a broken skill reference' php "$SCRIPT_DIR/validate.php" "$BUILD_ONE/public" "$BUILD_ONE/dist" "$SITE_URL" "$TEMP_DIR/references" "$TEMP_DIR/skill-broken-link.md"

cp -- "$ROOT_DIR/skills/dctcss/SKILL.md" "$TEMP_DIR/skill-dist-link.md"
printf '\n[Manifest](../../dist/dctcss-api.json)\n' >> "$TEMP_DIR/skill-dist-link.md"
expect_failure 'an active dist link in the skill' php "$SCRIPT_DIR/validate.php" "$BUILD_ONE/public" "$BUILD_ONE/dist" "$SITE_URL" "$TEMP_DIR/references" "$TEMP_DIR/skill-dist-link.md"

sed 's/^## Flujo operativo$/## Removed workflow/' "$ROOT_DIR/skills/dctcss/SKILL.md" > "$TEMP_DIR/skill-no-workflow.md"
expect_failure 'a skill without its workflow' php "$SCRIPT_DIR/validate.php" "$BUILD_ONE/public" "$BUILD_ONE/dist" "$SITE_URL" "$TEMP_DIR/references" "$TEMP_DIR/skill-no-workflow.md"

sed 's/^## Matriz de decisión$/## Removed matrix/' "$ROOT_DIR/skills/dctcss/SKILL.md" > "$TEMP_DIR/skill-no-matrix.md"
expect_failure 'a skill without its decision matrix' php "$SCRIPT_DIR/validate.php" "$BUILD_ONE/public" "$BUILD_ONE/dist" "$SITE_URL" "$TEMP_DIR/references" "$TEMP_DIR/skill-no-matrix.md"

sed 's/^## Checklist obligatorio de salida$/## Removed checklist/' "$ROOT_DIR/skills/dctcss/SKILL.md" > "$TEMP_DIR/skill-no-checklist.md"
expect_failure 'a skill without its checklist' php "$SCRIPT_DIR/validate.php" "$BUILD_ONE/public" "$BUILD_ONE/dist" "$SITE_URL" "$TEMP_DIR/references" "$TEMP_DIR/skill-no-checklist.md"

sed 's/\.dct-container/removed-container/g' "$ROOT_DIR/skills/dctcss/SKILL.md" > "$TEMP_DIR/skill-incomplete-api.md"
expect_failure 'incomplete skill API coverage' php "$SCRIPT_DIR/validate.php" "$BUILD_ONE/public" "$BUILD_ONE/dist" "$SITE_URL" "$TEMP_DIR/references" "$TEMP_DIR/skill-incomplete-api.md"

cp -- "$ROOT_DIR/skills/dctcss/SKILL.md" "$TEMP_DIR/skill-invalid-icon-button.md"
# Literal Markdown fence; shell expansion is intentionally disabled.
# shellcheck disable=SC2016
printf '\n```html\n<button class="dct-button dct-is-icon-only"></button>\n```\n' >> "$TEMP_DIR/skill-invalid-icon-button.md"
expect_failure 'a fenced skill example without its required modifier attribute' php "$SCRIPT_DIR/validate.php" "$BUILD_ONE/public" "$BUILD_ONE/dist" "$SITE_URL" "$TEMP_DIR/references" "$TEMP_DIR/skill-invalid-icon-button.md"

cp -- "$ROOT_DIR/skills/dctcss/SKILL.md" "$TEMP_DIR/skill-unknown-attribute.md"
# Literal Markdown fence; shell expansion is intentionally disabled.
# shellcheck disable=SC2016
printf '\n```html\n<div data-dct-invented="true"></div>\n```\n' >> "$TEMP_DIR/skill-unknown-attribute.md"
expect_failure 'a fenced skill example with an unknown dctcss attribute' php "$SCRIPT_DIR/validate.php" "$BUILD_ONE/public" "$BUILD_ONE/dist" "$SITE_URL" "$TEMP_DIR/references" "$TEMP_DIR/skill-unknown-attribute.md"

cp -- "$ROOT_DIR/skills/dctcss/SKILL.md" "$TEMP_DIR/skill-indirect-child.md"
# Literal Markdown fence; shell expansion is intentionally disabled.
# shellcheck disable=SC2016
printf '\n```html\n<section class="dct-message"><div><div class="dct-message-body"></div></div></section>\n```\n' >> "$TEMP_DIR/skill-indirect-child.md"
expect_failure 'an indirect requiresChild match' php "$SCRIPT_DIR/validate.php" "$BUILD_ONE/public" "$BUILD_ONE/dist" "$SITE_URL" "$TEMP_DIR/references" "$TEMP_DIR/skill-indirect-child.md"

cp -- "$ROOT_DIR/skills/dctcss/SKILL.md" "$TEMP_DIR/skill-split-descendant-attributes.md"
# Literal Markdown fence; shell expansion is intentionally disabled.
# shellcheck disable=SC2016
printf '\n```html\n<div class="dct-field dct-is-error"><input aria-invalid="true"><span aria-describedby="error"></span><span id="error">Error</span></div>\n```\n' >> "$TEMP_DIR/skill-split-descendant-attributes.md"
expect_failure 'modifier descendant requirements split across elements' php "$SCRIPT_DIR/validate.php" "$BUILD_ONE/public" "$BUILD_ONE/dist" "$SITE_URL" "$TEMP_DIR/references" "$TEMP_DIR/skill-split-descendant-attributes.md"

# PHP receives its own positional arguments.
# shellcheck disable=SC2016
php -r 'foreach (glob($argv[1] . "/*.php") as $file) require_once $file; $version = trim(read_file($argv[2] . "/VERSION")); $manifest = validate_manifest($argv[2] . "/src/dctcss-api.json", $version); $manifest["tokens"][] = "--dct-test-additive-token"; validate_release_api($manifest, $version);' "$SCRIPT_DIR/validation" "$ROOT_DIR"
# PHP receives its own positional arguments.
# shellcheck disable=SC2016
php -r 'foreach (glob($argv[1] . "/*.php") as $file) require_once $file; $version = trim(read_file($argv[2] . "/VERSION")); $manifest = validate_manifest($argv[2] . "/src/dctcss-api.json", $version); for ($index = 0; $index < 81; ++$index) $manifest["tokens"][] = "--dct-test-additive-token-$index"; validate_release_api($manifest, $version);' "$SCRIPT_DIR/validation" "$ROOT_DIR"

printf '%s\n' 'All checks passed.'
