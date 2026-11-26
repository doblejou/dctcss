#!/bin/sh
set -eu

root=$(git rev-parse --show-toplevel)
cd "$root"
tag="v$(cat VERSION)"
if [ -n "${WEB_RELEASE_TAG:-}" ]; then
  case "$WEB_RELEASE_TAG" in
    web-[0-9]*) tag=$WEB_RELEASE_TAG ;;
    *) echo "La etiqueta del sitio debe empezar por web- y una fecha." >&2; exit 1 ;;
  esac
fi
commit=$(git rev-parse --verify "refs/tags/$tag^{commit}")
[ "$commit" = "$(git rev-parse HEAD)" ] || {
  echo "La etiqueta $tag debe apuntar al commit actual." >&2
  exit 1
}

gitleaks detect --redact --no-banner --log-opts="--all $commit"
temp=$(mktemp -d -- "${TMPDIR:-/tmp}/dctcss-release.XXXXXX")
trap 'rm -rf -- "$temp"' EXIT
remote_tag=$(git ls-remote --exit-code origin "refs/tags/$tag")
[ "$(git rev-parse "refs/tags/$tag")" = "${remote_tag%%[[:space:]]*}" ] || {
  echo "La etiqueta remota $tag no coincide con la local." >&2
  exit 1
}
if [ -n "${WEB_RELEASE_TAG:-}" ]; then
  git archive --output="$temp/site-source.tar" "$commit"
  mkdir "$temp/source"
  tar -xf "$temp/site-source.tar" -C "$temp/source"
  DCTCSS_ENV=production SITE_URL=https://dctcss.com sh "$temp/source/scripts/build.sh" "$temp/build"
  gitleaks detect --redact --no-banner --no-git --source "$temp/build/public"
  tar -czf "$temp/dctcss-web.tar.gz" -C "$temp/build" public
  gh release create "$tag" "$temp/dctcss-web.tar.gz" --repo "$(git remote get-url origin)" \
    --verify-tag --latest=false --title "Web de dctcss · $tag" \
    --notes "Actualización del sitio y la documentación. Versión del framework: $(cat VERSION) (sin cambios). El adjunto contiene el sitio estático."
  exit 0
fi
trap 'exit 1' HUP INT TERM
git archive --output="$temp/dist.tar" "$commit" dist
tar -xf "$temp/dist.tar" -C "$temp"
(cd "$temp/dist" && sha256sum -c SHA256SUMS)
gitleaks detect --redact --no-banner --no-git --source "$temp/dist"
gh release create "$tag" "$temp"/dist/* --repo "$(git remote get-url origin)" \
  --verify-tag --title "dctcss $tag" --generate-notes
