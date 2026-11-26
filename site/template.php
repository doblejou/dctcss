<?php

declare(strict_types=1);

const GITHUB_REPOSITORY = 'https://github.com/doblejou/dctcss';

const SITE_PAGES = [
    'index' => 'Inicio',
    'fundamentals' => 'Empezar con IA',
    'layout' => 'Layout',
    'components' => 'Componentes',
    'forms' => 'Formularios',
    'navigation' => 'Navegación',
    'utilities' => 'Utilidades',
    'application' => 'Aplicación',
    'recipes' => 'Ejemplos',
    'changelog' => 'Cambios',
    'aviso-legal' => 'Aviso legal',
    'privacidad' => 'Política de privacidad',
];

const HEADER_PAGES = [
    'index' => 'Inicio',
    'fundamentals' => 'Empezar con IA',
    'components' => 'Componentes',
    'forms' => 'Formularios',
    'application' => 'Aplicación',
    'recipes' => 'Ejemplos',
    'changelog' => 'Cambios',
];

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE | ENT_XML1, 'UTF-8');
}

################################################
#
#  comparison_size
#
#  Convierte bytes a KB decimales y conserva las estimaciones de origen.
#
################################################
function comparison_size(int|string $size): string
{
    if (!ctype_digit((string) $size))
    {
        return (string) $size;
    }

    return number_format((int) $size / 1000, 2, ',', '.') . ' KB';
}

function canonical_url(string $siteUrl, string $slug): string
{
    return $slug === 'index' ? $siteUrl . '/' : $siteUrl . '/' . $slug . '.html';
}

################################################
#
#  agent_install_prompt
#
#  Presenta el mismo encargo copiable en la portada y en la guía.
#
################################################
function agent_install_prompt(): string
{
    return code_example(
        'instalar-con-agente',
        'Prompt para tu agente',
        "Obtén la skill del repositorio oficial github.com/doblejou/dctcss. Usa la etiqueta de la versión de dctcss del proyecto; si es nuevo, consulta dctcss.com/release.json.\n"
        . "Instala la carpeta skills/dctcss completa en la ruta de skills que uses para este proyecto. Lee SKILL.md y comprueba que el CSS y la skill corresponden a la misma versión.",
        'instrucciones de instalación para el agente'
    );
}

function code_example(string $id, string $language, string $source, string $label): string
{
    if (str_contains($source, 'data-site-')) {
        throw new InvalidArgumentException("Copyable example $id contains a private data-site-* attribute.");
    }
    $target = $id . '-code';
    return '<div class="dct-card dct-is-elevated">'
        . '<div class="dct-card-body"><div class="dct-cluster dct-justify-between"><span class="dct-badge">' . escape($language) . '</span>'
        . '<button class="dct-button dct-is-small" type="button" data-site-copy="' . escape($target) . '" hidden'
        . ' aria-label="Copiar ' . escape($label) . '">Copiar</button></div>'
        . '<div class="dct-content"><pre tabindex="0"><code id="' . escape($target) . '">' . escape(trim($source)) . '</code></pre></div>'
        . '</div></div>';
}

function inert_form_preview(string $source): string
{
    $preview = preg_replace('~<form\b[^>]*>~', '<div>', $source, 1);
    $preview = preg_replace('~</form>~', '</div>', $preview ?? $source, 1);
    return str_replace('type="submit"', 'type="button"', $preview ?? $source);
}

function inert_html_preview(string $source): string
{
    $preview = inert_form_preview($source);
    $preview = preg_replace('~<main\b([^>]*)>~', '<div$1>', $preview);
    return preg_replace('~</main>~', '</div>', $preview ?? $source) ?? $source;
}

function recipe_code_block(string $heading, string $language): string
{
    static $lines = null;
    static $blocks = [];
    $key = $heading . "\0" . $language;
    if (isset($blocks[$key])) {
        return $blocks[$key];
    }
    if ($lines === null) {
        $path = dirname(__DIR__) . '/docs/RECIPES.md';
        $contents = is_file($path) ? file_get_contents($path) : false;
        if ($contents === false) {
            throw new RuntimeException("Cannot read canonical recipes: $path");
        }
        $lines = preg_split('/\R/', $contents) ?: [];
    }

    $headingLine = '## ' . $heading;
    $fence = '```' . $language;
    $insideSection = false;
    $insideBlock = false;
    $block = [];
    foreach ($lines as $line) {
        if ($line === $headingLine) {
            $insideSection = true;
            continue;
        }
        if ($insideSection && str_starts_with($line, '## ')) {
            break;
        }
        if ($insideSection && $line === $fence) {
            $insideBlock = true;
            $block = [];
            continue;
        }
        if ($insideBlock && $line === '```') {
            return $blocks[$key] = implode("\n", $block);
        }
        if ($insideBlock) {
            $block[] = $line;
        }
    }

    throw new RuntimeException("Missing $language recipe block under: $headingLine");
}

