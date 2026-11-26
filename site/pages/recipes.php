<?php

declare(strict_types=1);

$page = array (
  'slug' => 'recipes',
  'title' => 'Recetas',
  'description' => 'Recetas completas de dctcss {{VERSION}}: shell, formulario, tabla responsive, lista compacta, diálogo y composición de contenido.',
  'index' => 
  array (
    'shell-aplicacion' => 'Shell de aplicación',
    'formulario-completo' => 'Formulario completo',
    'tabla-responsive' => 'Tabla responsive',
    'lista-compacta' => 'Lista compacta',
    'confirmacion-nativa' => 'Confirmación nativa',
    'composicion' => 'Grid, tarjetas y mensajes',
    'encabezado-pagina' => 'Encabezado de página',
    'dashboard' => 'Dashboard y KPIs',
    'empty-state' => 'Empty state',
    'actividad' => 'Lista de actividad',
    'ajustes-compactos' => 'Ajustes compactos',
    'panel-filtros' => 'Panel de filtros',
    'formulario-sticky' => 'Formulario móvil sticky',
    'icon-button' => 'Icon button',
    'resumen-contenedor' => 'Resumen por contenedor',
    'acordeones-exclusivos' => 'Acordeones exclusivos',
  ),
  'indexLabel' => 'Índice de recetas',
);
$page['content'] = static function (string $version): void {
?>
        <header class="site-doc-title dct-stack">
          <p class="dct-meta">Recetas / v<?= escape($version) ?></p>
          <h1 class="dct-display">Flujos completos, sin piezas implícitas.</h1>
          <p>Toda API dctcss usada aquí pertenece al inventario estable. Cuando una receta necesita CSS o JavaScript de aplicación lo identifica expresamente y evita el prefijo <code>dct-</code>. Cada snippet conserva la semántica y las relaciones accesibles necesarias para adaptarlo directamente.</p>
        </header>

        <section class="site-reference" id="shell-aplicacion" aria-labelledby="shell-aplicacion-title">
          <h2 id="shell-aplicacion-title">Shell de aplicación</h2>
          <p>Combina skip link, navbar, sidebar, menú local y contenido principal. El enlace de salto apunta a un <code>main</code> existente y enfocable; los estados actuales se expresan con <code>aria-current="page"</code>.</p>
          <p><strong>APIs usadas:</strong> <a href="navigation.html#skip-link">skip link</a>, <a href="navigation.html#navbar">navbar</a>, <a href="navigation.html#menu">menu</a> y <a href="layout.html#sidebar-layout">sidebar layout</a>.</p>
          <div class="site-note">
            <strong>Responsabilidad de la aplicación</strong>
            <span>La mejora progresiva parte con el toggle oculto y el menú visible; la aplicación sincroniza <code>hidden</code> y <code>aria-expanded</code> al iniciar, activar y cambiar de breakpoint.</span>
          </div>
          <?= html_example(
'shell',
<<<'HTML'
<a class="dct-skip-link" href="#main-content">Saltar al contenido</a>
<nav class="dct-navbar dct-is-inverse" aria-label="Navegación principal">
  <a class="dct-navbar-brand" href="/">Atlas</a>
  <button class="dct-button dct-navbar-toggle" id="main-menu-toggle" type="button" hidden aria-expanded="true" aria-controls="main-menu">Menú</button>
  <div class="dct-navbar-menu" id="main-menu">
    <a class="dct-navbar-item" href="/panel" aria-current="page">Panel</a>
    <a class="dct-navbar-item" href="/proyectos">Proyectos</a>
    <a class="dct-navbar-item" href="/configuracion">Configuración</a>
  </div>
</nav>

<div class="dct-container dct-sidebar-layout dct-p-4">
  <aside class="dct-sidebar">
    <nav class="dct-menu" aria-label="Secciones del panel">
      <ul class="dct-menu-list">
        <li><a class="dct-menu-item" href="/panel" aria-current="page">Resumen</a></li>
        <li><a class="dct-menu-item" href="/panel/actividad">Actividad</a></li>
        <li><a class="dct-menu-item" href="/panel/equipo">Equipo</a></li>
      </ul>
    </nav>
  </aside>

  <main class="dct-main dct-stack" id="main-content" tabindex="-1">
    <h1>Resumen del espacio</h1>
    <p class="dct-text-muted">Consulta el estado de los proyectos y las tareas recientes.</p>
    <div class="dct-cluster">
      <a class="dct-button dct-is-primary" href="/proyectos">Ver proyectos</a>
      <a class="dct-button dct-is-secondary" href="/configuracion">Configurar cuenta</a>
    </div>
  </main>
</div>
<script>
  const navbarToggle = document.querySelector("#main-menu-toggle");
  const navbarMenu = document.querySelector("#main-menu");
  const desktopNavbar = window.matchMedia("(min-width: 64rem)");

  function syncNavbar(desktop) {
    navbarToggle.hidden = desktop;
    navbarMenu.hidden = !desktop;
    navbarToggle.setAttribute("aria-expanded", String(desktop));
  }

  syncNavbar(desktopNavbar.matches);
  desktopNavbar.addEventListener("change", (event) => syncNavbar(event.matches));
  navbarToggle.addEventListener("click", () => {
    const expanded = navbarToggle.getAttribute("aria-expanded") === "true";
    navbarMenu.hidden = expanded;
    navbarToggle.setAttribute("aria-expanded", String(!expanded));
  });
</script>
HTML,
'html de Shell de aplicación',
<<<'HTML'
<div class="site-stage dct-p-4">
                <a class="dct-skip-link" href="#recipe-shell-main">Saltar al contenido</a>
                <nav class="dct-navbar dct-is-inverse" aria-label="Navegación principal de Atlas">
                  <a class="dct-navbar-brand" href="#shell-aplicacion">Atlas</a>
                  <button class="dct-button dct-navbar-toggle" type="button" hidden aria-expanded="true" aria-controls="recipe-main-menu" data-site-navbar-toggle>Menú</button>
                  <div class="dct-navbar-menu" id="recipe-main-menu">
                    <a class="dct-navbar-item" href="#shell-aplicacion" aria-current="page">Panel</a>
                    <a class="dct-navbar-item" href="#composicion">Proyectos</a>
                    <a class="dct-navbar-item" href="#formulario-completo">Configuración</a>
                  </div>
                </nav>
                <div class="dct-container dct-sidebar-layout dct-p-4">
                  <aside class="dct-sidebar">
                    <nav class="dct-menu" aria-label="Secciones del panel">
                      <ul class="dct-menu-list">
                        <li><a class="dct-menu-item" href="#shell-aplicacion" aria-current="page">Resumen</a></li>
                        <li><a class="dct-menu-item" href="#tabla-responsive">Actividad</a></li>
                        <li><a class="dct-menu-item" href="#composicion">Equipo</a></li>
                      </ul>
                    </nav>
                  </aside>
                  <div class="dct-main dct-stack" id="recipe-shell-main" tabindex="-1">
                    <h2>Resumen del espacio</h2>
                    <p class="dct-text-muted">Consulta el estado de los proyectos y las tareas recientes.</p>
                    <div class="dct-cluster">
                      <a class="dct-button dct-is-primary" href="#composicion">Ver proyectos</a>
                      <a class="dct-button dct-is-secondary" href="#formulario-completo">Configurar cuenta</a>
                    </div>
                  </div>
                </div>
              </div>
HTML
) ?>
        </section>

        <section class="site-reference" id="formulario-completo" aria-labelledby="formulario-completo-title">
          <h2 id="formulario-completo-title">Formulario completo</h2>
          <p>El formulario agrupa datos relacionados con <code>fieldset</code> y <code>legend</code>, asocia cada etiqueta mediante <code>for</code> e <code>id</code> y mantiene la ayuda vinculada con <code>aria-describedby</code>.</p>
          <p><strong>APIs usadas:</strong> <a href="forms.html#estructura">estructura de formulario</a>, <a href="forms.html#controles">controles</a>, <a href="forms.html#elecciones">choice</a> y <a href="components.html#button">botones</a>.</p>
          <div class="site-note">
            <strong>Responsabilidad de la aplicación</strong>
            <span>dctcss no valida datos, cambia estados ni envía el formulario. La aplicación debe procesar el envío y, si informa de errores, añadir el contrato completo de <code>.dct-is-error</code>, <code>aria-invalid</code> y <code>aria-describedby</code>.</span>
          </div>
<?php $recipeForm = recipe_code_block('Formulario completo', 'html'); ?>
          <?= html_example(
              'form',
              $recipeForm,
              'html de Formulario completo',
              inert_form_preview($recipeForm),
              'site-stage dct-p-4',
          ) ?>
        </section>

        <section class="site-reference" id="tabla-responsive" aria-labelledby="tabla-responsive-title">
          <h2 id="tabla-responsive-title">Tabla responsive</h2>
          <p>La tabla conserva su semántica y se desplaza dentro de una región con foco de teclado y nombre accesible. <code>caption</code> describe el conjunto, <code>scope</code> relaciona encabezados y el estado aparece como texto, no solo como color.</p>
          <p>Conserva el mínimo predeterminado de 40rem o valida otro con datos largos y texto al 200%. Limita <code>overflow-wrap: anywhere</code> a texto libre. La clase de aplicación <code>app-amount</code> mantiene signo, cifra y moneda juntos; las descripciones y estados sin badge pueden ajustarse. Los badges largos necesitan espacio o scroll local. En ancestros Grid/Flex permite la contracción con <code>min-inline-size: 0</code> o pistas <code>minmax(0, 1fr)</code>.</p>
          <p><strong>APIs usadas:</strong> <a href="components.html#table">tabla y wrapper responsive</a>.</p>
          <div class="site-note">
            <strong>Responsabilidad de la aplicación</strong>
            <span>dctcss no ordena, pagina ni selecciona filas. Si la aplicación ofrece ordenación debe usar botones reales en los encabezados y mantener <code>aria-sort</code>; no debe inventar comportamiento sobre esta receta.</span>
          </div>
<?php
$recipeTable = '<style>' . "\n" . recipe_code_block('Tabla responsive', 'css') . "\n" . '</style>' . "\n"
    . recipe_code_block('Tabla responsive', 'html');
?>
           <?= html_example(
'table',
$recipeTable,
'css y html de Tabla responsive',
null,
'site-stage dct-p-4'
) ?>
        </section>

        <section class="site-reference" id="lista-compacta" aria-labelledby="lista-compacta-title">
          <h2 id="lista-compacta-title">Lista compacta de pares</h2>
          <p>Un <code>dl</code> conserva la relación semántica entre cada etiqueta y su valor. Los selectores <code>app-*</code> pertenecen a la aplicación y usan tokens públicos para integrarse con el tema; esta receta todavía no es un componente dctcss.</p>
<?php
$recipeCompactList = '<style>' . "\n" . recipe_code_block('Lista compacta de pares', 'css') . "\n" . '</style>' . "\n"
    . recipe_code_block('Lista compacta de pares', 'html');
?>
          <?= html_example(
              'compact-list',
              $recipeCompactList,
              'html + css de aplicación de Lista compacta',
              '<div class="site-stage dct-p-4">' . $recipeCompactList . '</div>',
          ) ?>
          <div class="site-note"><strong>Límite deliberado</strong>Si esta composición se repite en varias aplicaciones podrá evaluarse como componente estable. No inventes una clase <code>.dct-*</code> para ella ni la conviertas en un componente financiero específico.</div>
        </section>

        <section class="site-reference" id="confirmacion-nativa" aria-labelledby="confirmacion-nativa-title">
          <h2 id="confirmacion-nativa-title">Confirmación nativa</h2>
          <p>Un <code>dialog</code> nativo contiene un título asociado y un formulario <code>method="dialog"</code>. Los botones devuelven un valor sin convertir la superficie visual en una API de JavaScript del framework.</p>
          <p><strong>APIs usadas:</strong> <a href="components.html#dialog">dialog nativo</a> y <a href="components.html#button">botones</a>.</p>
          <div class="site-note">
            <strong>Responsabilidad de la aplicación</strong>
            <span>El atributo <code>data-site-dialog-open</code> de la preview pertenece solo a esta documentación. En una aplicación, código propio llama a <code>showModal()</code>, interpreta <code>returnValue</code>, ejecuta la acción confirmada y conserva o devuelve el foco cuando corresponda. dctcss no distribuye ese JavaScript.</span>
          </div>
          <?= html_example(
'dialog',
recipe_code_block('Confirmación con diálogo', 'html'),
'html + javascript de aplicación de Confirmación nativa',
<<<'HTML'
<div class="site-stage dct-p-4">
                <button class="dct-button dct-is-danger" type="button" data-site-dialog-open="recipe-delete-dialog">Eliminar proyecto</button>
                <dialog class="dct-dialog" id="recipe-delete-dialog" aria-labelledby="recipe-delete-title">
                  <header class="dct-dialog-header">
                    <h3 class="dct-dialog-title" id="recipe-delete-title">Eliminar proyecto Atlas</h3>
                  </header>
                  <div class="dct-dialog-body">
                    <p>Se eliminarán sus tareas e informes. Esta acción no se puede deshacer.</p>
                  </div>
                  <form class="dct-dialog-actions" method="dialog">
                    <button class="dct-button" type="submit" value="cancel">Cancelar</button>
                    <button class="dct-button dct-is-danger" type="submit" value="confirm">Eliminar definitivamente</button>
                  </form>
                </dialog>
              </div>
HTML
) ?>
        </section>

        <section class="site-reference" id="composicion" aria-labelledby="composicion-title">
          <h2 id="composicion-title">Grid, tarjetas, mensajes y acciones</h2>
          <p>Esta vista combina una advertencia estática con tarjetas independientes dentro de un grid autoajustable. Los enlaces navegan y los botones ejecutan acciones; la tarjeta completa no simula ser interactiva.</p>
          <p><strong>APIs usadas:</strong> <a href="layout.html#grid">grid</a>, <a href="components.html#message">mensajes</a>, <a href="components.html#card">tarjetas</a>, <a href="components.html#badge">badges</a> y <a href="components.html#button">botones</a>.</p>
          <div class="site-note">
            <strong>Responsabilidad de la aplicación</strong>
            <span>La aplicación proporciona destinos reales, ejecuta las acciones de archivar y crea o retira mensajes dinámicos. dctcss no publica mensajes dismissible ni handlers para las acciones.</span>
          </div>
          <?= html_example(
'composition',
<<<'HTML'
<section class="dct-stack" aria-labelledby="projects-title">
  <h2 id="projects-title">Proyectos activos</h2>

  <aside class="dct-message dct-is-warning" aria-labelledby="projects-warning-title">
    <span class="dct-message-icon" aria-hidden="true">
      <svg class="dct-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path d="M12 3 2.5 20h19L12 3Z"></path>
        <path d="M12 9v5m0 3h.01"></path>
      </svg>
    </span>
    <div class="dct-message-body">
      <h3 class="dct-message-title" id="projects-warning-title">Una entrega necesita revisión</h3>
      <p class="dct-message-content">El proyecto Boreal tiene una fecha vencida y tareas pendientes.</p>
      <div class="dct-message-actions">
        <a class="dct-button dct-is-small dct-is-secondary" href="/proyectos/boreal/entregas">Revisar entrega</a>
      </div>
    </div>
  </aside>

  <div class="dct-grid">
    <article class="dct-card dct-is-elevated">
      <div class="dct-card-body">
        <div class="dct-cluster dct-justify-between">
          <p class="dct-card-subtitle">Operaciones</p>
          <span class="dct-badge dct-is-success">En plazo</span>
        </div>
        <h3 class="dct-card-title">Atlas</h3>
        <p class="dct-card-content">Panel de seguimiento para el equipo de operaciones.</p>
        <div class="dct-cluster dct-card-actions">
          <a class="dct-button dct-is-primary" href="/proyectos/atlas">Abrir proyecto</a>
          <button class="dct-button" type="button">Archivar</button>
        </div>
      </div>
    </article>

    <article class="dct-card">
      <div class="dct-card-body">
        <div class="dct-cluster dct-justify-between">
          <p class="dct-card-subtitle">Investigación</p>
          <span class="dct-badge dct-is-warning">Revisar</span>
        </div>
        <h3 class="dct-card-title">Boreal</h3>
        <p class="dct-card-content">Estudio de usuarios y validación de nuevos flujos.</p>
        <div class="dct-cluster dct-card-actions">
          <a class="dct-button dct-is-primary" href="/proyectos/boreal">Abrir proyecto</a>
          <button class="dct-button" type="button">Archivar</button>
        </div>
      </div>
    </article>
  </div>
</section>
HTML,
'html de Grid, tarjetas, mensajes y acciones',
<<<'HTML'
<div class="site-stage dct-stack dct-p-4">
                <h3>Proyectos activos</h3>
                <aside class="dct-message dct-is-warning" aria-labelledby="recipe-projects-warning-title">
                  <span class="dct-message-icon" aria-hidden="true">
                    <svg class="dct-icon" viewbox="0 0 24 24" fill="none" stroke="currentColor">
                      <path d="M12 3 2.5 20h19L12 3Z"></path>
                      <path d="M12 9v5m0 3h.01"></path>
                    </svg>
                  </span>
                  <div class="dct-message-body">
                    <h3 class="dct-message-title" id="recipe-projects-warning-title">Una entrega necesita revisión</h3>
                    <p class="dct-message-content">El proyecto Boreal tiene una fecha vencida y tareas pendientes.</p>
                    <div class="dct-message-actions">
                      <a class="dct-button dct-is-small dct-is-secondary" href="#composicion">Revisar entrega</a>
                    </div>
                  </div>
                </aside>
                <div class="dct-grid">
                  <article class="dct-card dct-is-elevated">
                    <div class="dct-card-body">
                      <div class="dct-cluster dct-justify-between">
                        <p class="dct-card-subtitle">Operaciones</p>
                        <span class="dct-badge dct-is-success">En plazo</span>
                      </div>
                      <h3 class="dct-card-title">Atlas</h3>
                      <p class="dct-card-content">Panel de seguimiento para el equipo de operaciones.</p>
                      <div class="dct-cluster dct-card-actions">
                        <a class="dct-button dct-is-primary" href="#shell-aplicacion">Abrir proyecto</a>
                        <button class="dct-button" type="button">Archivar</button>
                      </div>
                    </div>
                  </article>
                  <article class="dct-card">
                    <div class="dct-card-body">
                      <div class="dct-cluster dct-justify-between">
                        <p class="dct-card-subtitle">Investigación</p>
                        <span class="dct-badge dct-is-warning">Revisar</span>
                      </div>
                      <h3 class="dct-card-title">Boreal</h3>
                      <p class="dct-card-content">Estudio de usuarios y validación de nuevos flujos.</p>
                      <div class="dct-cluster dct-card-actions">
                        <a class="dct-button dct-is-primary" href="#tabla-responsive">Abrir proyecto</a>
                        <button class="dct-button" type="button">Archivar</button>
                      </div>
                    </div>
                  </article>
                </div>
              </div>
HTML
) ?>
        </section>

        <section class="site-reference" id="encabezado-pagina" aria-labelledby="encabezado-pagina-title">
          <h2 id="encabezado-pagina-title">Encabezado de página con acciones</h2>
          <p><code>.app-page-header</code> y <code>.app-toolbar</code> pertenecen a la aplicación, no a dctcss. El CSS completo permite que el título largo y las acciones envuelvan.</p>
<?php
$recipePageHeader = '<style>' . "\n" . recipe_code_block('Encabezado de página con acciones', 'css') . "\n" . '</style>' . "\n"
    . recipe_code_block('Encabezado de página con acciones', 'html');
$recipePageHeaderPreview = str_replace('href="/proyectos/nuevo"', 'href="#formulario-completo"', $recipePageHeader);
?>
          <?= html_example('page-header-recipe', $recipePageHeader, 'html + css de aplicación del encabezado', $recipePageHeaderPreview, 'site-stage dct-p-4') ?>
        </section>

        <section class="site-reference" id="dashboard" aria-labelledby="dashboard-title">
          <h2 id="dashboard-title">Dashboard básico con KPIs</h2>
          <p>Los KPI son artículos compuestos con card. <code>.app-kpi-*</code> no es API estable y debe copiarse junto a su CSS.</p>
<?php
$recipeDashboard = '<style>' . "\n" . recipe_code_block('Dashboard básico con KPIs', 'css') . "\n" . '</style>' . "\n"
    . recipe_code_block('Dashboard básico con KPIs', 'html');
?>
          <?= html_example('dashboard-recipe', $recipeDashboard, 'html + css de aplicación del dashboard', inert_html_preview($recipeDashboard), 'site-stage dct-p-4') ?>
        </section>

        <section class="site-reference" id="empty-state" aria-labelledby="empty-state-title">
          <h2 id="empty-state-title">Empty state con acción opcional</h2>
          <p>La composición usa <code>app-*</code>. Omite la acción si no existe un siguiente paso útil.</p>
<?php
$recipeEmptyState = '<style>' . "\n" . recipe_code_block('Empty state con acción opcional', 'css') . "\n" . '</style>' . "\n"
    . recipe_code_block('Empty state con acción opcional', 'html');
$recipeEmptyStatePreview = str_replace('href="/proyectos/nuevo"', 'href="#formulario-completo"', $recipeEmptyState);
?>
          <?= html_example('empty-recipe', $recipeEmptyState, 'html + css de aplicación del empty state', $recipeEmptyStatePreview, 'site-stage dct-p-4') ?>
        </section>

        <section class="site-reference" id="actividad" aria-labelledby="actividad-title">
          <h2 id="actividad-title">Lista de actividad</h2>
          <p>La lista cronológica y sus clases <code>app-activity*</code> pertenecen a la aplicación.</p>
<?php
$recipeActivity = '<style>' . "\n" . recipe_code_block('Lista de actividad', 'css') . "\n" . '</style>' . "\n"
    . recipe_code_block('Lista de actividad', 'html');
?>
          <?= html_example('activity-recipe', $recipeActivity, 'html + css de aplicación de la lista de actividad', null, 'site-stage dct-p-4') ?>
        </section>

        <section class="site-reference" id="ajustes-compactos" aria-labelledby="ajustes-compactos-title">
          <h2 id="ajustes-compactos-title">Pantalla de ajustes compacta</h2>
          <p>La densidad se aplica al subárbol completo y deja que los tokens heredables armonicen controles y paneles.</p>
<?php
$compactSettings = '<style>' . "\n" . recipe_code_block('Ajustes con densidad compacta', 'css') . "\n" . '</style>' . "\n"
    . recipe_code_block('Ajustes con densidad compacta', 'html');
?>
          <?= html_example('compact-settings-recipe', $compactSettings, 'html + css de aplicación de ajustes compactos', inert_form_preview($compactSettings), 'site-stage dct-p-4') ?>
        </section>

        <section class="site-reference" id="panel-filtros" aria-labelledby="panel-filtros-title">
          <h2 id="panel-filtros-title">Panel de filtros responsive</h2>
          <p>El panel y la toolbar usan clases de aplicación, mientras los campos y botones conservan la API estable. El grupo puede envolver sin imponer doce columnas.</p>
<?php
$filterPanel = '<style>' . "\n" . recipe_code_block('Panel de filtros responsive', 'css') . "\n" . '</style>' . "\n"
    . recipe_code_block('Panel de filtros responsive', 'html');
$filterPanelPreview = str_replace('href="/pedidos"', 'href="#panel-filtros"', inert_form_preview($filterPanel));
?>
          <?= html_example('filter-panel-recipe', $filterPanel, 'html + css de aplicación del panel de filtros', $filterPanelPreview, 'site-stage dct-p-4') ?>
        </section>

        <section class="site-reference" id="formulario-sticky" aria-labelledby="formulario-sticky-title">
          <h2 id="formulario-sticky-title">Formulario móvil con acciones sticky</h2>
          <p>La barra sticky es CSS de aplicación y solo debe mantenerse cuando no oculte ayuda, errores o controles con zoom y teclado virtual.</p>
<?php
$stickyForm = '<style>' . "\n" . recipe_code_block('Formulario móvil con acciones sticky', 'css') . "\n" . '</style>' . "\n"
    . recipe_code_block('Formulario móvil con acciones sticky', 'html');
$stickyFormPreview = str_replace('href="/perfil"', 'href="#formulario-sticky"', inert_form_preview($stickyForm));
?>
          <?= html_example('sticky-form-recipe', $stickyForm, 'html + css de aplicación del formulario móvil', $stickyFormPreview, 'site-stage dct-p-4') ?>
        </section>

        <section class="site-reference" id="icon-button" aria-labelledby="icon-button-title">
          <h2 id="icon-button-title">Botón de icono accesible</h2>
          <p>El nombre accesible pertenece al botón y describe la acción. El único SVG visible es decorativo.</p>
          <?= html_example('icon-button-recipe', recipe_code_block('Botón de icono accesible', 'html'), 'html del botón de icono accesible') ?>
        </section>

        <section class="site-reference" id="resumen-contenedor" aria-labelledby="resumen-contenedor-title">
          <h2 id="resumen-contenedor-title">Resumen por contenedor</h2>
          <p>El primer pedido ocupa un panel lateral y el segundo dispone del ancho restante de la sección. Ambos usan el mismo CSS de aplicación: a partir de 28rem de espacio propio, los datos pasan a dos columnas. Sin consultas de contenedor se mantiene una columna.</p>
<?php
$summary = '<style>' . "\n" . recipe_code_block('Resumen por contenedor', 'css') . "\n" . '</style>' . "\n"
    . recipe_code_block('Resumen por contenedor', 'html');
?>
          <?= html_example('container-summary', $summary, 'CSS y HTML del resumen por contenedor') ?>
        </section>

        <section class="site-reference" id="acordeones-exclusivos" aria-labelledby="acordeones-exclusivos-title">
          <h2 id="acordeones-exclusivos-title">Acordeones exclusivos por grupo</h2>
          <p>Abre Seguimiento: se cierra Preparación y Plazo permanece abierto. Cada grupo tiene su propio <code>name</code>. En navegadores sin soporte para la exclusividad, cada panel se puede abrir y cerrar de forma independiente.</p>
          <?= html_example('exclusive-details', recipe_code_block('Acordeones exclusivos por grupo', 'html'), 'HTML de los acordeones exclusivos') ?>
        </section>
<?php
};
return $page;
