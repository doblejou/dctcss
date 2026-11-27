#!/bin/sh

set -eu

SCRIPT_DIR=$(CDPATH='' cd -- "$(dirname -- "$0")" && pwd)
ROOT_DIR=$(CDPATH='' cd -- "$SCRIPT_DIR/.." && pwd)
VALIDATOR=${1:-"$SCRIPT_DIR/dctcss-validate.php"}
MANIFEST=${2:-"$ROOT_DIR/src/dctcss-api.json"}
FIXTURES="$SCRIPT_DIR/tests/contract-validator"
TEMP_DIR=$(mktemp -d -- "${TMPDIR:-/tmp}/dctcss-contract.XXXXXX")
trap 'rm -rf "$TEMP_DIR"' EXIT HUP INT TERM

expect_invalid() {
  label=$1
  html=$2
  printf '%s\n' "$html" > "$TEMP_DIR/invalid-case.html"
  expect_contract_error "$label" "$TEMP_DIR/invalid-case.html"
}

expect_contract_error() {
  status=0
  php "$VALIDATOR" --manifest "$MANIFEST" "$2" >/dev/null 2>"$TEMP_DIR/error" || status=$?
  if [ "$status" != 1 ]; then
    cat "$TEMP_DIR/error" >&2
    printf 'contract-validator: expected exit 1 for %s, got %s\n' "$1" "$status" >&2
    exit 1
  fi
}

php "$VALIDATOR" --manifest "$MANIFEST" "$FIXTURES/valid.html" >/dev/null
expect_contract_error 'invalid fixture' "$FIXTURES/invalid.html"
json=$(php "$VALIDATOR" --manifest "$MANIFEST" --format json "$FIXTURES/valid.html")
# PHP source is intentionally single-quoted against shell expansion.
# shellcheck disable=SC2016
php -r '$result = json_decode($argv[1], true, 512, JSON_THROW_ON_ERROR); if (($result["valid"] ?? null) !== true || ($result["filesChecked"] ?? null) !== 1) exit(1);' -- "$json"

expect_invalid 'an unknown dctcss class' '<div class="dct-invented"></div>'
expect_invalid 'an unknown dctcss attribute' '<div data-dct-invented="true"></div>'
expect_invalid 'a blank accessible name' '<progress class="dct-progress" max="10" aria-label="  "></progress>'
expect_invalid 'an empty accessible name reference' '<progress class="dct-progress" max="10" aria-labelledby=""></progress>'
expect_invalid 'a non-button popover trigger' '<span popovertarget="p">Open</span><div class="dct-popover" id="p" popover>Details</div>'
expect_invalid 'an invalid dctcss attribute value' '<div data-dct-theme="sepia"></div>'
expect_invalid 'hover elevation without a button base' '<button class="dct-is-hover-elevated" type="button">Save</button>'
expect_invalid 'hover elevation on a card' '<article class="dct-card dct-is-hover-elevated"><div class="dct-card-body">Card</div></article>'

printf '%s\n' '<button class="dct-button dct-is-hover-elevated" type="button">Save</button><a class="dct-button dct-is-secondary dct-is-hover-elevated" href="/docs">Docs</a>' > "$TEMP_DIR/hover-elevated.html"
php "$VALIDATOR" --manifest "$MANIFEST" "$TEMP_DIR/hover-elevated.html" >/dev/null

printf '%s\n' 'Contract validator checks passed.'
