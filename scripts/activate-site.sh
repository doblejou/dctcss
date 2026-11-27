#!/bin/sh
set -eu

# Fase interna de deploy.sh, ejecutada desde el commit actualizado bajo su bloqueo.
work=$1
dest=$2
prefix=$3
cd -- "$work/src"
sh scripts/build.sh --deploy

release=$(mktemp -d -- "$dest/${prefix}release.XXXXXX")
mv -- public "$release/public"
chmod 755 "$release"
ln -s -- "$prefix$(basename -- "$release")/public" "$work/public"
mv -Tf -- "$work/public" "$dest/public"