function enhance_documentation(string $html): string
{
    $html = preg_replace(
        '~<div class="site-note">\s*<strong>(.*?)</strong>\s*(.*?)\s*</div>~s',
        '<aside class="dct-message dct-is-info"><div class="dct-message-body"><p class="dct-message-title">$1</p><div class="dct-message-content">$2</div></div></aside>',
        $html,
    ) ?? $html;
    return str_replace(
        [
            'site-doc-title dct-stack',
            'site-reference',
            'site-stage',
            ' site-preview-card',
        ],
        [
            'dct-stack dct-gap-5',
            'dct-content',
            'dct-content',
            '',
        ],
        $html,
    );
}

function changelog_inline(string $text): string
{
    return preg_replace('/`([^`\r\n]+)`/', '<code>$1</code>', escape($text)) ?? escape($text);
}

function render_changelog(string $markdown): string
{
    $lines = preg_split('/\R/', $markdown) ?: [];
    if (array_shift($lines) !== '# Changelog') {
        throw new InvalidArgumentException('CHANGELOG.md must start with its canonical title.');
    }

    $html = '';
    $paragraph = [];
    $listOpen = false;
    $sectionOpen = false;
    $flushParagraph = static function () use (&$html, &$paragraph): void {
        if ($paragraph !== []) {
            $html .= '<p>' . changelog_inline(implode(' ', $paragraph)) . '</p>';
            $paragraph = [];
        }
    };
    $closeList = static function () use (&$html, &$listOpen): void {
        if ($listOpen) {
            $html .= '</ul>';
            $listOpen = false;
        }
    };

    foreach ($lines as $line) {
        if ($line === '') {
            $flushParagraph();
            $closeList();
            continue;
        }
        if (preg_match('/^## (\d+\.\d+\.\d+) - (\d{4}-\d{2}-\d{2})$/', $line, $match) === 1) {
            $flushParagraph();
            $closeList();
            if ($sectionOpen) {
                $html .= '</section>';
            }
            $id = 'release-' . str_replace('.', '-', $match[1]);
            $html .= '<section class="dct-content" aria-labelledby="' . $id . '">'
                . '<h2 id="' . $id . '">' . escape($match[1]) . ' <span class="dct-meta">' . escape($match[2]) . '</span></h2>';
            $sectionOpen = true;
            continue;
        }
        if (str_starts_with($line, '- ')) {
            $flushParagraph();
            if (!$listOpen) {
                $html .= '<ul>';
                $listOpen = true;
            }
            $html .= '<li>' . changelog_inline(substr($line, 2)) . '</li>';
            continue;
        }
        if (str_starts_with($line, '#')) {
            throw new InvalidArgumentException("Unsupported CHANGELOG.md heading: $line");
        }
        $closeList();
        $paragraph[] = $line;
    }
    $flushParagraph();
    $closeList();
    if ($sectionOpen) {
        $html .= '</section>';
    }
    return $html;
}

function page_navigation(string $current): string
{
    $keys = array_keys(array_diff_key(SITE_PAGES, ['aviso-legal' => true, 'privacidad' => true]));
    $position = array_search($current, $keys, true);
    if ($position === false || $current === 'index') {
        return '';
    }

    $previous = $keys[$position - 1];
    $next = $keys[($position + 1) % count($keys)];
    return '<nav class="dct-cluster dct-justify-between dct-p-6" aria-label="Páginas anterior y siguiente">'
        . '<a class="dct-button" href="' . $previous . '.html">Anterior: ' . SITE_PAGES[$previous] . '</a>'
        . '<a class="dct-button dct-is-primary" href="' . $next . '.html">Siguiente: ' . SITE_PAGES[$next] . '</a>'
        . '</nav>';
}

