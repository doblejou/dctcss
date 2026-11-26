<?php

declare(strict_types=1);

$page = array (
  'slug' => 'layout',
  'title' => 'Layout',
  'description' => 'Layouts de dctcss {{VERSION}}: container, stack, cluster, grid y composición responsive con sidebar y main.',
  'index' => 
  array (
    'container' => 'Container',
    'stack' => 'Stack',
    'cluster' => 'Cluster',
    'grid' => 'Grid',
    'sidebar-layout' => 'Sidebar layout',
    'composicion' => 'Composición y límites',
  ),
  'indexLabel' => 'Índice de layouts',
);
$page['content'] = static function (string $version): void {
?>
        <header class="site-doc-title dct-stack">
          <h1 class="dct-display">Layout</h1>
          <p>Limita el ancho del contenido, apila elementos o distribúyelos en columnas con una rejilla o una barra lateral.</p>
        </header>

        <section class="site-reference" id="container" aria-labelledby="container-title">
          <h2 id="container-title"><code>.dct-container</code></h2>
          <p>Usa container para centrar una región de página, limitar el ancho legible y conservar gutter lateral. Puede aplicarse a <code>header</code>, <code>main</code>, <code>footer</code> o a un <code>div</code> estructural; no aporta semántica por sí mismo.</p>
          <?= html_example(
'container-html',
<<<'HTML'
<main class="dct-container">
  <article class="dct-card">
    <div class="dct-card-body">
      <h1 class="dct-card-title">Informe trimestral</h1>
      <p class="dct-card-content">El contenido permanece centrado y conserva espacio en ambos bordes.</p>
    </div>
  </article>
</main>
HTML,
'html de .dct-container',
<<<'HTML'
<div class="dct-container">
                <article class="dct-card">
                  <div class="dct-card-body">
                    <h3 class="dct-card-title">Informe trimestral</h3>
                    <p class="dct-card-content">El contenido permanece centrado y conserva espacio en ambos bordes.</p>
                  </div>
                </article>
              </div>
HTML
) ?>
          <h3>Personalización</h3>
          <p><code>--dct-container-max</code> controla el ancho del contenido, con <code>72rem</code> por defecto. <code>--dct-container-gutter</code> controla el padding lateral y usa por defecto un <code>clamp()</code> entre <code>--dct-space-4</code> y <code>--dct-space-6</code>.</p>
          <?= code_example(
'container-css', 'CSS',
<<<'CODE'
.app-shell {
  --dct-container-max: 80rem;
  --dct-container-gutter: clamp(1rem, 5vw, 3rem);
}
CODE,
'css de .dct-container'
) ?>
          <div class="site-note"><strong>Límite</strong>Container no crea filas, columnas ni separación vertical. Combínalo con stack, grid o sidebar layout dentro de la región; evita anidar containers solo para obtener más padding.</div>
        </section>

        <section class="site-reference" id="stack" aria-labelledby="stack-title">
          <h2 id="stack-title"><code>.dct-stack</code></h2>
          <p>Usa stack cuando todos los hijos directos deban seguir un flujo vertical con una separación uniforme. Es apropiado para secciones, formularios, cuerpos de panel y grupos de bloques; no sustituye la jerarquía de encabezados.</p>
          <?= html_example(
'stack-html',
<<<'HTML'
<section class="settings-flow dct-stack" aria-labelledby="settings-title">
  <h2 id="settings-title">Preferencias de cuenta</h2>
  <p class="dct-text-muted">Revisa la información antes de guardar.</p>
  <div class="dct-field">
    <label class="dct-label" for="display-name">Nombre visible</label>
    <input class="dct-input" id="display-name" name="display-name" type="text" value="Ada Lovelace">
  </div>
  <button class="dct-button dct-is-primary" type="button">Guardar</button>
</section>
HTML,
'html de .dct-stack',
<<<'HTML'
<section class="dct-stack" style="--dct-stack-gap: var(--dct-space-5);">
                <h3>Preferencias de cuenta</h3>
                <p class="dct-text-muted">Revisa la información antes de guardar.</p>
                <div class="dct-field">
                  <label class="dct-label" for="layout-name-preview">Nombre visible</label>
                  <input class="dct-input" id="layout-name-preview" name="display-name" type="text" value="Ada Lovelace">
                </div>
                <button class="dct-button dct-is-primary" type="button">Guardar</button>
              </section>
HTML
) ?>
          <h3>Personalización</h3>
          <p><code>--dct-stack-gap</code> acepta cualquier longitud CSS válida para <code>gap</code> y vale <code>var(--dct-space-4)</code> por defecto. Ajústalo en una clase de contexto, no inventes modificadores como <code>.dct-stack-large</code>.</p>
          <?= code_example(
'stack-css', 'CSS',
<<<'CODE'
.settings-flow {
  --dct-stack-gap: var(--dct-space-5);
}
CODE,
'css de .dct-stack'
) ?>
          <div class="site-note"><strong>Accesibilidad y límite</strong>El orden visual coincide con el orden del DOM porque stack no reordena contenido. Si necesitas una fila que envuelva, usa cluster; si necesitas columnas equivalentes, usa grid.</div>
        </section>

        <section class="site-reference" id="cluster" aria-labelledby="cluster-title">
          <h2 id="cluster-title"><code>.dct-cluster</code></h2>
          <p>Usa cluster para una fila flexible de elementos relacionados que pueda envolver sin desbordar: acciones, etiquetas, filtros o metadatos. Sus hijos conservan su ancho intrínseco y pasan a otra línea cuando falta espacio.</p>
          <?= html_example(
'cluster-html',
<<<'HTML'
<div class="selection-toolbar dct-cluster">
  <strong>3 proyectos seleccionados</strong>
  <div class="dct-cluster">
    <button class="dct-button dct-is-primary" type="button">Mover</button>
    <button class="dct-button dct-is-danger" type="button">Eliminar</button>
  </div>
</div>
HTML,
'html de .dct-cluster',
<<<'HTML'
<div class="dct-cluster" style="--dct-cluster-gap: var(--dct-space-2); --dct-cluster-justify: space-between;">
                <strong>3 proyectos seleccionados</strong>
                <div class="dct-cluster" style="--dct-cluster-gap: var(--dct-space-2);">
                  <button class="dct-button dct-is-primary" type="button">Mover</button>
                  <button class="dct-button dct-is-danger" type="button">Eliminar</button>
                </div>
              </div>
HTML
) ?>
          <h3>Personalización</h3>
          <p><code>--dct-cluster-gap</code> vale <code>var(--dct-space-3)</code>, <code>--dct-cluster-align</code> vale <code>center</code> y <code>--dct-cluster-justify</code> vale <code>flex-start</code>. Los dos últimos deben recibir valores válidos de <code>align-items</code> y <code>justify-content</code>.</p>
          <?= code_example(
'cluster-css', 'CSS',
<<<'CODE'
.selection-toolbar {
  --dct-cluster-gap: var(--dct-space-2);
  --dct-cluster-align: center;
  --dct-cluster-justify: space-between;
}
CODE,
'css de .dct-cluster'
) ?>
          <div class="site-note"><strong>Accesibilidad y límite</strong>Cluster no convierte elementos en controles ni asigna nombres. Usa enlaces para navegar y botones con <code>type</code> para acciones. No fuerces una sola línea: la envoltura es parte de su comportamiento responsive.</div>
        </section>

        <section class="site-reference" id="grid" aria-labelledby="grid-title">
          <h2 id="grid-title"><code>.dct-grid</code></h2>
          <p>Usa grid para repetir columnas equivalentes que se autoajustan según el espacio disponible, sin elegir breakpoints. Es apropiado para tarjetas, paneles de resumen o colecciones; el ancho mínimo expresa cuándo una columna deja de ser útil.</p>
          <?= html_example(
'grid-html',
<<<'HTML'
<div class="summary-grid dct-grid">
  <article class="dct-card">
    <div class="dct-card-body">
      <p class="dct-card-subtitle">Activos</p>
      <h2 class="dct-card-title">12 proyectos</h2>
    </div>
  </article>
  <article class="dct-card">
    <div class="dct-card-body">
      <p class="dct-card-subtitle">Pendientes</p>
      <h2 class="dct-card-title">4 revisiones</h2>
    </div>
  </article>
  <article class="dct-card">
    <div class="dct-card-body">
      <p class="dct-card-subtitle">Equipo</p>
      <h2 class="dct-card-title">8 personas</h2>
    </div>
  </article>
</div>
HTML,
'html de .dct-grid',
<<<'HTML'
<div class="dct-grid" style="--dct-grid-min: 12rem; --dct-grid-gap: var(--dct-space-4);">
                <article class="dct-card"><div class="dct-card-body"><p class="dct-card-subtitle">Activos</p><h3 class="dct-card-title">12 proyectos</h3></div></article>
                <article class="dct-card"><div class="dct-card-body"><p class="dct-card-subtitle">Pendientes</p><h3 class="dct-card-title">4 revisiones</h3></div></article>
                <article class="dct-card"><div class="dct-card-body"><p class="dct-card-subtitle">Equipo</p><h3 class="dct-card-title">8 personas</h3></div></article>
              </div>
HTML
) ?>
          <h3>Personalización</h3>
          <p><code>--dct-grid-min</code> vale <code>16rem</code> por defecto y define el mínimo deseado de cada columna. <code>--dct-grid-gap</code> vale <code>var(--dct-space-5)</code>. La fórmula interna protege contenedores más estrechos que el mínimo para evitar overflow.</p>
          <?= code_example(
'grid-css', 'CSS',
<<<'CODE'
.summary-grid {
  --dct-grid-min: 12rem;
  --dct-grid-gap: var(--dct-space-4);
}
CODE,
'css de .dct-grid'
) ?>
          <div class="site-note"><strong>Límite</strong>Grid no garantiza un número fijo de columnas. Si el contenido exige exactamente una estructura editorial concreta, escribe un layout propio; no uses un mínimo irreal para forzar columnas que desborden.</div>
        </section>

        <section class="site-reference" id="sidebar-layout" aria-labelledby="sidebar-layout-title">
          <h2 id="sidebar-layout-title"><code>.dct-sidebar-layout</code>, <code>.dct-sidebar</code> y <code>.dct-main</code></h2>
          <p>Usa esta composición para una región lateral seguida del contenido principal. En móvil se muestra como una sola columna; desde <code>64rem</code>, la sidebar ocupa entre <code>12rem</code> y <code>18rem</code> y main recibe el espacio restante.</p>
          <?= html_example(
'sidebar-html',
<<<'HTML'
<div class="dct-sidebar-layout">
  <aside class="dct-sidebar">
    <nav class="dct-menu" aria-label="Ajustes">
      <ul class="dct-menu-list">
        <li><a class="dct-menu-item" href="/ajustes/perfil" aria-current="page">Perfil</a></li>
        <li><a class="dct-menu-item" href="/ajustes/seguridad">Seguridad</a></li>
        <li><a class="dct-menu-item" href="/ajustes/notificaciones">Notificaciones</a></li>
      </ul>
    </nav>
  </aside>
  <section class="dct-main" aria-labelledby="profile-title">
    <h2 id="profile-title">Perfil</h2>
    <p>Actualiza los datos visibles para tu equipo.</p>
  </section>
</div>
HTML,
'html de .dct-sidebar-layout, .dct-sidebar y .dct-main',
<<<'HTML'
<div class="dct-sidebar-layout">
                <aside class="dct-sidebar">
                  <nav class="dct-menu" aria-label="Ajustes de muestra">
                    <ul class="dct-menu-list">
                      <li><a class="dct-menu-item" href="#sidebar-layout" aria-current="page">Perfil</a></li>
                      <li><a class="dct-menu-item" href="#sidebar-layout">Seguridad</a></li>
                      <li><a class="dct-menu-item" href="#sidebar-layout">Notificaciones</a></li>
                    </ul>
                  </nav>
                </aside>
                <section class="dct-main dct-stack" aria-labelledby="preview-profile-title">
                  <h3 id="preview-profile-title">Perfil</h3>
                  <p>Actualiza los datos visibles para tu equipo.</p>
                  <div class="dct-field">
                    <label class="dct-label" for="sidebar-email-preview">Correo electrónico</label>
                    <input class="dct-input" id="sidebar-email-preview" name="email" type="email" value="ada@example.com">
                  </div>
                </section>
              </div>
HTML
) ?>
          <h3>Contrato de las partes</h3>
          <table class="site-api-table">
            <thead><tr><th scope="col">Clase</th><th scope="col">Responsabilidad</th><th scope="col">Requisito</th></tr></thead>
            <tbody>
              <tr><td><code>.dct-sidebar-layout</code></td><td>Grid responsive y separación con <code>var(--dct-space-5)</code>.</td><td>Contiene sidebar y main en ese orden cuando el orden de lectura sea lateral y después principal.</td></tr>
              <tr><td><code>.dct-sidebar</code></td><td>Parte lateral con protección frente a overflow.</td><td>Debe ser descendiente de <code>.dct-sidebar-layout</code>; usa <code>aside</code> solo si el contenido es complementario.</td></tr>
              <tr><td><code>.dct-main</code></td><td>Contenido flexible con <code>min-inline-size: 0</code>.</td><td>Debe ser descendiente de <code>.dct-sidebar-layout</code>; la clase puede aplicarse a <code>main</code>, <code>section</code> o <code>div</code> según la semántica.</td></tr>
            </tbody>
          </table>
          <p>Esta primitiva no publica custom properties específicas. El gap procede de <code>--dct-space-5</code>; el breakpoint y los anchos de la sidebar son parte del comportamiento estable, no opciones configurables.</p>
          <div class="site-note"><strong>Accesibilidad y límite</strong>Las clases no crean landmarks. Elige <code>aside</code>, <code>nav</code>, <code>main</code> o <code>div</code> por su significado, proporciona nombre a cada navegación y conserva un orden DOM útil en móvil. La sidebar no es colapsable y dctcss no distribuye un toggle.</div>
        </section>

        <section class="site-reference" id="composicion" aria-labelledby="composicion-title">
          <h2 id="composicion-title">Composición y límites</h2>
          <p>Las primitivas se pueden anidar cuando cada una conserva una responsabilidad clara. Un shell habitual combina container para el ancho, sidebar layout para las regiones, stack para el flujo vertical, cluster para acciones y grid para colecciones.</p>
          <p>Las clases tipográficas no aportan márgenes externos. Combínalas con stack o con una composición <code>app-*</code>; usa <code>--dct-content-measure</code> cuando una columna de lectura propia de la aplicación necesite limitarse.</p>
          <?= html_example(
'composition',
<<<'HTML'
<main class="dct-container">
  <section class="dct-stack" aria-labelledby="projects-title">
    <div class="project-heading dct-cluster">
      <h1 id="projects-title">Proyectos</h1>
      <a class="dct-button dct-is-primary" href="/proyectos/nuevo">Crear proyecto</a>
    </div>
    <div class="project-grid dct-grid">
      <article class="dct-card">
        <div class="dct-card-body">
          <h2 class="dct-card-title">Atlas</h2>
          <p class="dct-card-content">Operaciones</p>
        </div>
      </article>
      <article class="dct-card">
        <div class="dct-card-body">
          <h2 class="dct-card-title">Norte</h2>
          <p class="dct-card-content">Analítica</p>
        </div>
      </article>
    </div>
  </section>
</main>
HTML,
'html de Composición y límites',
<<<'HTML'
<div class="dct-container">
                <div class="dct-stack">
                  <div class="dct-cluster" style="--dct-cluster-justify: space-between;">
                    <h3>Proyectos</h3>
                    <a class="dct-button dct-is-primary" href="#composicion">Crear proyecto</a>
                  </div>
                  <div class="dct-grid" style="--dct-grid-min: 11rem;">
                    <article class="dct-card"><div class="dct-card-body"><h4 class="dct-card-title">Atlas</h4><p class="dct-card-content">Operaciones</p></div></article>
                    <article class="dct-card"><div class="dct-card-body"><h4 class="dct-card-title">Norte</h4><p class="dct-card-content">Analítica</p></div></article>
                  </div>
                </div>
              </div>
HTML
) ?>
          <?= code_example(
'composition-css', 'CSS',
<<<'CODE'
.project-heading {
  --dct-cluster-justify: space-between;
}

.project-grid {
  --dct-grid-min: 18rem;
  --dct-grid-gap: var(--dct-space-6);
}
CODE,
'css de Composición y límites'
) ?>
          <ul>
            <li>No uses clases de layout para simular semántica o cambiar el orden de lectura.</li>
            <li>No inventes variantes responsive: grid se autoajusta y sidebar layout incorpora su único breakpoint.</li>
            <li>No esperes que una primitiva gestione sticky, colapso, scroll o estados interactivos.</li>
            <li>Prueba palabras largas, controles anchos, zoom al 200 % y los anchos anterior y posterior a <code>64rem</code>.</li>
            <li>Si una composición necesita reglas diferentes, crea una clase de aplicación y reutiliza los tokens públicos.</li>
          </ul>
        </section>
<?php
};
return $page;
