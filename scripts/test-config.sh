#!/bin/sh

set -eu

SCRIPT_DIR=$(CDPATH='' cd -- "$(dirname -- "$0")" && pwd)
TEMP_DIR=$(mktemp -d -- "${TMPDIR:-/tmp}/dctcss-config.XXXXXX")
trap 'rm -rf "$TEMP_DIR"' EXIT HUP INT TERM

fail() {
  printf 'config-test: %s\n' "$1" >&2
  exit 1
}

expect_valid() {
  url=$1
  env DCTCSS_ENV=production SITE_URL="$url" php "$SCRIPT_DIR/validate.php" --assert-production >"$TEMP_DIR/out" 2>"$TEMP_DIR/err" \
    || fail "valid production URL was rejected: $url"
}

expect_invalid() {
  url=$1
  if env DCTCSS_ENV=production SITE_URL="$url" php "$SCRIPT_DIR/validate.php" --assert-production >"$TEMP_DIR/out" 2>"$TEMP_DIR/err"; then
    fail "invalid production URL was accepted: $url"
  fi
}

expect_valid 'https://example.com'
expect_valid 'https://8.8.8.8'
expect_valid 'https://[2606:4700:4700::1111]'

printf '%s\n' 'DCTCSS_ENV=development' 'SITE_URL=https://default.example' > "$TEMP_DIR/.env.example"
printf '%s\n' 'DCTCSS_ENV=production' > "$TEMP_DIR/.env"
(
  unset DCTCSS_ENV SITE_URL
  # shellcheck disable=SC2016
  php -r '
    require $argv[1] . "/validation/common.php";
    require $argv[1] . "/validation/config.php";
    $config = load_site_config($argv[2]);
    if ($config !== ["environment" => "production", "siteUrl" => "https://default.example"]) exit(1);
    putenv("DCTCSS_ENV=development");
    putenv("SITE_URL=http://localhost");
    $config = load_site_config($argv[2]);
    if ($config !== ["environment" => "development", "siteUrl" => "http://localhost"]) exit(1);
  ' -- "$SCRIPT_DIR" "$TEMP_DIR"
) || fail 'configuration precedence failed'

for url in \
  'http://example.com' \
  'https://localhost.' \
  'https://foo.local.' \
  'https://foo.localhost.' \
  'https://127.0.0.1.' \
  'https://100.64.0.1' \
  'https://192.0.0.1' \
  'https://198.18.0.1' \
  'https://203.0.113.1' \
  'https://[::1]' \
  'https://[2001:db8::1]' \
  'https://[fc00::1]' \
  'https://[fe80::1]'
do
  expect_invalid "$url"
done

printf '%s\n' 'Configuration checks passed.'
