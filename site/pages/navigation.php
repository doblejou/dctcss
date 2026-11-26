<?php

declare(strict_types=1);

$page = array (
  'slug' => 'navigation',
  'title' => 'Navegación',
  'description' => 'Referencia de navegación de dctcss {{VERSION}}: skip link, breadcrumb, tabs, paginación, navbar y menú.',
  'index' => 
  array (
    'skip-link' => 'Skip link',
    'breadcrumb' => 'Breadcrumb',
    'tabs' => 'Tabs de enlaces',
    'pagination' => 'Paginación',
    'navbar' => 'Navbar',
    'menu' => 'Menú',
    'contrato' => 'Contrato y errores',
  ),
  'indexLabel' => 'Índice de navegación',
);
$page['content'] = static function (string $version): void {
?>
<header class="site-doc-title dct-stack">
            <h1 class="dct-display">Navegación</h1>
            <p>Construye menús, rutas y paginadores con enlaces reales. Da un nombre a cada <code>nav</code> con <code>aria-label</code> o <code>aria-labelledby</code>.</p>
          </header>

          <section class="site-reference" id="skip-link" aria-labelledby="skip-link-title">
            <h2 id="skip-link-title">Skip link</h2>
            <p>Coloca <code>.dct-skip-link</code> como primer enlace útil del documento. Su <code>href</code> debe apuntar a un destino existente y enfocable situado antes del contenido principal.</p>
            <?= html_example(
'skip',
<<<'HTML'
<a class="dct-skip-link" href="#main-content">Saltar al contenido</a>
<header>
  <a href="/">Producto</a>
</header>
<main id="main-content" tabindex="-1">
  <h1>Panel de proyectos</h1>
</main>
HTML,
'html de Skip link',
<<<'HTML'
<p>La propia página incluye un skip link. Pulsa <kbd>Tab</kbd> desde el inicio para hacerlo visible.</p>
HTML
) ?>
            <div class="site-note"><strong>Requisito</strong>Un <code>href</code> vacío, un identificador inexistente o un destino que no puede recibir foco rompe el acceso directo por teclado.</div>
          </section>

          <section class="site-reference" id="breadcrumb" aria-labelledby="breadcrumb-title">
            <h2 id="breadcrumb-title">Breadcrumb</h2>
            <p>Representa la jerarquía desde una raíz hasta la página actual. Usa <code>nav.dct-breadcrumb</code>, <code>ol.dct-breadcrumb-list</code> y un <code>li.dct-breadcrumb-item</code> por nivel.</p>
            <?= html_example(
'breadcrumb',
<<<'HTML'
<nav class="dct-breadcrumb" aria-label="Ruta de navegación">
  <ol class="dct-breadcrumb-list">
    <li class="dct-breadcrumb-item"><a href="/">Inicio</a></li>
    <li class="dct-breadcrumb-item"><a href="/proyectos">Proyectos</a></li>
    <li class="dct-breadcrumb-item"><span aria-current="page">Atlas</span></li>
  </ol>
</nav>
HTML,
'html de Breadcrumb',
<<<'HTML'
<nav class="dct-breadcrumb" aria-label="Ruta de navegación">
                  <ol class="dct-breadcrumb-list">
                    <li class="dct-breadcrumb-item"><a href="#breadcrumb">Inicio</a></li>
                    <li class="dct-breadcrumb-item"><a href="#breadcrumb">Proyectos</a></li>
                    <li class="dct-breadcrumb-item"><span aria-current="page">Atlas</span></li>
                  </ol>
                </nav>
HTML
) ?>
            <h3>Requisitos y errores</h3>
            <p>El <code>nav</code> requiere un nombre accesible mediante <code>aria-label</code> o <code>aria-labelledby</code>. Conserva la lista ordenada y marca solo el último elemento con <code>aria-current="page"</code>. No escribas separadores <code>/</code> en el HTML: el componente los genera visualmente.</p>
          </section>

          <section class="site-reference" id="tabs" aria-labelledby="tabs-title">
            <h2 id="tabs-title">Tabs de enlaces</h2>
            <p>Usa tabs para navegar entre documentos o URLs. Las partes públicas son <code>nav.dct-tabs</code>, <code>ul.dct-tabs-list</code> y enlaces <code>.dct-tab</code>.</p>
            <?= html_example(
'tabs',
<<<'HTML'
<nav class="dct-tabs" aria-label="Secciones de cuenta">
  <ul class="dct-tabs-list">
    <li><a class="dct-tab" href="/cuenta/perfil" aria-current="page">Perfil</a></li>
    <li><a class="dct-tab" href="/cuenta/seguridad">Seguridad</a></li>
    <li><a class="dct-tab" href="/cuenta/notificaciones">Notificaciones</a></li>
  </ul>
</nav>
HTML,
'html de Tabs de enlaces',
<<<'HTML'
<nav class="dct-tabs" aria-label="Secciones de cuenta">
                  <ul class="dct-tabs-list">
                    <li><a class="dct-tab" href="#tabs" aria-current="page">Perfil</a></li>
                    <li><a class="dct-tab" href="#tabs">Seguridad</a></li>
                    <li><a class="dct-tab" href="#tabs">Notificaciones</a></li>
                  </ul>
                </nav>
HTML
) ?>
            <div class="site-note"><strong>Solo enlaces</strong>No uses <code>role="tablist"</code>, <code>role="tab"</code>, <code>aria-selected</code> ni paneles asociados. <code>.dct-tabs</code> no implementa tabs dinámicas; ese patrón y su gestión de foco pertenecen a la aplicación.</div>
          </section>

          <section class="site-reference" id="pagination" aria-labelledby="pagination-title">
            <h2 id="pagination-title">Paginación</h2>
            <p>Presenta enlaces a páginas reales dentro de <code>nav.dct-pagination</code> y <code>ul.dct-pagination-list</code>. Cada enlace o marcador no disponible usa <code>.dct-pagination-link</code>.</p>
            <?= html_example(
'pagination',
<<<'HTML'
<nav class="dct-pagination" aria-label="Paginación de resultados">
  <ul class="dct-pagination-list">
    <li><span class="dct-pagination-link" aria-disabled="true">Anterior</span></li>
    <li><a class="dct-pagination-link" href="?page=1" aria-current="page">1</a></li>
    <li><a class="dct-pagination-link" href="?page=2">2</a></li>
    <li><a class="dct-pagination-link" href="?page=3">3</a></li>
    <li><a class="dct-pagination-link" href="?page=2">Siguiente</a></li>
  </ul>
</nav>
HTML,
'html de Paginación',
<<<'HTML'
<nav class="dct-pagination" aria-label="Paginación de resultados">
                  <ul class="dct-pagination-list">
                    <li><span class="dct-pagination-link" aria-disabled="true">Anterior</span></li>
                    <li><a class="dct-pagination-link" href="#pagination" aria-current="page">1</a></li>
                    <li><a class="dct-pagination-link" href="#pagination">2</a></li>
                    <li><a class="dct-pagination-link" href="#pagination">3</a></li>
                    <li><a class="dct-pagination-link" href="#pagination">Siguiente</a></li>
                  </ul>
                </nav>
HTML
) ?>
            <h3>Requisitos y errores</h3>
            <p>El <code>nav</code> necesita <code>aria-label</code> o <code>aria-labelledby</code>; la página actual necesita <code>aria-current="page"</code>. Para un paso no disponible, prefiere un <code>span</code> con <code>aria-disabled="true"</code>: no dejes un enlace activable que no pueda navegar. La aplicación genera URLs, obtiene datos y decide el total de páginas.</p>
          </section>

          <section class="site-reference" id="navbar" aria-labelledby="navbar-title">
            <h2 id="navbar-title">Navbar</h2>
            <p>La navbar reúne marca, toggle, menú, enlaces y acciones. El menú empieza visible para seguir siendo útil sin JavaScript; esta preview usa el JavaScript privado del sitio para colapsarlo en pantallas estrechas.</p>
            <p>Sus partes públicas son <code>.dct-navbar-brand</code>, <code>.dct-navbar-toggle</code>, <code>.dct-navbar-menu</code>, <code>.dct-navbar-item</code> y <code>.dct-navbar-actions</code>, siempre dentro de <code>.dct-navbar</code>. Añade <code>.dct-is-inverse</code> al nav para una superficie oscura con contraste coordinado.</p>
            <?= html_example(
'navbar',
<<<'HTML'
<nav class="dct-navbar dct-is-inverse" aria-label="Navegación principal">
  <a class="dct-navbar-brand" href="/">Producto</a>
  <button class="dct-button dct-navbar-toggle" id="main-menu-toggle" type="button" hidden
    aria-expanded="true" aria-controls="main-menu">Menú</button>
  <div class="dct-navbar-menu" id="main-menu">
    <a class="dct-navbar-item" href="/dashboard" aria-current="page">Dashboard</a>
    <a class="dct-navbar-item" href="/proyectos">Proyectos</a>
    <a class="dct-navbar-item" href="/configuracion">Configuración</a>
    <div class="dct-navbar-actions">
      <a class="dct-button dct-is-primary dct-is-small" href="/proyectos/nuevo">Crear proyecto</a>
    </div>
  </div>
</nav>
HTML,
'html de Navbar',
<<<'HTML'
<nav class="dct-navbar dct-is-inverse" aria-label="Navegación principal de muestra">
                  <a class="dct-navbar-brand" href="#navbar">Producto</a>
                  <button class="dct-button dct-navbar-toggle" type="button" hidden aria-expanded="true" aria-controls="site-main-menu" data-site-navbar-toggle>Menú</button>
                  <div class="dct-navbar-menu" id="site-main-menu">
                    <a class="dct-navbar-item" href="#navbar" aria-current="page">Dashboard</a>
                    <a class="dct-navbar-item" href="#navbar">Proyectos</a>
                    <a class="dct-navbar-item" href="#navbar">Configuración</a>
                    <div class="dct-navbar-actions">
                      <a class="dct-button dct-is-primary dct-is-small" href="#navbar">Crear proyecto</a>
                    </div>
                  </div>
                </nav>
HTML
) ?>
            <?= code_example(
                'navbar-script',
                'JavaScript',
                <<<'CODE'
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
CODE,
                'javascript de aplicación de Navbar'
            ) ?>
            <h3>Responsabilidad del toggle</h3>
            <p>El botón <code>.dct-navbar-toggle</code> requiere también <code>.dct-button</code>, <code>type="button"</code>, <code>aria-expanded</code> y <code>aria-controls</code>. El valor de <code>aria-controls</code> debe coincidir con el <code>id</code> único de <code>.dct-navbar-menu</code>. Parte oculto y con el menú visible; al arrancar en una pantalla estrecha, la aplicación muestra el toggle, añade <code>hidden</code> al menú y cambia el estado a <code>false</code>. En cada activación posterior mantiene ambos valores sincronizados.</p>
            <div class="site-note"><strong>Error frecuente</strong>CSS no puede mantener este estado accesible. No cambies solo la visibilidad, no dejes <code>aria-expanded</code> desactualizado y no apuntes a un <code>id</code> inexistente.</div>
            <p>La variante inversa consume <code>--dct-navbar-inverse-background</code>, <code>--dct-navbar-inverse-text</code>, <code>--dct-navbar-inverse-text-muted</code> y <code>--dct-navbar-inverse-hover</code>. Personalízalos como grupo y comprueba contraste de hover, activo, botones y foco.</p>
            <h3>Acciones en carga</h3>
            <p>Loading conserva el nombre accesible y oculta visualmente el texto. En acciones neutras o secundarias, el spinner usa el texto de la navbar inversa; las variantes primary y danger conservan su propio color de contraste.</p>
            <?= html_example(
'navbar-loading',
<<<'HTML'
<nav class="dct-navbar dct-is-inverse" aria-label="Acciones en carga">
  <a class="dct-navbar-brand" href="#navbar">Producto</a>
  <div class="dct-navbar-menu">
    <div class="dct-navbar-actions">
      <button class="dct-button dct-is-loading" type="button" disabled aria-busy="true">Sincronizando</button>
      <button class="dct-button dct-is-secondary dct-is-loading" type="button" disabled aria-busy="true">Actualizando</button>
      <button class="dct-button dct-is-primary dct-is-loading" type="button" disabled aria-busy="true">Guardando</button>
      <button class="dct-button dct-is-danger dct-is-loading" type="button" disabled aria-busy="true">Eliminando</button>
    </div>
  </div>
</nav>
HTML,
'html de acciones en carga de Navbar'
) ?>
          </section>

          <section class="site-reference" id="menu" aria-labelledby="menu-title">
            <h2 id="menu-title">Menú</h2>
            <p>Usa <code>.dct-menu</code> para navegación lateral o local persistente. Su lista es <code>.dct-menu-list</code> y cada enlace es <code>.dct-menu-item</code>.</p>
            <?= html_example(
'menu',
<<<'HTML'
<nav class="dct-menu" aria-label="Configuración de cuenta">
  <ul class="dct-menu-list">
    <li><a class="dct-menu-item" href="/cuenta/perfil" aria-current="page">Perfil</a></li>
    <li><a class="dct-menu-item" href="/cuenta/equipo">Equipo</a></li>
    <li><a class="dct-menu-item" href="/cuenta/facturacion">Facturación</a></li>
  </ul>
</nav>
HTML,
'html de Menú',
<<<'HTML'
<nav class="dct-menu" aria-label="Configuración de cuenta">
                  <ul class="dct-menu-list">
                    <li><a class="dct-menu-item" href="#menu" aria-current="page">Perfil</a></li>
                    <li><a class="dct-menu-item" href="#menu">Equipo</a></li>
                    <li><a class="dct-menu-item" href="#menu">Facturación</a></li>
                  </ul>
                </nav>
HTML
) ?>
            <p>Da siempre un nombre accesible mediante <code>aria-label</code> o <code>aria-labelledby</code> al <code>nav</code>. Marca un solo enlace actual con <code>aria-current="page"</code>; no uses <code>.dct-menu-item</code> en botones ni conviertas esta API en un dropdown.</p>
          </section>

          <section class="site-reference" id="contrato" aria-labelledby="contrato-title">
            <h2 id="contrato-title">Contrato y límites</h2>
            <table class="site-api-table">
              <thead><tr><th scope="col">API</th><th scope="col">Partes y atributos obligatorios</th></tr></thead>
              <tbody>
                <tr><td><code>.dct-skip-link</code></td><td>Enlace a un destino existente y enfocable.</td></tr>
                <tr><td><code>.dct-breadcrumb</code></td><td><code>aria-label</code> o <code>aria-labelledby</code>, lista, items y <code>aria-current="page"</code>.</td></tr>
                <tr><td><code>.dct-tabs</code></td><td><code>aria-label</code> o <code>aria-labelledby</code>, lista y enlaces; nunca roles de tab dinámica.</td></tr>
                <tr><td><code>.dct-pagination</code></td><td><code>aria-label</code> o <code>aria-labelledby</code>, lista, links y página actual.</td></tr>
                <tr><td><code>.dct-navbar</code></td><td>Admite <code>.dct-is-inverse</code>; toggle con <code>aria-expanded</code> y <code>aria-controls</code>, mientras la aplicación sincroniza <code>hidden</code>.</td></tr>
                <tr><td><code>.dct-menu</code></td><td>Lista de enlaces y nombre accesible obligatorio con <code>aria-label</code> o <code>aria-labelledby</code>.</td></tr>
              </tbody>
            </table>
            <div class="site-note"><strong>No disponible</strong>No existe <code>.dct-dropdown</code> ni JavaScript distribuido. dctcss tampoco pagina datos, cambia de URL, gestiona paneles de tabs ni decide qué enlace está activo.</div>
          </section>
<?php
};
return $page;
