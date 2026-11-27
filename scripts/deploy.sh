#!/bin/sh
set -eu

fail() { echo "deploy: $*" >&2; exit 1; }
[ "$#" -le 1 ] || fail "Uso: sh scripts/deploy.sh [DIRECTORIO_DE_DESPLIEGUE]"
root=$(CDPATH='' cd -- "$(dirname -- "$0")/.." && pwd -P)
dest=$(realpath -m -- "${1:-$root}")
case "$dest" in
    "$root") prefix=.deploy/ ;;
    "$root"/*) fail "Usa la raíz del clon o un destino externo." ;;
    *) prefix='' ;;
esac
cd -- "$root"
php scripts/validate.php --assert-production
[ "$(git rev-parse --show-toplevel)" = "$root" ] || fail "Se necesita un clon del proyecto."
[ -f .env ] || fail "Falta el .env del servidor."

mkdir -p -- "$dest/$prefix"
exec 9<"$dest"
flock -n 9 || fail "Otro despliegue está en curso."
if [ -e "$dest/public" ] && [ ! -L "$dest/public" ]; then
    fail "public ya existe y no es un enlace."
fi
branch=$(git symbolic-ref --short HEAD)
remote=$(git config --get "branch.$branch.remote")
upstream=$(git config --get "branch.$branch.merge")
git pull --ff-only --no-rebase -- "$remote" "$upstream"
commit=$(git rev-parse --verify 'FETCH_HEAD^{commit}')
[ "$(git rev-parse HEAD)" = "$commit" ] || fail "La rama contiene commits locales sin publicar."

work=$(mktemp -d -- "$dest/$prefix.build.XXXXXX")
trap 'rm -rf -- "$work"' EXIT
trap 'exit 1' HUP INT TERM
mkdir "$work/src"
git archive --output="$work/src.tar" "$commit"
tar -xf "$work/src.tar" -C "$work/src"
install -m 600 -- .env "$work/src/.env"
sh "$work/src/scripts/activate-site.sh" "$work" "$dest" "$prefix"
echo "Publicado $commit en $dest/public"
