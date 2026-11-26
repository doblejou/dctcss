#!/bin/sh
set -eu

root=$(git rev-parse --show-toplevel)
cd "$root"
tag="v$(cat VERSION)"
commit=$(git rev-parse --verify "refs/tags/$tag^{commit}")
[ "$commit" = "$(git rev-parse HEAD)" ] || {
  echo "La etiqueta $tag debe apuntar al commit actual." >&2
  exit 1
}

gitleaks detect --redact --no-banner --log-opts="--all $commit"
temp=$(mktemp -d -- "${TMPDIR:-/tmp}/dctcss-release.XXXXXX")
trap 'rm -rf -- "$temp"' EXIT
trap 'exit 1' HUP INT TERM
git archive --output="$temp/dist.tar" "$commit" dist
tar -xf "$temp/dist.tar" -C "$temp"
(cd "$temp/dist" && sha256sum -c SHA256SUMS)
gitleaks detect --redact --no-banner --no-git --source "$temp/dist"
remote_tag=$(git ls-remote --exit-code origin "refs/tags/$tag")
[ "$(git rev-parse "refs/tags/$tag")" = "${remote_tag%%[[:space:]]*}" ] || {
  echo "La etiqueta remota $tag no coincide con la local." >&2
  exit 1
}
gh release create "$tag" "$temp"/dist/* --repo "$(git remote get-url origin)" \
  --verify-tag --title "dctcss $tag" --generate-notes
