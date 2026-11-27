<?php

declare(strict_types=1);

$page = [
    'slug' => 'changelog',
    'title' => 'Historial de cambios',
    'description' => 'Historial de cambios de dctcss {{VERSION}} y contrato para descubrir y evaluar nuevas releases.',
    'index' => [
        'releases' => 'Releases',
        'actualizacion' => 'Cómo actualizar',
    ],
    'indexLabel' => 'Índice de cambios',
];
$page['intro'] = static function (string $version): void
{
?>
          <p>Consulta qué ha cambiado entre tu versión y la actual (<?= escape($version) ?>).</p>
          <div class="dct-cluster">
            <a class="dct-button dct-is-primary" href="release.json">Consultar release.json</a>
            <a class="dct-button" href="fundamentals.html#actualizar-con-ia">Actualizar con IA</a>
          </div>
<?php
};
$page['content'] = static function (string $version, array $build): void {
?>

        <div class="dct-stack" id="releases">
          <?= render_changelog($build['changelog']) ?>
        </div>

        <section class="dct-content" id="actualizacion" aria-labelledby="actualizacion-title">
          <h2 id="actualizacion-title">Cómo actualizar</h2>
          <ol>
            <li>Compara tu versión instalada con <code>version</code> en <a href="release.json"><code>release.json</code></a>.</li>
            <li>Lee las entradas intermedias de este historial.</li>
            <li>Actualiza el artefacto en un entorno de pruebas y ejecuta las validaciones de tu aplicación.</li>
            <li>Publica el cambio de forma deliberada; no reemplaces automáticamente el CSS de producción.</li>
          </ol>
          <aside class="dct-message dct-is-info" aria-labelledby="latest-title">
            <div class="dct-message-body">
              <h3 class="dct-message-title" id="latest-title">URLs latest</h3>
              <p class="dct-message-content"><code>/downloads/dct.css</code> y <code>/downloads/dct.min.css</code> cambian con cada release y no son URLs históricas inmutables.</p>
            </div>
          </aside>
        </section>
<?php
};
return $page;
