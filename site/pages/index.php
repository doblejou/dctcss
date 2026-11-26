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
    $install = '<link rel="stylesheet" href="' . $build['siteUrl'] . '/downloads/' . $minified['filename'] . '">';
?>
      <section class="site-hero">
        <div class="dct-container site-hero-grid">
          <div class="dct-stack site-hero-copy">
            <p class="site-eyebrow"><span aria-hidden="true"></span>CSS nativo. API estable. v<?= escape($version) ?></p>
            <h1>CSS nativo y ligero.<br><span>Diseñado para la IA.</span></h1>
            <p class="site-hero-lead">dctcss es un framework CSS nativo para construir interfaces con HTML. Incluye componentes, layouts y una guía para trabajar con agentes de IA.</p>
            <div class="dct-cluster site-hero-actions">
              <a class="dct-button dct-is-primary dct-is-medium dct-is-fullwidth-mobile" href="downloads/<?= escape($minified['filename']) ?>" download>Descargar CSS</a>
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
                  <div class="dct-card-actions dct-cluster"><a class="dct-button dct-is-primary" href="recipes.html#dashboard">Ver receta del panel <span aria-hidden="true">→</span></a></div>
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

      <div class="site-proof" aria-label="Datos principales de dctcss">
        <div class="dct-container site-proof-grid">
          <p><strong>0</strong><span>JavaScript distribuido</span></p>
          <p><strong>0</strong><span>Dependencias runtime</span></p>
          <p><strong>dct-</strong><span>Prefijo sin colisiones</span></p>
          <p><strong><?= escape($version) ?></strong><span>Release estable</span></p>
        </div>
      </div>

      <section class="dct-container site-section site-benefits" aria-labelledby="benefits-title">
        <div class="site-section-heading">
          <p class="site-kicker">Por qué dctcss</p>
          <h2 id="benefits-title">Menos fricción entre la idea<br>y una interfaz fiable.</h2>
          <p>Una base pequeña, explícita y moderna para que tu equipo componga más y depure menos.</p>
        </div>
        <div class="site-benefit-grid">
          <article class="dct-card site-feature-card"><div class="dct-card-body"><span class="site-feature-number">01</span><h3 class="dct-card-title">CSS moderno nativo</h3><p class="dct-card-content">Custom properties, Grid, Flexbox y capas de cascada. Sin preprocesadores que oculten lo que llega al navegador.</p></div></article>
          <article class="dct-card site-feature-card site-feature-card-accent"><div class="dct-card-body"><span class="site-feature-number">02</span><h3 class="dct-card-title">Cero JavaScript distribuido</h3><p class="dct-card-content">dctcss se ocupa de presentar. Tu aplicación mantiene el control total de sus datos y comportamiento.</p></div></article>
          <article class="dct-card site-feature-card"><div class="dct-card-body"><span class="site-feature-number">03</span><h3 class="dct-card-title">Accesible por diseño</h3><p class="dct-card-content">Foco visible, semántica, zoom, modo oscuro y contenido estrecho forman parte del contrato.</p></div></article>
          <article class="dct-card site-feature-card"><div class="dct-card-body"><span class="site-feature-number">04</span><h3 class="dct-card-title">API sin colisiones</h3><p class="dct-card-content">Clases, tokens y animaciones propios empiezan por <code>dct-</code>: sabes qué pertenece al framework.</p></div></article>
          <article class="dct-card site-feature-card site-feature-wide"><div class="dct-card-body"><span class="site-feature-number">05</span><h3 class="dct-card-title">Del encargo al HTML comprobable</h3><p class="dct-card-content">¿Cómo pedirías a tu agente una ayuda desplegable que funcione con teclado? Sigue el encargo, prueba el resultado y comprueba su estructura.</p><a href="fundamentals.html#trabajar-con-ia">Seguir el ejemplo con IA <span aria-hidden="true">→</span></a></div></article>
        </div>
      </section>

      <section class="dct-container dct-grid dct-p-6 site-section site-example" id="example" aria-labelledby="example-title">
        <div class="dct-stack">
          <p class="dct-meta">02 / Ejemplo inmediato</p>
          <h2 class="dct-title" id="example-title">Vista y código comparten contrato.</h2>
          <p class="dct-text-muted">La tarjeta requiere un body; el enlace navega y el botón representa una acción.</p>
        </div>
        <?= html_example(
            'landing-card',
            <<<'HTML'
<article class="dct-card dct-is-elevated">
  <div class="dct-card-body">
    <p class="dct-card-subtitle">Proyecto activo</p>
    <h2 class="dct-card-title">Atlas</h2>
    <p class="dct-card-content">Panel de seguimiento para operaciones.</p>
    <div class="dct-cluster dct-card-actions">
      <a class="dct-button dct-is-primary" href="/proyectos/atlas">Abrir proyecto</a>
      <button class="dct-button" type="button">Archivar</button>
    </div>
  </div>
</article>
HTML,
            'HTML del ejemplo de tarjeta',
            <<<'HTML'
<article class="dct-card dct-is-elevated site-preview-card">
  <div class="dct-card-body">
    <p class="dct-card-subtitle">Proyecto activo</p>
    <h3 class="dct-card-title">Atlas</h3>
    <p class="dct-card-content">Panel de seguimiento para operaciones.</p>
    <div class="dct-cluster dct-card-actions">
      <a class="dct-button dct-is-primary" href="#example">Abrir proyecto</a>
      <button class="dct-button" type="button">Archivar</button>
    </div>
  </div>
</article>
HTML
        ) ?>
      </section>

      <section class="dct-container dct-grid dct-p-6 site-section site-downloads" id="downloads" aria-labelledby="downloads-title">
        <div class="dct-stack">
          <p class="dct-meta">03 / Descargas</p>
          <h2 class="dct-title" id="downloads-title">La release actual, en dos variantes.</h2>
          <p>Descarga la versión actual desde esta web o consulta las <a href="<?= escape(GITHUB_REPOSITORY) ?>/releases">versiones anteriores en GitHub</a>.</p>
          <p class="dct-text-muted">Estas URLs son <em>latest</em>: siempre contienen la versión publicada más reciente y no son enlaces históricos inmutables. Consulta <a href="release.json"><code>release.json</code></a> para descubrir la versión y <a href="changelog.html">Cambios</a> antes de actualizar.</p>
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
              <div class="dct-card-actions dct-cluster"><a class="dct-button dct-is-primary" href="downloads/<?= escape($artifact['filename']) ?>" download>Descargar <?= escape($artifact['filename']) ?></a></div>
            </div>
          </article>
