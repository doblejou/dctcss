<?php

declare(strict_types=1);

$page = array (
  'slug' => 'utilities',
  'title' => 'Utilidades',
  'description' => 'Inventario completo de utilidades dctcss {{VERSION}}: display, texto, espaciado, flex, ancho, overflow, accesibilidad y responsive.',
  'index' => 
  array (
    'display' => 'Display',
    'texto' => 'Texto',
    'gap' => 'Gap',
    'responsive' => 'Responsive',
    'espaciado' => 'Padding y margen',
    'flex' => 'Flex',
    'tamano' => 'Ancho',
    'overflow' => 'Overflow',
    'screen-reader' => 'Screen reader',
    'limites' => 'Límites',
  ),
  'indexLabel' => 'Índice de utilidades',
);
$page['content'] = static function (string $version): void {
?>
<header class="site-doc-title dct-stack">
            <h1 class="dct-display">Utilidades</h1>
            <p>Encuentra la clase para ajustar espaciado, alineación o visibilidad en distintos tamaños de pantalla.</p>
          </header>

          <section class="site-reference" id="display" aria-labelledby="display-title">
            <h2 id="display-title">Display</h2>
            <table class="site-api-table">
              <thead><tr><th scope="col">Clase</th><th scope="col">Resultado</th></tr></thead>
              <tbody>
                <tr><td><code>.dct-block</code></td><td><code>display: block</code></td></tr>
                <tr><td><code>.dct-inline</code></td><td><code>display: inline</code></td></tr>
                <tr><td><code>.dct-flex</code></td><td><code>display: flex</code></td></tr>
                <tr><td><code>.dct-inline-flex</code></td><td><code>display: inline-flex</code></td></tr>
                <tr><td><code>.dct-hidden</code></td><td><code>display: none</code></td></tr>
              </tbody>
            </table>
            <?= html_example(
'display',
<<<'HTML'
<div class="dct-flex dct-gap-3 dct-wrap">
  <span class="dct-inline">Elemento inline</span>
  <span class="dct-inline-flex dct-items-center">Elemento inline-flex</span>
  <span class="dct-block">Elemento block</span>
  <span class="dct-hidden">Este contenido no se muestra</span>
</div>
HTML,
'html de Display'
) ?>
          </section>

          <section class="site-reference" id="texto" aria-labelledby="texto-title">
            <h2 id="texto-title">Texto</h2>
            <table class="site-api-table">
              <thead><tr><th scope="col">Clase</th><th scope="col">Uso</th></tr></thead>
              <tbody>
              <tr><td><code>.dct-text-start</code></td><td>Alinea al inicio lógico.</td></tr>
                <tr><td><code>.dct-text-center</code></td><td>Centra el texto.</td></tr>
              <tr><td><code>.dct-text-end</code></td><td>Alinea al final lógico.</td></tr>
                <tr><td><code>.dct-text-muted</code></td><td>Usa el token de texto secundario.</td></tr>
              </tbody>
            </table>
            <?= html_example(
'text',
<<<'HTML'
<p class="dct-text-start">Inicio lógico</p>
<p class="dct-text-center">Texto centrado</p>
<p class="dct-text-end">Final lógico</p>
<p class="dct-text-muted">Información secundaria</p>
HTML,
'html de Texto'
) ?>
            <p>Inicio y final son direcciones lógicas, no aliases de izquierda y derecha. El texto muted no debe contener información con contraste crítico.</p>
          </section>

          <section class="site-reference" id="gap" aria-labelledby="gap-title">
            <h2 id="gap-title">Gap</h2>
            <p>Aplica gap a un contenedor Grid o Flex existente. La escala comparte los tokens globales de espaciado.</p>
            <table class="site-api-table">
              <thead><tr><th scope="col">Clase</th><th scope="col">Valor</th></tr></thead>
              <tbody>
                <tr><td><code>.dct-gap-1</code></td><td><code>var(--dct-space-1)</code></td></tr>
                <tr><td><code>.dct-gap-2</code></td><td><code>var(--dct-space-2)</code></td></tr>
                <tr><td><code>.dct-gap-3</code></td><td><code>var(--dct-space-3)</code></td></tr>
                <tr><td><code>.dct-gap-4</code></td><td><code>var(--dct-space-4)</code></td></tr>
                <tr><td><code>.dct-gap-5</code></td><td><code>var(--dct-space-5)</code></td></tr>
                <tr><td><code>.dct-gap-6</code></td><td><code>var(--dct-space-6)</code></td></tr>
              </tbody>
            </table>
            <?= html_example(
'gap',
<<<'HTML'
<div class="dct-flex dct-gap-4 dct-wrap">
  <button class="dct-button" type="button">Cancelar</button>
  <button class="dct-button dct-is-primary" type="button">Guardar</button>
</div>
HTML,
'html de Gap'
) ?>
          </section>

          <section class="site-reference" id="responsive" aria-labelledby="responsive-title">
            <h2 id="responsive-title">Display responsive</h2>
            <p>Las variantes son mobile-first: empiezan a aplicar en el ancho mínimo indicado y continúan activas por encima. Solo existe la familia de utilidades display; <code>.dct-is-fullwidth-mobile</code> es un modificador específico de botón.</p>
            <table class="site-api-table">
              <thead><tr><th scope="col">Breakpoint</th><th scope="col">Desde</th><th scope="col">Clases publicadas</th></tr></thead>
              <tbody>
                <tr><td><code>sm</code></td><td><code>40rem</code></td><td><code>.dct-sm-block</code>, <code>.dct-sm-inline</code>, <code>.dct-sm-flex</code>, <code>.dct-sm-inline-flex</code>, <code>.dct-sm-hidden</code></td></tr>
                <tr><td><code>md</code></td><td><code>64rem</code></td><td><code>.dct-md-block</code>, <code>.dct-md-inline</code>, <code>.dct-md-flex</code>, <code>.dct-md-inline-flex</code>, <code>.dct-md-hidden</code></td></tr>
                <tr><td><code>lg</code></td><td><code>80rem</code></td><td><code>.dct-lg-block</code>, <code>.dct-lg-inline</code>, <code>.dct-lg-flex</code>, <code>.dct-lg-inline-flex</code>, <code>.dct-lg-hidden</code></td></tr>
              </tbody>
            </table>
            <h3>Mostrar desde un breakpoint</h3>
            <?= html_example(
'responsive',
<<<'HTML'
<p class="dct-hidden dct-sm-block">Visible desde 40rem.</p>
<span class="dct-hidden dct-md-inline">Visible inline desde 64rem.</span>
<div class="dct-hidden dct-lg-flex dct-gap-2">
  <span>Visible</span><span>como flex</span><span>desde 80rem.</span>
</div>
HTML,
'html de Display responsive',
<<<'HTML'
<p class="dct-hidden dct-sm-block">Visible desde 40rem con <code>.dct-hidden.dct-sm-block</code>.</p>
                <span class="dct-hidden dct-md-inline">Visible inline desde 64rem.</span>
                <div class="dct-hidden dct-lg-flex dct-gap-2"><span>Visible</span><span>como flex</span><span>desde 80rem.</span></div>
HTML
) ?>
            <h3>Ocultar desde un breakpoint</h3>
            <p>Combina el display base apropiado con <code>.dct-sm-hidden</code>, <code>.dct-md-hidden</code> o <code>.dct-lg-hidden</code>, por ejemplo <code>class="dct-flex dct-md-hidden"</code>.</p>
            <div class="site-note"><strong>No inventes variantes</strong>No existen <code>.dct-sm-gap-3</code>, <code>.dct-md-text-center</code>, <code>.dct-lg-p-4</code>, <code>.dct-sm-items-center</code> ni familias responsive de ancho, overflow o margen. Para botones anchos solo en móvil usa el modificador inventariado <code>.dct-is-fullwidth-mobile</code>.</div>
          </section>

          <section class="site-reference" id="espaciado" aria-labelledby="espaciado-title">
            <h2 id="espaciado-title">Padding y margen inferior</h2>
            <p>Padding actúa en los cuatro lados. Margen inferior usa la dirección lógica <code>margin-block-end</code>.</p>
            <table class="site-api-table">
              <thead><tr><th scope="col">Nivel</th><th scope="col">Padding</th><th scope="col">Margen inferior</th><th scope="col">Valor</th></tr></thead>
              <tbody>
                <tr><td>0</td><td><code>.dct-p-0</code></td><td><code>.dct-mb-0</code></td><td><code>0</code></td></tr>
                <tr><td>1</td><td><code>.dct-p-1</code></td><td><code>.dct-mb-1</code></td><td><code>var(--dct-space-1)</code></td></tr>
                <tr><td>2</td><td><code>.dct-p-2</code></td><td><code>.dct-mb-2</code></td><td><code>var(--dct-space-2)</code></td></tr>
                <tr><td>3</td><td><code>.dct-p-3</code></td><td><code>.dct-mb-3</code></td><td><code>var(--dct-space-3)</code></td></tr>
                <tr><td>4</td><td><code>.dct-p-4</code></td><td><code>.dct-mb-4</code></td><td><code>var(--dct-space-4)</code></td></tr>
                <tr><td>5</td><td><code>.dct-p-5</code></td><td><code>.dct-mb-5</code></td><td><code>var(--dct-space-5)</code></td></tr>
                <tr><td>6</td><td><code>.dct-p-6</code></td><td><code>.dct-mb-6</code></td><td><code>var(--dct-space-6)</code></td></tr>
              </tbody>
            </table>
            <?= html_example(
'spacing',
<<<'HTML'
<div class="dct-p-4">
  <h2 class="dct-mb-2">Resumen semanal</h2>
  <p class="dct-mb-0">Tres proyectos actualizaron su estado.</p>
</div>
HTML,
'html de Padding y margen inferior'
) ?>
          </section>

          <section class="site-reference" id="flex" aria-labelledby="flex-title">
            <h2 id="flex-title">Ajustes Flex</h2>
            <table class="site-api-table">
              <thead><tr><th scope="col">Clase</th><th scope="col">Resultado</th></tr></thead>
              <tbody>
                <tr><td><code>.dct-items-center</code></td><td><code>align-items: center</code></td></tr>
                <tr><td><code>.dct-justify-between</code></td><td><code>justify-content: space-between</code></td></tr>
                <tr><td><code>.dct-wrap</code></td><td><code>flex-wrap: wrap</code></td></tr>
                <tr><td><code>.dct-grow</code></td><td><code>flex-grow: 1</code></td></tr>
              </tbody>
            </table>
            <?= html_example(
'flex',
<<<'HTML'
<div class="dct-flex dct-items-center dct-justify-between dct-wrap dct-gap-3">
  <div class="dct-grow">
    <strong>Proyecto Atlas</strong>
    <span class="dct-block dct-text-muted">Actualizado hoy</span>
  </div>
  <button class="dct-button" type="button">Abrir</button>
</div>
HTML,
'html de Ajustes Flex'
) ?>
            <p>Estas clases no establecen <code>display</code>. Combínalas con <code>.dct-flex</code>, <code>.dct-inline-flex</code> o un componente que ya sea Flex.</p>
          </section>

          <section class="site-reference" id="tamano" aria-labelledby="tamano-title">
            <h2 id="tamano-title">Ancho</h2>
            <table class="site-api-table">
              <thead><tr><th scope="col">Clase</th><th scope="col">Resultado</th></tr></thead>
              <tbody>
                <tr><td><code>.dct-w-full</code></td><td><code>inline-size: 100%</code></td></tr>
                <tr><td><code>.dct-max-w-full</code></td><td><code>max-inline-size: 100%</code></td></tr>
              </tbody>
            </table>
            <?= html_example(
'width',
<<<'HTML'
<button class="dct-button dct-is-primary dct-w-full" type="button">
  Guardar cambios
</button>
HTML,
'html de Ancho'
) ?>
          </section>

          <section class="site-reference" id="overflow" aria-labelledby="overflow-title">
            <h2 id="overflow-title">Overflow</h2>
            <table class="site-api-table">
              <thead><tr><th scope="col">Clase</th><th scope="col">Resultado</th><th scope="col">Cuándo usarla</th></tr></thead>
              <tbody>
                <tr><td><code>.dct-overflow-auto</code></td><td><code>overflow: auto</code></td><td>Permite desplazamiento cuando el contenido excede el contenedor.</td></tr>
                <tr><td><code>.dct-overflow-hidden</code></td><td><code>overflow: hidden</code></td><td>Recorta desbordamiento deliberadamente.</td></tr>
              </tbody>
            </table>
            <?= html_example(
'overflow',
<<<'HTML'
<div class="dct-overflow-auto dct-max-w-full" tabindex="0"
  role="region" aria-label="Datos de actividad">
  <pre><code>Contenido ancho que conserva desplazamiento.</code></pre>
</div>
HTML,
'html de Overflow',
<<<'HTML'
<div class="dct-overflow-auto dct-max-w-full" tabindex="0" role="region" aria-label="Ejemplo de contenido ancho">
                  <pre><code>columna-01  columna-02  columna-03  columna-04  columna-05  columna-06  columna-07</code></pre>
                </div>
HTML
) ?>
            <p>Si una región desplazable contiene información relevante, hazla alcanzable por teclado y dale nombre accesible. No uses <code>.dct-overflow-hidden</code> para ocultar controles enfocados o contenido necesario.</p>
          </section>

          <section class="site-reference" id="screen-reader" aria-labelledby="screen-reader-title">
            <h2 id="screen-reader-title">Solo para screen reader</h2>
            <p><code>.dct-sr-only</code> oculta contenido visualmente y lo mantiene disponible para tecnologías de asistencia. Es útil para añadir contexto que un ícono o una cifra no comunican por sí solos.</p>
            <?= html_example(
'sr',
<<<'HTML'
<button class="dct-button" type="button">
  <svg class="dct-icon" viewBox="0 0 24 24" fill="none"
    stroke="currentColor" aria-hidden="true">
    <path d="M4 4h16M8 4v16h8V4M6 4l1 17h10l1-17"></path>
  </svg>
  <span class="dct-sr-only">Eliminar proyecto Atlas</span>
</button>
HTML,
'html de Solo para screen reader'
) ?>
            <p>No uses <code>.dct-hidden</code> como sustituto: <code>display: none</code> también retira el contenido del árbol de accesibilidad.</p>
          </section>

          <section class="site-reference" id="limites" aria-labelledby="limites-title">
            <h2 id="limites-title">Reglas y límites</h2>
            <ul>
              <li>Compone utilidades con componentes; no reemplaces semántica HTML ni atributos nativos.</li>
              <li>Solo display publica variantes <code>sm</code>, <code>md</code> y <code>lg</code>.</li>
              <li>No existen escalas de margen general, ancho fraccional, position, color, font-size ni z-index.</li>
              <li>No existen prefijos <code>xs</code> o <code>xl</code>, variantes max-width ni sintaxis como <code>dct-md:dct-flex</code>.</li>
              <li>Usa CSS propio cuando el inventario no cubra una necesidad; no atribuyas nombres inventados a dctcss.</li>
            </ul>
            <div class="site-note"><strong>API cerrada</strong>Clases como <code>.dct-p-7</code>, <code>.dct-mt-4</code>, <code>.dct-w-1/2</code>, <code>.dct-grid-cols-3</code>, <code>.dct-sm-text-center</code> y <code>.dct-lg-gap-6</code> no existen en <?= escape($version) ?>.</div>
          </section>
<?php
};
return $page;
