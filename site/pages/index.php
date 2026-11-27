<?php

declare(strict_types=1);

$page = [
    'slug' => 'index',
    'title' => 'Framework CSS nativo',
    'description' => 'dctcss es un framework CSS nativo para construir interfaces con HTML. Incluye componentes, layouts y una guía para trabajar con agentes de IA.',
    'index' => [],
    'indexLabel' => '',
];
$page['content'] = static function (string $version, array $build): void {
    $minified = $build['cssMinified'];
    $readable = $build['css'];
?>
      <section class="site-hero">
        <div class="dct-container site-hero-grid">
          <div class="dct-stack site-hero-copy">
            <p class="site-eyebrow"><span aria-hidden="true"></span>CSS nativo. API estable. v<?= escape($version) ?></p>
            <h1>dctcss: CSS nativo y ligero.<br><span>Diseñado para la IA.</span></h1>
            <p class="site-hero-lead">Describe la pantalla que necesitas. Tu agente consulta la skill de dctcss para elegir los componentes y darles un estilo coherente con una sola hoja de CSS.</p>
            <div class="dct-cluster site-hero-actions">
              <a class="dct-button dct-is-primary dct-is-medium dct-is-fullwidth-mobile" href="#uso-con-ia">Ver el prompt para mi agente de IA</a>
              <a class="dct-button dct-is-medium dct-is-fullwidth-mobile" href="components.html">Ver componentes <span aria-hidden="true">→</span></a>
            </div>
            <p class="site-hero-note"><span aria-hidden="true">✓</span> MIT · Sin JavaScript · Listo para producción</p>
          </div>

          <section class="site-product-window" data-dct-theme="light" aria-labelledby="sample-title">
            <div class="site-window-bar dct-cluster dct-justify-between"><span>Cuaderno de interfaz</span><span>DCT / UI</span></div>
            <div class="site-sample-body dct-stack">
              <p class="site-kicker">Muestra 01 · Componentes</p>
              <h2 class="dct-title" id="sample-title">Anatomía de un panel</h2>
              <article class="dct-card dct-is-elevated">
                <div class="dct-card-body">
                  <div class="dct-cluster dct-justify-between"><h3 class="dct-card-title">Actividad semanal</h3><span class="dct-badge dct-is-success">Al día</span></div>
                  <p class="dct-card-content">28 tareas completadas esta semana.</p>
                  <div class="site-mini-chart" aria-hidden="true"><span></span><span></span><span></span><span></span><span></span><span></span></div>
                  <div class="dct-card-actions dct-cluster"><a class="dct-button dct-is-primary" href="recipes.html#dashboard">Ver el panel <span aria-hidden="true">→</span></a></div>
                </div>
              </article>
              <details class="dct-disclosure">
                <summary class="dct-disclosure-summary">¿Qué piezas utiliza?</summary>
                <div class="dct-disclosure-content"><p>Una tarjeta agrupa el indicador y su enlace. La insignia muestra el estado y la fila flexible ajusta el título al espacio disponible. El gráfico usa CSS de la aplicación.</p></div>
              </details>
            </div>
          </section>
        </div>
      </section>

      <section class="site-proof" aria-label="Datos principales de dctcss">
        <div class="dct-container site-proof-grid">
          <p><strong>0</strong><span>JavaScript distribuido</span></p>
          <p><strong>0</strong><span>Dependencias runtime</span></p>
          <p><strong>dct-</strong><span>Prefijo sin colisiones</span></p>
          <p><strong><?= escape($version) ?></strong><span>Release estable</span></p>
        </div>
      </section>

      <section class="dct-container site-section site-benefits" aria-labelledby="benefits-title">
        <div class="site-section-heading">
          <p class="site-kicker">Por qué dctcss</p>
          <h2 id="benefits-title">Qué aporta dctcss</h2>
        </div>
        <div class="site-benefit-grid">
          <article class="dct-card site-feature-card"><div class="dct-card-body"><span class="site-feature-number">01</span><h3 class="dct-card-title">CSS moderno nativo</h3><p class="dct-card-content">Custom properties, Grid, Flexbox y capas de cascada. Sin preprocesadores que oculten lo que llega al navegador.</p></div></article>
          <article class="dct-card site-feature-card site-feature-card-accent"><div class="dct-card-body"><span class="site-feature-number">02</span><h3 class="dct-card-title">Cero JavaScript distribuido</h3><p class="dct-card-content">dctcss se ocupa de presentar. Tu aplicación mantiene el control total de sus datos y comportamiento.</p></div></article>
          <article class="dct-card site-feature-card"><div class="dct-card-body"><span class="site-feature-number">03</span><h3 class="dct-card-title">Accesible por diseño</h3><p class="dct-card-content">Foco visible, semántica, zoom, modo oscuro y contenido estrecho forman parte del contrato.</p></div></article>
          <article class="dct-card site-feature-card"><div class="dct-card-body"><span class="site-feature-number">04</span><h3 class="dct-card-title">API sin colisiones</h3><p class="dct-card-content">Clases, tokens y animaciones propios empiezan por <code>dct-</code>: sabes qué pertenece al framework.</p></div></article>
          <article class="dct-card site-feature-card site-feature-wide"><div class="dct-card-body"><span class="site-feature-number">05</span><h3 class="dct-card-title">De una petición a una interfaz</h3><p class="dct-card-content">¿Cómo pedirías a tu agente una ayuda desplegable que funcione con teclado? Lee la petición y prueba el resultado con el teclado.</p><a href="fundamentals.html#ejemplo">Seguir el ejemplo con IA <span aria-hidden="true">→</span></a></div></article>
        </div>
      </section>

      <section class="dct-container site-section site-chapter site-agent" id="uso-con-ia" aria-labelledby="agent-title">
        <div class="dct-stack">
          <p class="dct-meta">02 / Uso con IA</p>
          <h2 class="dct-title" id="agent-title">Crea interfaces con tu agente de IA.</h2>
          <p>Abre tu proyecto en un agente con acceso a la web y permiso de escritura. Pega este mensaje para que instale la skill.</p>
          <a href="fundamentals.html#trabajar-con-ia">Ver la guía de instalación y un ejemplo con IA →</a>
        </div>
        <?= agent_install_prompt() ?>
      </section>

      <section class="dct-container site-section site-chapter" aria-labelledby="browser-support-title">
        <div class="dct-stack">
          <p class="dct-meta">03 / Compatibilidad</p>
          <h2 class="dct-title" id="browser-support-title">Comprueba tus navegadores objetivo</h2>
          <p>Versiones mínimas para dctcss <?= escape($version) ?>.</p>
        </div>
        <div class="dct-stack">
          <dl class="site-browser-list">
<?php foreach ($build['browserMinimums'] as $browser => $minimum): ?>
            <div><dt><?= escape($browser) ?></dt><dd><?= escape($minimum) ?>+</dd></div>
<?php endforeach; ?>
          </dl>
          <p class="dct-text-muted">En Edge y Android WebView cuenta la versión de Chromium; en iOS, la de Safari/WebKit. Internet Explorer no es compatible.</p>
        </div>
      </section>

      <section class="dct-container site-section site-chapter site-downloads" id="downloads" aria-labelledby="downloads-title">
        <div class="dct-stack">
          <p class="dct-meta">04 / Uso Manual</p>
          <h2 class="dct-title" id="downloads-title">Descarga manual del CSS</h2>
          <p>Elige un solo archivo: minificado para publicar o legible para inspeccionar el CSS.</p>
          <p class="dct-text-muted">Ambos enlaces sirven la <a href="release.json">versión actual</a>. Consulta los <a href="changelog.html">cambios</a> o las <a href="<?= escape(GITHUB_REPOSITORY) ?>/releases">versiones anteriores en GitHub</a>.</p>
        </div>
        <div class="dct-grid">
<?php foreach ([[$minified, 'Producción', 'CSS minificado recomendado'], [$readable, 'Desarrollo', 'CSS legible para inspección']] as [$artifact, $use, $description]): ?>
          <article class="dct-card">
            <div class="dct-card-body">
              <p class="dct-card-subtitle"><?= escape($use) ?></p>
              <h3 class="dct-card-title"><?= escape($artifact['filename']) ?></h3>
              <p class="dct-card-content"><?= escape($description) ?></p>
              <dl class="dct-stack">
                <div><dt>Versión</dt><dd><?= escape($version) ?></dd></div>
                <div><dt><?= $use === 'Producción' ? 'Tamaño minificado (sin gzip)' : 'Tamaño sin minificar' ?></dt><dd><data value="<?= $artifact['bytes'] ?>"><?= $artifact['bytes'] ?> bytes</data></dd></div>
<?php if ($use === 'Producción'): ?>
                <div><dt>Tamaño minificado con gzip</dt><dd><data value="<?= $artifact['gzipBytes'] ?>"><?= $artifact['gzipBytes'] ?> bytes</data></dd></div>
<?php endif; ?>
              </dl>
              <div class="dct-card-actions dct-cluster"><a class="dct-button" href="downloads/<?= escape($artifact['filename']) ?>" download>Descargar <?= escape($artifact['filename']) ?></a></div>
            </div>
          </article>
<?php endforeach; ?>
        </div>
      </section>

      <section class="dct-container site-section site-chapter" aria-labelledby="paths-title">
        <div class="dct-stack">
          <p class="dct-meta">05 / Galería</p>
          <h2 class="dct-title" id="paths-title">Explora las interfaces que puedes pedir</h2>
        </div>
        <nav class="site-topic-list" aria-labelledby="paths-title">
          <div><h3>Diseño</h3><a href="fundamentals.html#temas">Temas claro y oscuro</a><a href="layout.html">Distribución de página</a><a href="utilities.html">Detalles de diseño</a></div>
          <div><h3>Piezas</h3><a href="components.html">Componentes</a><a href="forms.html">Formularios</a><a href="navigation.html">Menús y navegación</a></div>
          <div><h3>Pantallas</h3><a href="recipes.html">Ejemplos de interfaz</a><a href="application.html">Aplicación completa</a><a href="changelog.html">Cambios de versión</a></div>
        </nav>
      </section>

      <section class="dct-container site-section site-chapter" aria-labelledby="sizes-title">
        <div class="dct-stack">
          <p class="dct-meta">06 / Comparativa de tamaño</p>
          <h2 class="dct-title" id="sizes-title">¿Cuánto pesa el CSS?</h2>
          <p><strong><?= comparison_size($minified['bytes']) ?></strong> minificado · <?= comparison_size($minified['gzipBytes']) ?> con gzip.</p>
          <p class="dct-text-muted">De menor a mayor tamaño minificado sin gzip. 1 KB = 1000 bytes. Gzip usa el nivel 9.</p>
          <p>dctcss se mide en cada build. Última medición del CSS publicado por los demás frameworks: <time datetime="<?= escape($build['benchmarks']['date']) ?>"><?= escape($build['benchmarks']['date']) ?></time>. En Tailwind el tamaño depende del CSS que genere cada proyecto.</p>
        </div>
        <div class="dct-table-wrap" tabindex="0" role="region" aria-label="Comparativa de tamaños CSS minificados">
          <table class="dct-table dct-is-striped dct-is-hoverable site-size-table">
            <caption class="dct-table-caption">CSS completo, sin JavaScript, iconos ni fuentes</caption>
            <thead><tr><th scope="col">Framework</th><th scope="col">Versión</th><th class="dct-is-numeric" scope="col">CSS minificado</th><th class="dct-is-numeric" scope="col">Con gzip</th><th scope="col">Medición</th></tr></thead>
            <tbody>
<?php foreach ($build['benchmarks']['rows'] as $benchmark): ?>
              <tr<?= $benchmark['name'] === 'dctcss' ? ' class="dct-is-selected"' : '' ?>><th scope="row"><?= escape($benchmark['name']) ?></th><td><?= escape($benchmark['version']) ?></td><td class="dct-is-numeric"><?= escape(comparison_size($benchmark['bytes'])) ?></td><td class="dct-is-numeric"><?= escape(comparison_size($benchmark['gzipBytes'])) ?></td><td><?= escape($benchmark['measurement']) ?></td></tr>
<?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </section>

      <aside class="dct-container dct-message dct-is-warning site-limits" aria-labelledby="limits-title">
        <div class="dct-message-body">
          <p class="dct-meta">Límites explícitos</p>
          <h2 class="dct-message-title" id="limits-title">El CSS no ejecuta tu aplicación.</h2>
          <p class="dct-message-content">dctcss no valida ni envía datos, no sincroniza estados interactivos, no ordena ni pagina tablas y no aporta JavaScript de aplicación. Tu código conserva esas responsabilidades y la accesibilidad de cada cambio dinámico.</p>
        </div>
      </aside>

      <section class="site-cta" aria-labelledby="cta-title">
        <div class="dct-container site-cta-inner">
          <p class="site-kicker">Siguiente paso</p>
          <h2 id="cta-title">Crea tu primera interfaz</h2>
          <p>Instala la skill y pide a tu agente una pantalla para tu proyecto.</p>
          <div class="dct-cluster">
            <a class="dct-button dct-is-primary dct-is-medium" href="#uso-con-ia">Usa el prompt</a>
            <a class="dct-button dct-is-medium" href="recipes.html">Ver ejemplos</a>
          </div>
        </div>
      </section>
<?php
};
return $page;