function render_page(array $page, array $build): string
{
    $version = $build['version'];
    $description = str_replace('{{VERSION}}', $version, $page['description']);
    $title = $page['title'] . ' - dctcss ' . $version;
    $canonical = canonical_url($build['siteUrl'], $page['slug']);

    ob_start();
    ($page['content'])($version, $build);
    $content = enhance_documentation((string) ob_get_clean());
    $current = $page['slug'];
    $headerCurrent = in_array($current, ['layout', 'navigation', 'utilities'], true)
        ? 'fundamentals'
        : $current;

    ob_start();
    ?>
<!doctype html>
<!-- Generated by site/build.php; do not edit this file directly. -->
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= escape($description) ?>">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= escape($title) ?>">
    <meta property="og:description" content="<?= escape($description) ?>">
    <meta property="og:url" content="<?= escape($canonical) ?>">
    <title><?= escape($title) ?></title>
    <link rel="canonical" href="<?= escape($canonical) ?>">
    <link rel="icon" type="image/svg+xml" href="assets/favicon.svg">
    <link rel="stylesheet" href="downloads/<?= escape($build['css']['filename']) ?>?v=<?= $build['frameworkCssTime'] ?>">
    <link rel="stylesheet" href="assets/site.css?v=<?= $build['siteCssTime'] ?>">
    <script src="assets/site.js" defer></script>
  </head>
  <body class="site-page site-page-<?= escape($current) ?>">
    <a class="dct-skip-link" href="#contenido">Saltar al contenido</a>
    <header class="site-header">
      <nav class="dct-container dct-navbar dct-is-inverse site-navbar" aria-label="Navegación principal" data-dct-density="compact">
        <a class="dct-navbar-brand" href="index.html" aria-label="dctcss, sitio oficial">
          <img src="assets/favicon.svg" width="32" height="32" alt=""><span>dctcss</span>
        </a>
        <button class="dct-navbar-toggle dct-button dct-is-small" type="button" aria-expanded="true" aria-controls="site-global-menu" data-site-navbar-toggle hidden>Menú</button>
        <div class="dct-navbar-menu" id="site-global-menu">
<?php foreach (HEADER_PAGES as $slug => $label): ?>
          <a class="dct-navbar-item" href="<?= $slug ?>.html"<?= $slug === $headerCurrent ? ' aria-current="page"' : '' ?>><?= $label ?></a>
<?php endforeach; ?>
          <div class="dct-navbar-actions">
            <span class="site-version">v<?= escape($version) ?></span>
            <a class="dct-navbar-item" href="<?= escape(GITHUB_REPOSITORY) ?>" aria-label="Repositorio de dctcss en GitHub" title="GitHub">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M12 .3a12 12 0 0 0-3.79 23.4c.6.11.82-.26.82-.58v-2.23c-3.34.73-4.04-1.42-4.04-1.42-.55-1.38-1.33-1.75-1.33-1.75-1.09-.74.08-.73.08-.73 1.2.08 1.84 1.24 1.84 1.24 1.07 1.83 2.81 1.3 3.5.99.11-.78.42-1.31.76-1.61-2.67-.3-5.47-1.33-5.47-5.93 0-1.31.47-2.38 1.24-3.22-.12-.3-.54-1.52.12-3.18 0 0 1.01-.32 3.3 1.23a11.5 11.5 0 0 1 6 0c2.29-1.55 3.3-1.23 3.3-1.23.66 1.66.24 2.88.12 3.18.77.84 1.24 1.91 1.24 3.22 0 4.61-2.81 5.62-5.49 5.92.44.38.82 1.12.82 2.26v3.26c0 .32.22.7.83.58A12 12 0 0 0 12 .3Z"/></svg>
            </a>
            <a class="dct-button dct-is-primary dct-is-small" href="fundamentals.html#trabajar-con-ia">Usa el prompt</a>
          </div>
        </div>
      </nav>
    </header>
<?php if ($current === 'index'): ?>
    <main id="contenido" tabindex="-1"><?= $content ?></main>
<?php else: ?>
    <div class="dct-container dct-sidebar-layout dct-p-6">
      <details class="dct-disclosure site-mobile-index">
        <summary class="dct-disclosure-summary">En esta página: <?= escape(SITE_PAGES[$current]) ?></summary>
        <nav class="dct-disclosure-content dct-menu site-mobile-index-menu" aria-label="<?= escape($page['indexLabel']) ?>">
          <ul class="dct-menu-list">
<?php foreach ($page['index'] as $id => $label): ?>
            <li><a class="dct-menu-item" href="#<?= escape($id) ?>"><?= escape($label) ?></a></li>
<?php endforeach; ?>
          </ul>
        </nav>
      </details>
      <aside class="dct-sidebar site-desktop-index">
        <nav class="dct-menu" aria-label="<?= escape($page['indexLabel']) ?>">
          <p class="dct-meta">En esta página</p><ul class="dct-menu-list">
<?php foreach ($page['index'] as $id => $label): ?>
            <li><a class="dct-menu-item" href="#<?= escape($id) ?>"><?= escape($label) ?></a></li>
<?php endforeach; ?>
          </ul>
        </nav>
      </aside>
      <main class="dct-main dct-stack dct-gap-6" id="contenido" tabindex="-1">
<?= $content ?>
<?= page_navigation($current) ?>
      </main>
    </div>
<?php endif; ?>
    <footer class="site-footer">
      <div class="dct-container site-footer-grid">
        <div><a class="site-footer-brand" href="index.html"><img src="assets/favicon.svg" width="32" height="32" alt="">dctcss</a><p>CSS nativo y ligero.<br>Diseñado para la IA.</p></div>
        <nav aria-label="Enlaces del pie"><strong>Producto</strong><a href="components.html">Componentes</a><a href="application.html">Aplicación</a><a href="index.html#downloads">Descargas</a></nav>
        <nav aria-label="Recursos del pie"><strong>Recursos</strong><a href="fundamentals.html">Empezar con IA</a><a href="recipes.html">Ejemplos</a><a href="changelog.html">Cambios</a></nav>
      </div>
      <div class="dct-container site-footer-bottom">
        <span>© <?= escape(substr($build['releaseDate'], 0, 4)) ?> <a href="https://daycomtech.com/">Daycom Technologies</a> · Licencia MIT</span>
        <nav class="dct-cluster" aria-label="Información legal"><a href="aviso-legal.html">Aviso legal</a><a href="privacidad.html">Política de privacidad</a></nav>
      </div>
    </footer>
    <p class="dct-sr-only" role="status" aria-live="polite" data-site-status></p>
  </body>
</html>
<?php
    return (string) ob_get_clean();
}
