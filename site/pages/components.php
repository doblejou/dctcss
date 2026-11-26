<?php

declare(strict_types=1);

$page = array (
  'slug' => 'components',
  'title' => 'Componentes',
  'description' => 'Referencia de componentes dctcss {{VERSION}}: clases, variantes, accesibilidad, previews y HTML copiable.',
  'index' => 
  array (
    'content' => 'Content',
    'typography' => 'Tipografía',
    'button' => 'Button',
    'icon' => 'Icon',
    'message' => 'Message',
    'card' => 'Card',
    'table' => 'Table',
    'badge' => 'Badge',
    'progress' => 'Progress',
    'avatar' => 'Avatar',
    'dialog' => 'Dialog',
    'accordion' => 'Accordion',
    'disclosure' => 'Disclosure',
    'popover' => 'Popover',
  ),
  'indexLabel' => 'Índice de componentes',
);
$page['content'] = static function (string $version): void {
?>
        <header class="site-doc-title dct-stack">
          <h1 class="dct-display">Componentes</h1>
          <p>Consulta el marcado de cada componente y prueba sus variantes.</p>
        </header>

        <section class="site-reference" id="content" aria-labelledby="content-title">
          <h2 id="content-title">Content</h2>
          <p><code>.dct-content</code> da ritmo tipográfico a contenido editorial ya estructurado. Se admite en <code>article</code>, <code>section</code> o <code>div</code>; no reemplaza encabezados, listas, citas ni código semánticos.</p>
          <?= html_example(
'content',
<<<'HTML'
<article class="dct-content">
  <h2>Guía de despliegue</h2>
  <p>Publica primero en un entorno de pruebas y revisa el registro.</p>
  <blockquote>Un despliegue verificable es mejor que uno rápido.</blockquote>
  <ul>
    <li>Ejecuta las comprobaciones.</li>
    <li>Conserva una ruta de vuelta.</li>
  </ul>
  <pre><code>sh scripts/check.sh</code></pre>
</article>
HTML,
'html de Content',
<<<'HTML'
<article class="dct-content">
                 <h3>Guía de despliegue</h3>
                <p>Publica primero en un entorno de pruebas y revisa el registro.</p>
                <blockquote>Un despliegue verificable es mejor que uno rápido.</blockquote>
                <ul>
                  <li>Ejecuta las comprobaciones.</li>
                  <li>Conserva una ruta de vuelta.</li>
                </ul>
                <pre><code>sh scripts/check.sh</code></pre>
              </article>
HTML
) ?>
          <div class="site-note"><strong>Límite</strong>No apliques <code>.dct-content</code> a controles o layouts para obtener márgenes. La aplicación sigue siendo responsable de una jerarquía de encabezados correcta y de sanear HTML generado por usuarios.</div>
        </section>

        <section class="site-reference" id="typography" aria-labelledby="typography-title">
          <h2 id="typography-title">Tipografía semántica</h2>
          <p><code>.dct-display</code> destaca el encabezado único de una pantalla o pieza editorial; <code>.dct-title</code> nombra una página o sección; <code>.dct-subtitle</code> introduce ese título y <code>.dct-meta</code> presenta contexto breve. Las clases no añaden márgenes externos: usa <code>.dct-stack</code> para el ritmo y conserva una jerarquía de encabezados semántica.</p>
          <?= html_example(
              'typography',
              <<<'HTML'
<header class="dct-stack">
  <p class="dct-meta">Informe mensual / julio de 2026</p>
  <h1 class="dct-display">Operaciones</h1>
  <p class="dct-subtitle">Estado de entregas, capacidad y riesgos del equipo.</p>
</header>
<section class="dct-stack" aria-labelledby="typography-section-title">
  <h2 class="dct-title" id="typography-section-title">Entregas prioritarias</h2>
  <p>Contenido normal asociado a la sección.</p>
</section>
HTML,
              'html de tipografía semántica'
          ) ?>
          <div class="site-note"><strong>Decisión</strong>No elijas <code>h1</code>, <code>h2</code> o <code>p</code> por apariencia. El elemento expresa la estructura; la clase expresa el tratamiento visual.</div>
        </section>

        <section class="site-reference" id="button" aria-labelledby="button-title">
          <h2 id="button-title">Button</h2>
          <p>Usa <code>button.dct-button</code> para acciones y <code>a.dct-button</code> para navegación. La clase base es neutral.</p>
          <h3>Variantes, tamaños y estados</h3>
          <table class="site-api-table">
            <thead><tr><th scope="col">API</th><th scope="col">Uso</th></tr></thead>
            <tbody>
              <tr><td><code>.dct-is-primary</code>, <code>.dct-is-secondary</code>, <code>.dct-is-danger</code></td><td>Jerarquía visual o acción destructiva.</td></tr>
              <tr><td><code>.dct-is-small</code>, <code>.dct-is-medium</code>, <code>.dct-is-large</code></td><td>Tamaños que componen con una sola variante visual.</td></tr>
              <tr><td><code>.dct-is-fullwidth</code></td><td>Ocupa todo el ancho disponible.</td></tr>
              <tr><td><code>.dct-is-fullwidth-mobile</code></td><td>Ocupa todo el ancho por debajo de <code>40rem</code> y recupera ancho automático desde ese punto.</td></tr>
              <tr><td><code>.dct-is-icon-only</code></td><td>Botón cuadrado con un SVG visible y nombre accesible obligatorio.</td></tr>
              <tr><td><code>.dct-is-loading</code></td><td>Requiere <code>aria-busy="true"</code>; conserva texto accesible.</td></tr>
              <tr><td><code>.dct-is-hover-elevated</code></td><td>Sombra y subida de 1 px durante hover; no se aplica a disabled ni loading.</td></tr>
              <tr><td><code>disabled</code> / <code>aria-disabled="true"</code></td><td>Estado nativo de botón / estado accesible de enlace.</td></tr>
            </tbody>
          </table>
          <h3>Variantes visuales</h3>
          <p>Neutral es la apariencia base. Primary, secondary y danger cambian la jerarquía visual; estos cuatro ejemplos usan el mismo tamaño para facilitar la comparación.</p>
          <?= html_example(
              'button',
              <<<'HTML'
<div class="dct-cluster">
  <button class="dct-button" type="button">Neutral</button>
  <button class="dct-button dct-is-primary" type="button">Primary</button>
  <button class="dct-button dct-is-secondary" type="button">Secondary</button>
  <button class="dct-button dct-is-danger" type="button">Danger</button>
</div>
HTML,
              'html de variantes de Button'
          ) ?>
          <h3>Tamaños</h3>
          <p>Small, tamaño base sin modificador, medium y large. Se mantiene la variante neutral en todos los ejemplos.</p>
          <?= html_example(
              'button-sizes',
              <<<'HTML'
<div class="dct-cluster">
  <button class="dct-button dct-is-small" type="button">Small</button>
  <button class="dct-button" type="button">Base</button>
  <button class="dct-button dct-is-medium" type="button">Medium</button>
  <button class="dct-button dct-is-large" type="button">Large</button>
</div>
HTML,
              'html de tamaños de Button'
          ) ?>
          <h3>Anchura</h3>
          <p>Compara el ancho automático, fullwidth y fullwidth-mobile. El último ocupa todo el ancho por debajo de <code>40rem</code>; desde ese breakpoint recupera el ancho automático.</p>
          <?= html_example(
              'button-widths',
              <<<'HTML'
<div class="dct-stack">
  <div><button class="dct-button" type="button">Ancho automático</button></div>
  <button class="dct-button dct-is-fullwidth" type="button">Fullwidth: siempre a todo el ancho</button>
  <div><button class="dct-button dct-is-fullwidth-mobile" type="button">Fullwidth-mobile: ancho completo en móvil</button></div>
</div>
HTML,
              'html de anchuras de Button'
          ) ?>
          <h3>Estados: habilitado, disabled y loading</h3>
          <p>Cada fila muestra las cuatro variantes en el mismo estado. Los botones loading conservan su nombre accesible aunque el spinner oculte visualmente el texto.</p>
          <?= html_example(
              'button-states',
              <<<'HTML'
<div class="dct-stack">
  <div class="dct-stack">
    <p class="dct-meta">Habilitados</p>
    <div class="dct-cluster">
      <button class="dct-button" type="button">Neutral habilitado</button>
      <button class="dct-button dct-is-primary" type="button">Primary habilitado</button>
      <button class="dct-button dct-is-secondary" type="button">Secondary habilitado</button>
      <button class="dct-button dct-is-danger" type="button">Danger habilitado</button>
    </div>
  </div>
  <div class="dct-stack">
    <p class="dct-meta">Disabled</p>
    <div class="dct-cluster">
      <button class="dct-button" type="button" disabled>Neutral desactivado</button>
      <button class="dct-button dct-is-primary" type="button" disabled>Primary desactivado</button>
      <button class="dct-button dct-is-secondary" type="button" disabled>Secondary desactivado</button>
      <button class="dct-button dct-is-danger" type="button" disabled>Danger desactivado</button>
    </div>
  </div>
  <div class="dct-stack">
    <p class="dct-meta">Loading</p>
    <div class="dct-cluster">
      <button class="dct-button dct-is-loading" type="button" disabled aria-busy="true">Cargando, neutral</button>
      <button class="dct-button dct-is-primary dct-is-loading" type="button" disabled aria-busy="true">Cargando, primary</button>
      <button class="dct-button dct-is-secondary dct-is-loading" type="button" disabled aria-busy="true">Cargando, secondary</button>
      <button class="dct-button dct-is-danger dct-is-loading" type="button" disabled aria-busy="true">Cargando, danger</button>
    </div>
  </div>
</div>
HTML,
              'html de estados de Button'
          ) ?>
          <div class="site-note"><strong>Responsabilidad de la aplicación</strong><code>.dct-is-loading</code> solo presenta el estado. Bloquea activaciones con <code>disabled</code> y protege el handler. En un enlace no disponible, usa <code>aria-disabled="true"</code>, <code>tabindex="-1"</code> y evita la navegación desde la aplicación.</div>
          <h3>Enlaces con apariencia de botón</h3>
          <p>Usa enlaces para navegar. El ejemplo no disponible conserva su destino, se retira del recorrido de Tab y cancela la activación mediante código de aplicación.</p>
          <?= html_example(
              'button-links',
              <<<'HTML'
<div class="dct-cluster">
  <a class="dct-button" href="#button">Enlace neutral</a>
  <a class="dct-button dct-is-primary" href="#button">Enlace primary</a>
  <a class="dct-button dct-is-secondary" href="#button">Enlace secondary</a>
  <a class="dct-button dct-is-danger" href="#button">Enlace danger</a>
  <a class="dct-button" id="button-disabled-link" href="#button" aria-disabled="true" tabindex="-1">Enlace no disponible</a>
</div>
<script>
  document.getElementById("button-disabled-link").addEventListener("click", (event) => event.preventDefault());
</script>
HTML,
              'html y javascript de aplicación de enlaces Button'
          ) ?>
          <h3>Hover, foco visible y active</h3>
          <p>Prueba los botones habilitados de las muestras: pasa el cursor para ver hover, usa Tab para ver el foco y mantén pulsado el botón para ver active. Son estados reales de CSS, no clases que debas añadir al HTML.</p>
          <h3>Sombra al pasar el cursor</h3>
          <p>Añade <code>.dct-is-hover-elevated</code> a <code>.dct-button</code> para mostrar <code>--dct-shadow-sm</code> y subir visualmente el botón 1 px durante hover. Compone con variantes y tamaños sin modificar el layout y conserva el foco visible y el efecto de pulsación. Con movimiento reducido se mantiene la sombra, pero se omite la subida. En dispositivos sin hover y en botones disabled o loading no se aplica el efecto.</p>
          <?= html_example(
              'button-hover-elevated',
              <<<'HTML'
<div class="dct-stack">
  <div class="dct-cluster">
    <button class="dct-button dct-is-hover-elevated" type="button">Neutral con sombra</button>
    <button class="dct-button dct-is-primary dct-is-hover-elevated" type="button">Primary con sombra</button>
    <button class="dct-button dct-is-secondary dct-is-hover-elevated" type="button">Secondary con sombra</button>
    <button class="dct-button dct-is-danger dct-is-hover-elevated" type="button">Danger con sombra</button>
  </div>
  <div class="dct-cluster">
    <button class="dct-button dct-is-small dct-is-hover-elevated" type="button">Small con sombra</button>
    <button class="dct-button dct-is-hover-elevated" type="button">Base con sombra</button>
    <button class="dct-button dct-is-medium dct-is-hover-elevated" type="button">Medium con sombra</button>
    <button class="dct-button dct-is-large dct-is-hover-elevated" type="button">Large con sombra</button>
  </div>
  <div class="dct-cluster">
    <button class="dct-button dct-is-hover-elevated" type="button" disabled>Disabled: sin sombra</button>
    <span class="dct-cluster"><span class="dct-meta">Loading: sin sombra</span><button class="dct-button dct-is-primary dct-is-hover-elevated dct-is-loading" type="button" disabled aria-busy="true">Guardando</button></span>
  </div>
</div>
HTML,
              'html de botones con sombra hover'
          ) ?>
          <h3>Botón solo con icono</h3>
          <?= html_example(
              'icon-button',
              <<<'HTML'
<button class="dct-button dct-is-icon-only" type="button" aria-label="Buscar proyectos">
  <svg class="dct-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <circle cx="11" cy="11" r="7"></circle><path d="m20 20-4-4"></path>
  </svg>
</button>
HTML,
              'html de botón de icono accesible'
          ) ?>
          <p>El nombre describe la acción, no el dibujo. El SVG es decorativo, usa <code>aria-hidden="true"</code> y es el único contenido visible recomendado. Conserva tamaños, variantes, disabled y loading cuando la combinación tenga sentido.</p>
          <p><strong>Inválido:</strong> no uses un enlace para ejecutar una acción, no sustituyas <code>disabled</code> con una clase visual y no combines varias variantes visuales o varios tamaños en el mismo botón.</p>
        </section>

        <section class="site-reference" id="icon" aria-labelledby="icon-title">
          <h2 id="icon-title">Icon</h2>
          <p><code>.dct-icon</code> se aplica a SVG inline. Admite <code>.dct-is-small</code>, <code>.dct-is-medium</code> y <code>.dct-is-large</code>; el tamaño base es <code>1em</code>.</p>
          <?= html_example(
'icon',
<<<'HTML'
<svg class="dct-icon dct-is-small" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
  <path d="m5 12 4 4L19 6"></path>
</svg>
<svg class="dct-icon dct-is-medium" viewBox="0 0 24 24" fill="none" stroke="currentColor" role="img" aria-label="Información">
  <circle cx="12" cy="12" r="9"></circle>
  <path d="M12 11v6M12 7h.01"></path>
</svg>
<svg class="dct-icon dct-is-medium" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
  <path d="M12 2 2 12l10 10 10-10L12 2Z"></path>
</svg>
<button class="dct-button dct-is-primary" type="button">
  <svg class="dct-icon dct-is-large" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
    <path d="M5 12h14M13 6l6 6-6 6"></path>
  </svg>
  Continuar
</button>
HTML,
'html de Icon',
<<<'HTML'
<div class="dct-cluster">
                <svg class="dct-icon dct-is-small" viewbox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path d="m5 12 4 4L19 6"></path></svg>
                <svg class="dct-icon dct-is-medium" viewbox="0 0 24 24" fill="none" stroke="currentColor" role="img" aria-label="Información"><circle cx="12" cy="12" r="9"></circle><path d="M12 11v6M12 7h.01"></path></svg>
                <svg class="dct-icon dct-is-medium" viewbox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2 2 12l10 10 10-10L12 2Z"></path></svg>
                <button class="dct-button dct-is-primary" type="button"><svg class="dct-icon dct-is-large" viewbox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"></path></svg>Continuar</button>
              </div>
HTML
) ?>
          <p>Un ícono decorativo requiere <code>aria-hidden="true"</code>. Si comunica información por sí solo, usa <code>role="img"</code> y un nombre accesible. Declara <code>fill="currentColor"</code> en iconos de relleno o <code>fill="none" stroke="currentColor"</code> en iconos de trazos. dctcss no aplica <code>fill</code> ni <code>stroke</code> globalmente porque rompería la otra familia. No uses fuentes de íconos, imágenes externas ni sprites remotos.</p>
          <h3>Catálogo SVG copiable</h3>
          <p>Añadir, editar, eliminar, buscar, cerrar y menú comparten geometría de 24 unidades, <code>stroke-width="2"</code>, extremos redondos, uniones redondas y <code>currentColor</code>.</p>
          <?= code_example(
              'icon-catalog',
              'HTML',
              <<<'HTML'
<!-- Añadir --><svg class="dct-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14"></path></svg>
<!-- Editar --><svg class="dct-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4 20 4.5-1 10-10a2.1 2.1 0 0 0-3-3l-10 10L4 20Z"></path><path d="m14 7 3 3"></path></svg>
<!-- Eliminar --><svg class="dct-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16M9 7V4h6v3m3 0-1 13H7L6 7m4 4v5m4-5v5"></path></svg>
<!-- Buscar --><svg class="dct-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-4-4"></path></svg>
<!-- Cerrar --><svg class="dct-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"></path></svg>
<!-- Menú --><svg class="dct-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"></path></svg>
HTML,
              'catálogo de SVG inline'
          ) ?>
        </section>

        <section class="site-reference" id="message" aria-labelledby="message-title">
          <h2 id="message-title">Message</h2>
          <p><code>.dct-message</code> presenta información contextual. <code>.dct-message-body</code> es obligatorio; <code>.dct-message-icon</code>, <code>.dct-message-title</code>, <code>.dct-message-content</code> y <code>.dct-message-actions</code> son partes opcionales en sus ancestros indicados.</p>
          <p>Modificadores válidos: <code>.dct-is-info</code>, <code>.dct-is-success</code>, <code>.dct-is-warning</code> y <code>.dct-is-danger</code>.</p>
          <?= html_example(
'message',
<<<'HTML'
<aside class="dct-message dct-is-info" aria-labelledby="plan-info-title">
  <span class="dct-message-icon" aria-hidden="true">
    <svg class="dct-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
      <circle cx="12" cy="12" r="9"></circle>
      <path d="M12 11v6M12 7h.01"></path>
    </svg>
  </span>
  <div class="dct-message-body">
    <h2 class="dct-message-title" id="plan-info-title">Límite del plan</h2>
    <p class="dct-message-content">Tu plan permite hasta cinco proyectos activos.</p>
    <div class="dct-message-actions">
      <a class="dct-button dct-is-small dct-is-secondary" href="/planes">Ver planes</a>
    </div>
  </div>
</aside>

<aside class="dct-message dct-is-success" aria-labelledby="save-success-title">
  <div class="dct-message-body">
    <h2 class="dct-message-title" id="save-success-title">Cambios guardados</h2>
    <p class="dct-message-content">La configuración se actualizó correctamente.</p>
  </div>
</aside>

<aside class="dct-message dct-is-warning" aria-labelledby="review-warning-title">
  <div class="dct-message-body">
    <h2 class="dct-message-title" id="review-warning-title">Revisión pendiente</h2>
    <p class="dct-message-content">Comprueba los datos antes de publicar.</p>
  </div>
</aside>

<aside class="dct-message dct-is-danger" aria-labelledby="save-error-title">
  <div class="dct-message-body">
    <h2 class="dct-message-title" id="save-error-title">No se pudo guardar</h2>
    <p class="dct-message-content">Conserva tus cambios y vuelve a intentarlo.</p>
  </div>
</aside>
HTML,
'html de Message',
<<<'HTML'
<aside class="dct-message dct-is-info" aria-labelledby="plan-info-title">
                <span class="dct-message-icon" aria-hidden="true"><svg class="dct-icon" viewbox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="9"></circle><path d="M12 11v6M12 7h.01"></path></svg></span>
                <div class="dct-message-body">
                  <h3 class="dct-message-title" id="plan-info-title">Límite del plan</h3>
                  <p class="dct-message-content">Tu plan permite hasta cinco proyectos activos.</p>
                  <div class="dct-message-actions"><a class="dct-button dct-is-small dct-is-secondary" href="#message">Ver planes</a></div>
                </div>
              </aside>
              <div class="dct-mb-3"></div>
              <aside class="dct-message dct-is-success" aria-labelledby="save-success-preview-title">
                <div class="dct-message-body"><h3 class="dct-message-title" id="save-success-preview-title">Cambios guardados</h3><p class="dct-message-content">La configuración se actualizó correctamente.</p></div>
              </aside>
              <div class="dct-mb-3"></div>
               <aside class="dct-message dct-is-warning" aria-labelledby="review-warning-preview-title"><div class="dct-message-body"><h3 class="dct-message-title" id="review-warning-preview-title">Revisión pendiente</h3><p class="dct-message-content">Comprueba los datos antes de publicar.</p></div></aside>
              <div class="dct-mb-3"></div>
               <aside class="dct-message dct-is-danger" aria-labelledby="save-error-preview-title"><div class="dct-message-body"><h3 class="dct-message-title" id="save-error-preview-title">No se pudo guardar</h3><p class="dct-message-content">Conserva tus cambios y vuelve a intentarlo.</p></div></aside>
HTML
) ?>
          <h3>Mensajes dinámicos</h3>
          <p>Cuando la aplicación inserta una confirmación después de una acción, usa <code>role="status"</code>. Reserva <code>role="alert"</code> para un error dinámico urgente que requiera atención inmediata.</p>
          <?= code_example(
'message-dynamic', 'HTML',
<<<'CODE'
<div class="dct-message dct-is-success" role="status">
  <div class="dct-message-body">
    <p class="dct-message-title">Cambios guardados</p>
    <p class="dct-message-content">La configuración se actualizó correctamente.</p>
  </div>
</div>

<div class="dct-message dct-is-danger" role="alert">
  <div class="dct-message-body">
    <p class="dct-message-title">No se pudo guardar</p>
    <p class="dct-message-content">Conserva tus cambios y vuelve a intentarlo.</p>
  </div>
</div>
CODE,
'html de mensajes dinámicos'
) ?>
          <div class="site-note"><strong>Semántica y comportamiento</strong>Usa <code>section</code> o <code>aside</code> con <code>aria-labelledby</code> para mensajes estáticos. Los ejemplos con <code>role="status"</code> y <code>role="alert"</code> representan contenido que la aplicación inserta dinámicamente; no uses esos roles para mensajes ya presentes al cargar. La aplicación inserta, persiste y retira mensajes; no existe una API dismissible.</div>
          <p><strong>Inválido:</strong> omitir <code>.dct-message-body</code>, colocar las partes fuera de sus ancestros, usar <code>role="alert"</code> para información rutinaria o comunicar el estado solo mediante color.</p>
        </section>

        <section class="site-reference" id="card" aria-labelledby="card-title">
          <h2 id="card-title">Card</h2>
          <p><code>.dct-card</code> admite <code>article</code> o <code>div</code> y requiere un hijo <code>.dct-card-body</code>. Sus partes son <code>.dct-card-media</code>, <code>.dct-card-title</code>, <code>.dct-card-subtitle</code>, <code>.dct-card-content</code> y <code>.dct-card-actions</code>; las acciones requieren también <code>.dct-cluster</code>.</p>
          <?= html_example(
'card',
<<<'HTML'
<article class="dct-card dct-is-elevated">
  <figure class="dct-card-media">
    <svg viewBox="0 0 640 360" role="img" aria-label="Panel del proyecto Atlas">
      <rect width="640" height="360" fill="#10201d"></rect>
      <rect x="55" y="50" width="530" height="260" rx="18" fill="#78e2cd"></rect>
      <path d="M100 245l90-80 75 48 105-110 170 142" fill="none" stroke="#10201d" stroke-width="18"></path>
    </svg>
  </figure>
  <div class="dct-card-body">
    <p class="dct-card-subtitle">Proyecto activo</p>
    <h2 class="dct-card-title">Atlas</h2>
    <p class="dct-card-content">Panel de seguimiento para el equipo de operaciones.</p>
    <div class="dct-cluster dct-card-actions">
      <a class="dct-button dct-is-primary" href="/proyectos/atlas">Abrir proyecto</a>
      <button class="dct-button" type="button">Archivar</button>
    </div>
  </div>
</article>
HTML,
'html de Card',
<<<'HTML'
<article class="dct-card dct-is-elevated site-preview-card">
                <figure class="dct-card-media">
                  <svg viewbox="0 0 640 360" role="img" aria-label="Panel del proyecto Atlas"><rect width="640" height="360" fill="#10201d"></rect><rect x="55" y="50" width="530" height="260" rx="18" fill="#78e2cd"></rect><path d="M100 245l90-80 75 48 105-110 170 142" fill="none" stroke="#10201d" stroke-width="18"></path></svg>
                </figure>
                <div class="dct-card-body">
                  <p class="dct-card-subtitle">Proyecto activo</p>
                  <h3 class="dct-card-title">Atlas</h3>
                  <p class="dct-card-content">Panel de seguimiento para el equipo de operaciones.</p>
                  <div class="dct-cluster dct-card-actions"><a class="dct-button dct-is-primary" href="#card">Abrir proyecto</a><button class="dct-button" type="button">Archivar</button></div>
                </div>
              </article>
HTML
) ?>
          <p>El modificador propio de card es <code>.dct-is-elevated</code> y consume <code>--dct-shadow-md</code>. Una card normal permanece plana: la elevación comunica profundidad, no decoración universal. Personaliza cada instancia con <code>--dct-card-padding</code>, <code>--dct-card-gap</code> y <code>--dct-card-media-ratio</code>. Usa media solo cuando exista contenido real y proporciona texto alternativo apropiado.</p>
          <p><strong>Inválido:</strong> omitir el body, usar <code>.dct-card-actions</code> sin <code>.dct-cluster</code>, envolver toda la tarjeta en un enlace, simular interactividad con <code>onclick</code> sobre <code>article</code> o anidar enlaces.</p>
        </section>

        <section class="site-reference" id="table" aria-labelledby="table-title">
          <h2 id="table-title">Table, wrap y caption</h2>
          <p>Una tabla ancha usa <code>.dct-table-wrap</code> con <code>tabindex="0"</code>, <code>role="region"</code> y <code>aria-label</code> o <code>aria-labelledby</code>. <code>table.dct-table</code> requiere <code>caption.dct-table-caption</code>.</p>
          <p>El wrapper admite <code>.dct-is-elevated</code>. La tabla admite <code>.dct-is-striped</code>, <code>.dct-is-hoverable</code> y <code>.dct-is-compact</code>. Usa <code>.dct-is-numeric</code> en encabezado y celdas numéricas. En filas de <code>tbody</code> se admiten <code>.dct-is-selected</code>, <code>.dct-is-success</code>, <code>.dct-is-warning</code> y <code>.dct-is-danger</code>.</p>
          <?= html_example(
'table',
<<<'HTML'
<div class="dct-table-wrap dct-is-elevated" tabindex="0" role="region" aria-labelledby="orders-caption">
  <table class="dct-table dct-is-striped dct-is-hoverable dct-is-compact">
    <caption class="dct-table-caption" id="orders-caption">Pedidos recientes</caption>
    <thead>
      <tr>
        <th scope="col">Pedido</th>
        <th scope="col">Cliente</th>
        <th class="dct-is-numeric" scope="col">Importe</th>
        <th scope="col">Estado</th>
      </tr>
    </thead>
    <tbody>
      <tr class="dct-is-selected">
        <th scope="row">DCT-1041</th><td>Lin Chen</td><td class="dct-is-numeric">42,00 EUR</td><td>Seleccionado</td>
      </tr>
      <tr class="dct-is-success">
        <th scope="row">DCT-1042</th><td>Ada Lovelace</td><td class="dct-is-numeric">128,00 EUR</td><td>Completado</td>
      </tr>
      <tr class="dct-is-warning">
        <th scope="row">DCT-1043</th><td>Grace Hopper</td><td class="dct-is-numeric">84,50 EUR</td><td>Pendiente</td>
      </tr>
      <tr class="dct-is-danger">
        <th scope="row">DCT-1044</th><td>Alan Turing</td><td class="dct-is-numeric">65,25 EUR</td><td>Pago rechazado</td>
      </tr>
    </tbody>
  </table>
</div>
HTML,
'html de Table, wrap y caption'
) ?>
          <p>Los tokens específicos son <code>--dct-table-min</code> y <code>--dct-table-cell-padding</code>. El texto de cada fila debe repetir el estado expresado por color.</p>
          <p>Los encabezados y celdas numéricas evitan cortes arbitrarios heredados de <code>overflow-wrap: anywhere</code>, pero permiten saltos entre palabras. Para importes indivisibles usa CSS de aplicación <code>white-space: nowrap</code> solo en esos valores; conserva el ajuste de las descripciones. El mínimo predeterminado es 40rem: revisa la <a href="recipes.html#tabla-responsive">receta responsive</a> antes de reducirlo.</p>
          <div class="site-note"><strong>Responsabilidad de la aplicación</strong>dctcss no ordena, pagina ni selecciona filas. La aplicación debe usar botones reales y mantener <code>aria-sort</code> para ordenar. No cambies a <code>role="grid"</code> salvo que implementes el patrón interactivo completo.</div>
          <p><strong>Inválido:</strong> omitir caption, <code>scope</code> o nombre accesible del wrapper; aplicar <code>.dct-is-elevated</code> directamente a la tabla; aplicar estados fuera de <code>tbody tr</code>; usar <code>.dct-is-numeric</code> solo en algunas celdas de una columna.</p>
        </section>

        <section class="site-reference" id="badge" aria-labelledby="badge-title">
          <h2 id="badge-title">Badge</h2>
          <p>Usa <code>span.dct-badge</code> para una etiqueta breve, no interactiva. Admite <code>.dct-is-info</code>, <code>.dct-is-success</code>, <code>.dct-is-warning</code> y <code>.dct-is-danger</code>.</p>
          <p>Los badges permanecen en una línea incluso con ajuste de texto heredado. Una etiqueta larga necesita espacio o desplazamiento local; no se trunca. Para explicaciones extensas usa texto normal.</p>
          <?= html_example(
'badge',
<<<'HTML'
<span class="dct-badge">Borrador</span>
<span class="dct-badge dct-is-info">Informativo</span>
<span class="dct-badge dct-is-success">Completado</span>
<span class="dct-badge dct-is-warning">Pendiente</span>
<span class="dct-badge dct-is-danger">Bloqueado</span>
HTML,
'html de Badge',
<<<'HTML'
<div class="dct-cluster"><span class="dct-badge">Borrador</span><span class="dct-badge dct-is-info">Informativo</span><span class="dct-badge dct-is-success">Completado</span><span class="dct-badge dct-is-warning">Pendiente</span><span class="dct-badge dct-is-danger">Bloqueado</span></div>
HTML
) ?>
          <p>El texto debe comunicar el significado sin depender del color. No pongas <code>.dct-badge</code> en un enlace o botón para simular un control y no combines modificadores de estado.</p>
        </section>

        <section class="site-reference" id="progress" aria-labelledby="progress-title">
          <h2 id="progress-title">Progress</h2>
          <p>Aplica <code>.dct-progress</code> al elemento nativo <code>progress</code>. Admite <code>.dct-is-info</code>, <code>.dct-is-success</code>, <code>.dct-is-warning</code> y <code>.dct-is-danger</code>.</p>
          <?= html_example(
'progress',
<<<'HTML'
<label for="progress-base">Importación, 25 %</label>
<progress class="dct-progress" id="progress-base" max="100" value="25" aria-label="Importación, 25 %">25 %</progress>
<label for="progress-info">Análisis, 45 %</label>
<progress class="dct-progress dct-is-info" id="progress-info" max="100" value="45" aria-label="Análisis, 45 %">45 %</progress>
<label for="progress-success">Completado, 100 %</label>
<progress class="dct-progress dct-is-success" id="progress-success" max="100" value="100" aria-label="Completado, 100 %">100 %</progress>
<label for="progress-warning">Cuota utilizada, 75 %</label>
<progress class="dct-progress dct-is-warning" id="progress-warning" max="100" value="75" aria-label="Cuota utilizada, 75 %">75 %</progress>
<label for="progress-danger">Límite utilizado, 92 %</label>
<progress class="dct-progress dct-is-danger" id="progress-danger" max="100" value="92" aria-label="Límite utilizado, 92 %">92 %</progress>
HTML,
'html de Progress',
null,
'dct-stack'
) ?>
          <p>Cada progress requiere <code>aria-label</code> o <code>aria-labelledby</code>. La aplicación actualiza <code>value</code> y el texto visible. No uses un <code>div</code> con esta clase, no comuniques riesgo solo por color y no uses <code>.dct-is-loading</code>: no es un modificador de progress.</p>
        </section>

        <section class="site-reference" id="avatar" aria-labelledby="avatar-title">
          <h2 id="avatar-title">Avatar</h2>
          <p><code>.dct-avatar</code> se aplica a una imagen o a un <code>span</code> con iniciales. Admite <code>.dct-is-small</code>, <code>.dct-is-medium</code> y <code>.dct-is-large</code>.</p>
          <?= html_example(
'avatar',
<<<'HTML'
<span><span class="dct-avatar dct-is-small" aria-hidden="true">AL</span> Ada Lovelace</span>
<span><span class="dct-avatar" aria-hidden="true">GH</span> Grace Hopper</span>
<span><span class="dct-avatar dct-is-medium" aria-hidden="true">LC</span> Lin Chen</span>
<span><span class="dct-avatar dct-is-large" aria-hidden="true">AT</span> Alan Turing</span>
HTML,
'html de Avatar',
<<<'HTML'
<div class="dct-cluster"><span><span class="dct-avatar dct-is-small" aria-hidden="true">AL</span> Ada Lovelace</span><span><span class="dct-avatar" aria-hidden="true">GH</span> Grace Hopper</span><span><span class="dct-avatar dct-is-medium" aria-hidden="true">LC</span> Lin Chen</span><span><span class="dct-avatar dct-is-large" aria-hidden="true">AT</span> Alan Turing</span></div>
HTML
) ?>
          <p>Si el nombre aparece al lado, el avatar suele ser decorativo y usa <code>aria-hidden="true"</code> o <code>alt=""</code>. Si una imagen aporta información, escribe un <code>alt</code> equivalente. No uses el avatar como único nombre de una persona ni combines tamaños.</p>
        </section>

        <section class="site-reference" id="dialog" aria-labelledby="dialog-reference-title">
          <h2 id="dialog-reference-title">Dialog</h2>
          <p><code>dialog.dct-dialog</code> usa el diálogo nativo y <code>--dct-shadow-lg</code> para expresar profundidad modal. Sus partes son <code>.dct-dialog-header</code>, <code>.dct-dialog-title</code>, <code>.dct-dialog-body</code> y <code>.dct-dialog-actions</code>.</p>
          <?= html_example(
'dialog',
<<<'HTML'
<button class="dct-button dct-is-danger" type="button" id="delete-project">Eliminar proyecto</button>
<dialog class="dct-dialog" id="confirm-dialog" aria-labelledby="confirm-dialog-title">
  <header class="dct-dialog-header">
    <h2 class="dct-dialog-title" id="confirm-dialog-title">Confirmar eliminación</h2>
  </header>
  <div class="dct-dialog-body">
    <p>El proyecto Atlas y su historial se eliminarán de forma permanente.</p>
  </div>
  <form class="dct-dialog-actions" method="dialog">
    <button class="dct-button" type="submit" value="cancel">Cancelar</button>
    <button class="dct-button dct-is-danger" type="submit" value="confirm">Eliminar</button>
  </form>
</dialog>
<script>
  const dialog = document.getElementById("confirm-dialog");
  const dialogTrigger = document.getElementById("delete-project");
  dialogTrigger.addEventListener("click", () => dialog.showModal());
  dialog.addEventListener("close", () => {
    if (dialog.returnValue === "confirm") {
      // Ejecuta la acción destructiva desde la aplicación.
    }
    dialogTrigger.focus();
  });
</script>
HTML,
'html + javascript de aplicación de Dialog',
<<<'HTML'
<button class="dct-button dct-is-danger" type="button" data-site-dialog-open="confirm-dialog">Eliminar proyecto</button><dialog class="dct-dialog" id="confirm-dialog" aria-labelledby="confirm-dialog-title">
              <header class="dct-dialog-header"><h3 class="dct-dialog-title" id="confirm-dialog-title">Confirmar eliminación</h3></header>
              <div class="dct-dialog-body"><p>El proyecto Atlas y su historial se eliminarán de forma permanente.</p></div>
              <form class="dct-dialog-actions" method="dialog"><button class="dct-button" type="submit" value="cancel">Cancelar</button><button class="dct-button dct-is-danger" type="submit" value="confirm">Eliminar</button></form>
            </dialog>
HTML
) ?>
          <div class="site-note"><strong>Responsabilidad de la aplicación</strong>dctcss no abre, cierra ni procesa el diálogo. Usa <code>showModal()</code>, <code>close()</code> o un formulario <code>method="dialog"</code>; procesa <code>returnValue</code>, devuelve explícitamente el foco al disparador y conserva una etiqueta accesible mediante <code>aria-labelledby</code>.</div>
          <p><strong>Inválido:</strong> usar un <code>div</code> con <code>role="dialog"</code> sin implementar foco y teclado, abrirlo solo con una clase CSS, omitir el nombre accesible o ejecutar la acción irreversible antes de confirmar.</p>
        </section>

        <section class="site-reference" id="accordion" aria-labelledby="accordion-title">
          <h2 id="accordion-title">Accordion</h2>
          <p><code>.dct-accordion</code> agrupa varios disclosures nativos y controla únicamente su disposición. No crea exclusividad ni estado compartido.</p>
          <?= html_example(
'accordion',
<<<'HTML'
<div class="dct-accordion">
  <details class="dct-disclosure" open>
    <summary class="dct-disclosure-summary">Requisitos</summary>
    <div class="dct-disclosure-content">
      <p>Necesitas permisos de administración y una copia reciente.</p>
    </div>
  </details>
  <details class="dct-disclosure">
    <summary class="dct-disclosure-summary">Proceso</summary>
    <div class="dct-disclosure-content">
      <p>Ejecuta las comprobaciones antes de publicar.</p>
    </div>
  </details>
</div>
HTML,
'html de Accordion'
) ?>
          <p>Para permitir un solo panel abierto por grupo, usa el atributo nativo <code>name</code>. Consulta la receta de <a href="recipes.html#acordeones-exclusivos">acordeones exclusivos</a> y su comportamiento en navegadores anteriores.</p>
        </section>

        <section class="site-reference" id="disclosure" aria-labelledby="disclosure-title">
          <h2 id="disclosure-title">Disclosure</h2>
          <p>Usa <code>details.dct-disclosure</code> con un primer hijo <code>summary.dct-disclosure-summary</code> y contenido en <code>.dct-disclosure-content</code>. El atributo nativo <code>open</code> fija el estado inicial.</p>
          <?= html_example(
'disclosure',
<<<'HTML'
<details class="dct-disclosure">
    <summary class="dct-disclosure-summary">¿Qué incluye el plan?</summary>
  <div class="dct-disclosure-content">
    <p>Incluye cinco proyectos y soporte por correo.</p>
  </div>
</details>
HTML,
'html de Disclosure'
) ?>
          <p>El navegador gestiona teclado, foco y <code>open</code>. No añadas <code>role="button"</code>, <code>tabindex</code> ni <code>aria-expanded</code> redundantes al <code>summary</code>; no apliques las partes fuera de <code>.dct-disclosure</code>.</p>
        </section>

        <section class="site-reference" id="popover" aria-labelledby="popover-title">
          <h2 id="popover-title">Popover</h2>
          <p><code>.dct-popover</code> requiere el atributo nativo <code>popover</code> y un <code>id</code>. Un botón con <code>popovertarget</code> que coincida con ese id lo abre y cierra sin JavaScript. Su elevación pequeña consume <code>--dct-shadow-sm</code>.</p>
          <?= html_example(
'popover',
<<<'HTML'
<button class="dct-button dct-is-secondary" type="button" popovertarget="account-popover">
  Detalles de la cuenta
</button>
<div class="dct-popover" id="account-popover" popover>
  <strong>Cuenta profesional</strong>
  <p>Renovación: 14 de agosto.</p>
</div>
HTML,
'html de Popover',
<<<'HTML'
<button class="dct-button dct-is-secondary" type="button" popovertarget="account-popover">Detalles de la cuenta</button><div class="dct-popover" id="account-popover" popover><strong>Cuenta profesional</strong><p>Renovación: 14 de agosto.</p></div>
HTML
) ?>
          <div class="site-note"><strong>Responsabilidad de la aplicación</strong>El comportamiento básico es nativo. La aplicación gestiona datos, acciones adicionales y cualquier política de apertura programática. Para confirmaciones o tareas modales, usa <a href="#dialog">dialog</a>, no popover.</div>
          <p><strong>Inválido:</strong> omitir <code>popover</code>, usar un objetivo inexistente, presentar contenido modal o asumir que <code>.dct-popover</code> implementa un dropdown. <code>.dct-dropdown</code> no existe en <?= escape($version) ?>.</p>
        </section>
<?php
};
return $page;
