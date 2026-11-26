<?php

declare(strict_types=1);

$page = [
    'slug' => 'changelog',
    'title' => 'Cambios',
    'description' => 'Historial de cambios de dctcss {{VERSION}} y contrato para descubrir y evaluar nuevas releases.',
    'index' => [
        'releases' => 'Releases',
        'actualizacion' => 'Cómo actualizar',
    ],
    'indexLabel' => 'Índice de cambios',
];
$page['content'] = static function (string $version, array $build): void {
?>
        <header class="site-doc-title dct-stack">
          <p class="dct-meta">Releases / v<?= escape($version) ?></p>
          <h1 class="dct-display">Cambios públicos, actualización deliberada.</h1>
          <p><code>release.json</code> anuncia la versión estable actual. Este historial explica qué cambió antes de sustituir el CSS de una aplicación.</p>
          <div class="dct-cluster">
            <a class="dct-button dct-is-primary" href="release.json">Consultar release.json</a>
            <a class="dct-button" href="index.html#downloads">Ver descargas latest</a>
          </div>
        </header>

        <div class="dct-stack" id="releases">
          <?= render_changelog($build['changelog']) ?>
        </div>

        <section class="site-reference" id="actualizacion" aria-labelledby="actualizacion-title">
          <h2 id="actualizacion-title">Cómo actualizar</h2>
          <ol>
            <li>Compara tu versión instalada con <code>version</code> en <a href="release.json"><code>release.json</code></a>.</li>
            <li>Lee las entradas intermedias de este historial.</li>
            <li>Actualiza el artefacto en un entorno de pruebas y ejecuta las validaciones de tu aplicación.</li>
            <li>Publica el cambio de forma deliberada; no reemplaces automáticamente el CSS de producción.</li>
          </ol>
          <div class="site-note"><strong>URLs latest</strong><code>/downloads/dct.css</code> y <code>/downloads/dct.min.css</code> cambian con cada release y no son URLs históricas inmutables.</div>
        </section>
<?php
};
return $page;
