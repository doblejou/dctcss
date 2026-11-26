#!/bin/sh
set -eu

fail() { echo "$*" >&2; exit 1; }
[ "$#" -eq 2 ] || fail "Uso: sh scripts/prepare-rules-commit.sh REMOTO CLON_NUEVO"
root=$(CDPATH='' cd -- "$(dirname -- "$0")/.." && pwd -P)
clone=$(realpath -m -- "$2")
case "$clone/" in
    "$root/"*) fail "El clon debe estar fuera del proyecto." ;;
esac
[ ! -e "$clone" ] && [ ! -L "$2" ] || fail "El destino ya existe."

rules=$root/.ai-rules
[ -d "$rules" ] || fail "Falta $rules."
# No recorrer enlaces a directorios ni copiar dispositivos, enlaces rotos o metadatos Git.
invalid=$(find "$rules" -mindepth 1 \( -iname .git -o ! \( -type d -o -xtype f \) \) -print -quit)
[ -z "$invalid" ] || fail "Entrada no publicable: $invalid"
for hook in pre-commit pre-push; do
    [ -x "$root/.githooks/$hook" ] || fail "Falta el hook ejecutable $hook."
done
command -v gitleaks >/dev/null || fail "Falta Gitleaks."

# Reservar un destino nuevo: nunca reutilizar un clon con trabajo pendiente.
mkdir -- "$clone"
git clone -- "$1" "$clone"
cd -- "$clone"
git config --local core.hooksPath "$root/.githooks"
# El remoto también puede tener un enlace en la propia ruta .ai-rules.
git rm -rq --ignore-unmatch -- .ai-rules
mkdir -p -- .ai-rules
rsync -rL --perms -- "$rules/" .ai-rules/
git add -f -- .ai-rules/
git diff --cached --stat
echo "Preparación completada en $clone. Revisa git diff --cached antes del commit."
