#!/bin/sh
set -eu

root=$(CDPATH='' cd -- "$(dirname -- "$0")/.." && pwd)
temp=$(mktemp -d -- "$root/benchmarks/.refresh.XXXXXX")
trap 'rm -rf -- "$temp"' EXIT
trap 'exit 1' HUP INT TERM

fetch() {
    curl -fsSL --proto '=https' --proto-redir '=https' --tlsv1.2 \
        --retry 2 --max-time 60 "$1" -o "$2"
}

snapshot="$temp/framework-sizes.txt"
cat > "$snapshot" <<EOF
# name|version|minified_size|gzip_size|reference
# measured-at: $(date -u +%F)
# Repetir con: make refresh-benchmarks
# Verificación: curl -fL --proto '=https' --tlsv1.2 URL -o FILE
# wc -c FILE; gzip -n -9 -c FILE | wc -c; sha256sum FILE
EOF

while IFS='|' read -r name package path; do
    fetch "https://registry.npmjs.org/$package/latest" "$temp/latest.json"
    # PHP interprets $argv and $data, not the shell.
    # shellcheck disable=SC2016
    # PHP lee sus argumentos; el shell no debe expandir sus variables.
    # shellcheck disable=SC2016
    version=$(php -r '
        $data = json_decode(file_get_contents($argv[1]), true, flags: JSON_THROW_ON_ERROR);
        if ($data["name"] !== $argv[2] || !preg_match("/^\\d+\\.\\d+\\.\\d+$/D", $data["version"])) exit(1);
        echo $data["version"];
    ' "$temp/latest.json" "$package")
    if [ "$package" = tailwindcss ]; then
        printf '%s|%s|variable|variable|El tamaño depende del CSS generado para cada proyecto.\n' "$name" "$version" >> "$snapshot"
        continue
    fi

    url="https://cdn.jsdelivr.net/npm/$package@$version/$path"
    fetch "$url" "$temp/framework.css"
    grep -q '{' "$temp/framework.css"
    gzip -n -9 -c "$temp/framework.css" > "$temp/framework.css.gz"
    digest=$(sha256sum "$temp/framework.css")
    printf '# sha256 %s %s: %s\n' "$name" "$version" "${digest%% *}" >> "$snapshot"
    printf '%s|%s|%s|%s|%s\n' "$name" "$version" "$(wc -c < "$temp/framework.css")" \
        "$(wc -c < "$temp/framework.css.gz")" "$url" >> "$snapshot"
done <<'EOF'
Pure.css|purecss|build/pure-min.css
Pico|@picocss/pico|css/pico.min.css
Spectre.css|spectre.css|dist/spectre.min.css
Bootstrap|bootstrap|dist/css/bootstrap.min.css
Foundation|foundation-sites|dist/css/foundation.min.css
Bulma|bulma|css/bulma.min.css
Materialize|@materializecss/materialize|dist/css/materialize.min.css
UIkit|uikit|dist/css/uikit.min.css
Tailwind CSS|tailwindcss|
EOF

mv -- "$snapshot" "$root/benchmarks/framework-sizes.txt"