<?php endforeach; ?>
        </div>
      </section>

      <section class="dct-container dct-grid dct-p-6 site-section site-install" id="install" aria-labelledby="install-title">
        <div class="dct-stack">
          <p class="dct-meta">04 / Inicio rápido</p>
          <h2 class="dct-title" id="install-title">Un enlace absoluto y nada más.</h2>
          <p class="dct-text-muted">Carga una sola variante. Personaliza después mediante tokens, sin editar el archivo generado.</p>
          <p>La skill de dctcss funciona con OpenCode, Codex, Claude Code y otros agentes compatibles con Agent Skills. <a href="fundamentals.html#trabajar-con-ia">Copia las instrucciones para integrar dctcss con tu agente.</a></p>
        </div>
        <?= code_example('install', 'HTML', $install, 'HTML de instalación') ?>
        <aside class="dct-message dct-is-warning" aria-labelledby="browser-support-title">
          <div class="dct-message-body">
            <h3 class="dct-message-title" id="browser-support-title">Comprueba tus navegadores objetivo</h3>
            <p class="dct-message-content">dctcss <?= escape($version) ?> requiere como mínimo Chrome o Chromium <?= escape($build['browserMinimums']['Chrome y Chromium']) ?>, Firefox <?= escape($build['browserMinimums']['Firefox']) ?> y Safari <?= escape($build['browserMinimums']['Safari']) ?>. Edge y Android WebView necesitan un motor Chromium equivalente; en iOS se aplica el mínimo de Safari/WebKit. Internet Explorer y versiones anteriores no son compatibles. El framework no transpila CSS ni incluye polyfills.</p>
          </div>
        </aside>
      </section>

      <section class="dct-container dct-grid dct-p-6 site-section site-catalog" aria-labelledby="paths-title">
        <div class="dct-stack">
          <p class="dct-meta">05 / Catálogo técnico</p>
          <h2 class="dct-title" id="paths-title">Entra por la tarea que necesitas resolver.</h2>
        </div>
        <div class="dct-grid">
          <a class="dct-button" href="fundamentals.html">01 / Fundamentos / Tokens, capas y temas</a>
          <a class="dct-button" href="layout.html">02 / Layout / Container, grid y sidebar</a>
          <a class="dct-button" href="components.html">03 / Componentes / Partes, variantes y estados</a>
          <a class="dct-button" href="forms.html">04 / Formularios / Controles y validación</a>
          <a class="dct-button" href="navigation.html">05 / Navegación / Rutas, menús y navbar</a>
          <a class="dct-button" href="utilities.html">06 / Utilidades / Inventario responsive</a>
          <a class="dct-button" href="application.html">07 / Aplicación / Pantalla completa</a>
          <a class="dct-button" href="recipes.html">08 / Recetas / Composiciones completas</a>
          <a class="dct-button" href="changelog.html">09 / Cambios / Releases y actualización</a>
        </div>
      </section>

      <section class="dct-container dct-grid dct-p-6 site-section" aria-labelledby="sizes-title">
        <div class="dct-stack">
          <p class="dct-meta">06 / Comparativa factual</p>
          <h2 class="dct-title" id="sizes-title">Cuánto CSS llega realmente.</h2>
          <p class="dct-text-muted">dctcss y las referencias con cifras en bytes son mediciones reproducibles de archivos completos. Los valores con <code>~</code> son rangos orientativos aportados y no se usan para calcular porcentajes. Gzip usa nivel 9 sin nombre ni fecha en la cabecera.</p>
        </div>
        <div class="dct-table-wrap" tabindex="0" role="region" aria-label="Comparativa de tamaños CSS minificados">
          <table class="dct-table dct-is-striped dct-is-hoverable">
            <caption class="dct-table-caption">CSS completo, sin JavaScript, iconos ni fuentes</caption>
            <thead><tr><th scope="col">Framework</th><th scope="col">Versión</th><th class="dct-is-numeric" scope="col">CSS minificado</th><th class="dct-is-numeric" scope="col">Con gzip</th><th scope="col">Medición</th></tr></thead>
            <tbody>
              <tr class="dct-is-selected"><th scope="row">dctcss</th><td><?= escape($version) ?></td><td class="dct-is-numeric"><?= $minified['bytes'] ?> bytes</td><td class="dct-is-numeric"><?= $minified['gzipBytes'] ?> bytes</td><td>Exacta, build actual</td></tr>
