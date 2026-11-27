#!/bin/sh
set -eu

modules=${DCTCSS_AUDIT_MODULES:-"$HOME/.local/share/dctcss-audit/node_modules"}
NODE_PATH="$modules:$(npm root --global)"
export NODE_PATH
# El wrapper admite una sola ruta y procesa todos sus archivos como HTML.
html_dir=$(mktemp -d)
trap 'rm -rf -- "$html_dir"' EXIT
cp -- "$1"/*.html "$html_dir/"
jar="${XDG_CACHE_HOME:-$HOME/.cache}/dctcss/vnu.jar"
if ! diagnostics=$(/opt/dctserver/scripts/dct_vnu/dct_vnu_validate_html.sh \
    --path "$html_dir" --jar "$jar" --quiet --format gnu 2>&1); then
    printf '%s\n' "$diagnostics" >&2
    exit 1
fi
if [ -n "$diagnostics" ]; then
    printf '%s\n' "$diagnostics" >&2
    if printf '%s\n' "$diagnostics" | grep -q 'info warning:'; then
        exit 1
    fi
fi
node "$(dirname "$0")/audit-site.cjs" "$1"
