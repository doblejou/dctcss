#!/bin/sh

set -eu

SCRIPT_DIR=$(CDPATH='' cd -- "$(dirname -- "$0")" && pwd)
ROOT_DIR=$(CDPATH='' cd -- "$SCRIPT_DIR/.." && pwd)
PUBLIC_DIR="$ROOT_DIR/public"
PORT=${PORT:-8000}

case "$PORT" in
  ''|*[!0-9]*) printf '%s\n' 'serve: PORT must be an integer between 1 and 65535.' >&2; exit 1 ;;
esac
[ "$PORT" -ge 1 ] && [ "$PORT" -le 65535 ] || { printf '%s\n' 'serve: PORT must be between 1 and 65535.' >&2; exit 1; }

[ -d "$PUBLIC_DIR" ] || { printf '%s\n' 'serve: public/ is missing; run scripts/build.sh first.' >&2; exit 1; }
cd -- "$PUBLIC_DIR"

if command -v python3 >/dev/null 2>&1; then
  printf 'Serving dctcss at http://localhost:%s/\n' "$PORT"
  exec python3 -m http.server --bind 127.0.0.1 "$PORT"
fi

if command -v busybox >/dev/null 2>&1; then
  printf 'Serving dctcss at http://localhost:%s/\n' "$PORT"
  exec busybox httpd -f -p "127.0.0.1:$PORT" -h "$PUBLIC_DIR"
fi

printf '%s\n' 'serve: Python 3 or BusyBox is required to start the local server.' >&2
exit 1