<?php foreach ($build['benchmarks'] as $benchmark): ?>
              <tr><th scope="row"><?= escape($benchmark['name']) ?></th><td><?= escape($benchmark['version']) ?></td><td class="dct-is-numeric"><?= escape(ctype_digit($benchmark['bytes']) ? $benchmark['bytes'] . ' bytes' : $benchmark['bytes']) ?></td><td class="dct-is-numeric"><?= escape(ctype_digit($benchmark['gzipBytes']) ? $benchmark['gzipBytes'] . ' bytes' : $benchmark['gzipBytes']) ?></td><td><?= ctype_digit($benchmark['bytes']) ? 'Exacta, referencia fijada' : ($benchmark['bytes'] === 'variable' ? 'Depende del proyecto' : 'Rango orientativo') ?></td></tr>
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
          <p class="site-kicker">Empieza sin fricción</p>
          <h2 id="cta-title">Tu próxima interfaz<br>necesita menos capas.</h2>
          <p>Descarga el CSS, enlázalo y empieza a componer con una API que no te obliga a adivinar.</p>
          <div class="dct-cluster">
            <a class="dct-button dct-is-primary dct-is-medium" href="downloads/<?= escape($minified['filename']) ?>" download>Descargar <?= escape($minified['filename']) ?></a>
            <a class="dct-button dct-is-medium" href="fundamentals.html">Leer la documentación</a>
          </div>
        </div>
      </section>
<?php
};
return $page;
