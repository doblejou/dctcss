# Recetas dctcss 1.6.6

Toda API dctcss usada en estas recetas pertenece al inventario estable. Algunas composiciones incluyen CSS o JavaScript de aplicación identificado expresamente y sin prefijo `dct-`. Los atributos HTML ordinarios como `type`, `name`, `autocomplete`, `action` o `accept` siguen siendo necesarios cuando la semántica y la aplicación los requieran; el inventario de atributos del manifiesto no es una lista blanca de HTML.

Las clases `app-*` de las recetas siguientes pertenecen a la aplicación y no son API estable de dctcss. Copia también su CSS cuando adaptes una composición.

## Formulario completo

La aplicación procesa el envío y valida de nuevo los datos en el servidor.

```html
<form action="/contacto" method="post">
  <fieldset class="dct-fieldset">
    <legend class="dct-legend">Solicitud de contacto</legend>
    <div class="dct-field-group">
      <div class="dct-field">
        <label class="dct-label" for="contact-name">Nombre</label>
        <input class="dct-input" id="contact-name" name="name" type="text" autocomplete="name" required>
      </div>
      <div class="dct-field">
        <label class="dct-label" for="contact-email">Correo electrónico</label>
        <input class="dct-input" id="contact-email" name="email" type="email" autocomplete="email" required>
      </div>
    </div>
    <div class="dct-field">
      <label class="dct-label" for="contact-topic">Motivo</label>
      <select class="dct-select" id="contact-topic" name="topic" required>
        <option value="">Selecciona un motivo</option>
        <option value="support">Soporte</option>
        <option value="sales">Ventas</option>
      </select>
    </div>
    <div class="dct-field">
      <label class="dct-label" for="contact-message">Mensaje</label>
      <textarea class="dct-textarea" id="contact-message" name="message" rows="5" required aria-describedby="contact-message-help"></textarea>
      <p class="dct-help" id="contact-message-help">No incluyas información sensible.</p>
    </div>
    <label class="dct-choice">
      <input name="terms" type="checkbox" required>
      <span>Acepto las condiciones de contacto.</span>
    </label>
    <div class="dct-cluster">
      <button class="dct-button dct-is-primary" type="submit">Enviar solicitud</button>
      <button class="dct-button" type="reset">Limpiar</button>
    </div>
  </fieldset>
</form>
```

Para mostrar un error, aplica `.dct-is-error` al `.dct-field` y sincroniza en el control `aria-invalid="true"`, `aria-describedby` y un `.dct-help` visible con el id referenciado.

## Tabla responsive

El wrapper conserva acceso por teclado cuando la tabla necesita desplazamiento horizontal. La aplicación es responsable de ordenar, paginar o seleccionar datos.

Conserva `--dct-table-min` (40rem por defecto) salvo que hayas validado otro mínimo con tus datos y texto ampliado. `overflow-x: auto` permite desplazamiento cuando el contenido necesita más anchura; no crea anchura por sí mismo. En ancestros Grid/Flex, permite que el área de contenido se contraiga (`min-inline-size: 0` o pistas `minmax(0, 1fr)`) para que el scroll quede dentro del wrapper.

Evita aplicar `overflow-wrap: anywhere` globalmente: limítalo a descripciones, URLs o texto libre. Los encabezados y `.dct-is-numeric` evitan cortes dentro de palabras, pero permiten saltos entre palabras; esta última clase alinea cifras y no declara que todo el contenido sea indivisible (puede incluir «128 EUR, completado»). Para mantener juntos importe, signo y moneda, usa esta clase de aplicación solo en esos valores; no impongas nowrap a toda la tabla.

```css
.app-amount { white-space: nowrap; }
```

Los badges son etiquetas breves de una sola línea. Un badge largo aumenta la anchura intrínseca de la tabla y queda accesible mediante scroll. Fuera de ella, reserva espacio, proporciona desplazamiento local o muestra el texto largo como texto normal; no lo recortes ni lo conviertas en una etiqueta multilínea comprimida. Estados de varias palabras sin badge pueden ajustarse normalmente.

```html
<div class="dct-table-wrap dct-is-elevated" tabindex="0" role="region" aria-labelledby="orders-caption">
  <table class="dct-table dct-is-striped dct-is-hoverable">
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
      <tr class="dct-is-success">
        <th scope="row">DCT-1042</th>
        <td>Ada Lovelace</td>
        <td class="dct-is-numeric app-amount">999.999.999,99 EUR</td>
        <td><span class="dct-badge dct-is-success">Activo</span></td>
      </tr>
      <tr class="dct-is-warning">
        <th scope="row">DCT-1043</th>
        <td>Grace Hopper</td>
        <td class="dct-is-numeric app-amount">-84.500,50 EUR</td>
        <td>Pendiente de pago</td>
      </tr>
    </tbody>
  </table>
</div>
```

## Lista compacta de pares

Usa `dl` para pares de etiqueta y valor. Las clases `app-*` y este CSS pertenecen a la aplicación: no son API de dctcss. Los tokens públicos mantienen la composición alineada con el tema.

```css
.app-compact-list {
  margin: 0;
}

.app-compact-list__row {
  align-items: baseline;
  border-block-end: 1px solid var(--dct-color-border);
  display: grid;
  gap: var(--dct-space-3);
  grid-template-columns: minmax(0, 1fr) auto;
  padding-block: var(--dct-space-2);
}

.app-compact-list__row:last-child {
  border-block-end: 0;
}

.app-compact-list dt {
  font-weight: 700;
}

.app-compact-list dd {
  margin: 0;
  overflow-wrap: anywhere;
  text-align: end;
}
```

```html
<dl class="app-compact-list">
  <div class="app-compact-list__row">
    <dt>Categoría</dt>
    <dd>Servicios</dd>
  </div>
  <div class="app-compact-list__row">
    <dt>Importe</dt>
    <dd>128,00 EUR</dd>
  </div>
  <div class="app-compact-list__row">
    <dt>Estado</dt>
    <dd>Confirmado</dd>
  </div>
</dl>
```

Mantén cada `dt` y `dd` dentro del mismo contenedor y conserva el orden etiqueta-valor. Si esta composición se repite en varias aplicaciones podrá evaluarse como componente estable; no la trates todavía como `.dct-*`.

## Navbar interactiva

El menú queda visible sin JavaScript. La mejora progresiva lo colapsa en un viewport estrecho y mantiene `hidden` y `aria-expanded` sincronizados al iniciar, activar y cambiar de breakpoint.

```html
<nav class="dct-navbar dct-is-inverse" aria-label="Navegación principal">
  <a class="dct-navbar-brand" href="/">Producto</a>
  <button class="dct-button dct-navbar-toggle" id="main-menu-toggle" type="button" hidden aria-expanded="true" aria-controls="main-menu">Menú</button>
  <div class="dct-navbar-menu" id="main-menu">
    <a class="dct-navbar-item" href="/dashboard" aria-current="page">Dashboard</a>
    <a class="dct-navbar-item" href="/proyectos">Proyectos</a>
    <div class="dct-navbar-actions">
      <a class="dct-button dct-is-primary dct-is-small" href="/proyectos/nuevo">Crear proyecto</a>
    </div>
  </div>
</nav>
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
```

## Confirmación con diálogo

La aplicación abre el diálogo, procesa `returnValue`, ejecuta la acción confirmada y devuelve el foco al disparador.

```html
<button class="dct-button dct-is-danger" id="delete-project" type="button">Eliminar proyecto</button>
<dialog class="dct-dialog" id="delete-dialog" aria-labelledby="delete-title">
  <header class="dct-dialog-header">
    <h2 class="dct-dialog-title" id="delete-title">Eliminar proyecto</h2>
  </header>
  <div class="dct-dialog-body"><p>Esta acción no se puede deshacer.</p></div>
  <form class="dct-dialog-actions" method="dialog">
    <button class="dct-button" type="submit" value="cancel">Cancelar</button>
    <button class="dct-button dct-is-danger" type="submit" value="confirm">Eliminar</button>
  </form>
</dialog>
<script>
  const deleteTrigger = document.querySelector("#delete-project");
  const deleteDialog = document.querySelector("#delete-dialog");

  deleteTrigger.addEventListener("click", () => deleteDialog.showModal());
  deleteDialog.addEventListener("close", () => {
    if (deleteDialog.returnValue === "confirm") {
      // Ejecuta aquí la eliminación desde la aplicación.
    }
    deleteTrigger.focus();
  });
</script>
```

## Encabezado de página con acciones

La toolbar y el page header son composiciones de aplicación; sus clases `app-*` no son API de dctcss. El texto largo comprueba que las acciones envuelven sin tapar el título.

```css
.app-page-header {
  align-items: end;
  display: flex;
  flex-wrap: wrap;
  gap: var(--dct-space-4) var(--dct-space-6);
  justify-content: space-between;
}

.app-page-header__copy {
  max-inline-size: var(--dct-content-measure);
}

.app-toolbar {
  align-items: center;
  display: flex;
  flex-wrap: wrap;
  gap: var(--dct-control-gap);
}
```

```html
<header class="app-page-header">
  <div class="app-page-header__copy dct-stack">
    <p class="dct-meta">Operaciones / trimestre actual</p>
    <h1 class="dct-title">Proyectos con entregas que necesitan revisión antes del cierre mensual</h1>
    <p class="dct-subtitle">Coordina responsables, fechas y dependencias sin perder el contexto del equipo.</p>
  </div>
  <div class="app-toolbar" aria-label="Acciones de la página">
    <button class="dct-button" type="button">Exportar</button>
    <a class="dct-button dct-is-primary" href="/proyectos/nuevo">Crear proyecto</a>
  </div>
</header>
```

## Dashboard básico con KPIs

Los KPI son artículos de aplicación compuestos con card; sus clases `app-*` no son API de dctcss y no existe una clase `.dct-kpi`. Mantén una etiqueta textual para que el significado no dependa del tamaño o el color.

```css
.app-dashboard {
  display: grid;
  gap: var(--dct-space-6);
}

.app-kpi-grid {
  display: grid;
  gap: var(--dct-space-4);
  grid-template-columns: repeat(auto-fit, minmax(min(100%, 13rem), 1fr));
}

.app-kpi__value {
  font-size: var(--dct-font-size-2xl);
  font-weight: var(--dct-font-weight-bold);
  line-height: var(--dct-line-height-tight);
}
```

```html
<main class="app-dashboard dct-container">
  <header class="dct-stack">
    <p class="dct-meta">Actualizado hoy a las 09:30</p>
    <h1 class="dct-title">Resumen operativo</h1>
  </header>
  <section class="app-kpi-grid" aria-label="Indicadores principales">
    <article class="app-kpi dct-card"><div class="dct-card-body"><p class="dct-meta">Proyectos activos</p><p class="app-kpi__value">24</p><p class="dct-card-content">Tres requieren atención esta semana.</p></div></article>
    <article class="app-kpi dct-card"><div class="dct-card-body"><p class="dct-meta">Entregas en plazo</p><p class="app-kpi__value">92 %</p><p class="dct-card-content">Dos puntos más que el mes anterior.</p></div></article>
    <article class="app-kpi dct-card"><div class="dct-card-body"><p class="dct-meta">Revisiones abiertas</p><p class="app-kpi__value">7</p><p class="dct-card-content">La revisión más antigua lleva cuatro días.</p></div></article>
  </section>
  <section class="dct-stack" aria-labelledby="dashboard-work-title">
    <h2 class="dct-title" id="dashboard-work-title">Trabajo prioritario</h2>
    <aside class="dct-message dct-is-warning" aria-labelledby="dashboard-warning-title"><div class="dct-message-body"><h3 class="dct-message-title" id="dashboard-warning-title">Una entrega está bloqueada</h3><p class="dct-message-content">Atlas necesita aprobación legal antes del viernes.</p></div></aside>
  </section>
</main>
```

## Empty state con acción opcional

El empty state es una composición `app-*`. Si no existe una acción útil, omite el último párrafo en lugar de añadir un botón inerte.

```css
.app-empty-state {
  border: 1px dashed var(--dct-color-border-strong);
  border-radius: var(--dct-radius-lg);
  display: grid;
  justify-items: center;
  padding: var(--dct-panel-padding);
  text-align: center;
}

.app-empty-state > * {
  max-inline-size: var(--dct-content-measure);
}
```

```html
<section class="app-empty-state dct-stack" aria-labelledby="empty-projects-title">
  <svg class="dct-icon dct-is-large" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14"></path></svg>
  <h2 class="dct-title" id="empty-projects-title">Todavía no hay proyectos</h2>
  <p class="dct-subtitle">Crea el primero para organizar responsables, entregas y conversaciones del equipo en un mismo lugar.</p>
  <p><a class="dct-button dct-is-primary" href="/proyectos/nuevo">Crear el primer proyecto</a></p>
</section>
```

## Lista de actividad

Esta lista conserva orden cronológico y contenido largo. La composición `app-activity` no es un componente estable.

```css
.app-activity {
  list-style: none;
  margin: 0;
  padding: 0;
}

.app-activity__item {
  border-block-end: 1px solid var(--dct-color-border-subtle);
  display: grid;
  gap: var(--dct-space-2) var(--dct-space-3);
  grid-template-columns: auto minmax(0, 1fr);
  padding-block: var(--dct-navigation-padding-block);
}

.app-activity__item:last-child {
  border-block-end: 0;
}

.app-activity__body {
  overflow-wrap: anywhere;
}
```

```html
<article class="dct-card" aria-labelledby="activity-title">
  <div class="dct-card-body">
    <h2 class="dct-card-title" id="activity-title">Actividad reciente</h2>
    <ol class="app-activity">
      <li class="app-activity__item"><span class="dct-avatar" aria-hidden="true">AL</span><div class="app-activity__body"><p><strong>Ada Lovelace</strong> aprobó la propuesta de arquitectura del proyecto Atlas.</p><p class="dct-meta"><time datetime="2026-07-27T09:10:00Z">Hoy, 09:10 UTC</time></p></div></li>
      <li class="app-activity__item"><span class="dct-avatar" aria-hidden="true">GH</span><div class="app-activity__body"><p><strong>Grace Hopper</strong> solicitó cambios en una entrega con un nombre excepcionalmente largo para comprobar el ajuste del contenido.</p><p class="dct-meta"><time datetime="2026-07-26T17:45:00Z">Ayer, 17:45 UTC</time></p></div></li>
    </ol>
  </div>
</article>
```

## Ajustes con densidad compacta

Aplica la densidad al subárbol que la necesita. No inventes modificadores compactos por componente ni reduzcas manualmente cada control.

```css
.app-settings-panel {
  background: var(--dct-color-surface);
  border: 1px solid var(--dct-color-border-subtle);
  border-radius: var(--dct-radius-lg);
  padding: var(--dct-panel-padding);
}
```

```html
<section class="app-settings-panel dct-stack" data-dct-density="compact" aria-labelledby="compact-settings-title">
  <header class="dct-stack">
    <h2 class="dct-title" id="compact-settings-title">Preferencias de notificación</h2>
    <p class="dct-subtitle">La densidad se hereda solo dentro de este panel de configuración.</p>
  </header>
  <form action="/ajustes/notificaciones" method="post">
    <fieldset class="dct-fieldset">
      <legend class="dct-legend">Canales</legend>
      <label class="dct-switch"><input name="email" type="checkbox" role="switch" checked><span>Resumen por correo</span></label>
      <label class="dct-switch"><input name="browser" type="checkbox" role="switch"><span>Avisos en el navegador</span></label>
      <div class="dct-field"><label class="dct-label" for="compact-frequency">Frecuencia</label><select class="dct-select" id="compact-frequency" name="frequency"><option value="daily">Diaria</option><option value="weekly">Semanal</option></select></div>
      <button class="dct-button dct-is-primary" type="submit">Guardar preferencias</button>
    </fieldset>
  </form>
</section>
```

## Botón de icono accesible

El nombre pertenece al botón; el SVG visible es decorativo. Conserva un único SVG `.dct-icon` y no uses el atributo `title` como sustituto de `aria-label`.

```html
<button class="dct-button dct-is-icon-only" type="button" aria-label="Buscar proyectos">
  <svg class="dct-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <circle cx="11" cy="11" r="7"></circle><path d="m20 20-4-4"></path>
  </svg>
</button>
```

## Shell administrativo responsive

Combina navegación global inversa, menú local y contenido en un orden DOM útil para móvil. La aplicación reutiliza la sincronización de la receta de navbar interactiva; dctcss no distribuye este JavaScript.

```html
<a class="dct-skip-link" href="#admin-main">Saltar al contenido</a>
<nav class="dct-navbar dct-is-inverse" aria-label="Navegación principal de Atlas">
  <a class="dct-navbar-brand" href="/">Atlas</a>
  <button class="dct-button dct-navbar-toggle" id="admin-menu-toggle" type="button" hidden aria-expanded="true" aria-controls="admin-menu">Menú</button>
  <div class="dct-navbar-menu" id="admin-menu">
    <a class="dct-navbar-item" href="/panel" aria-current="page">Panel</a>
    <a class="dct-navbar-item" href="/proyectos">Proyectos</a>
    <div class="dct-navbar-actions"><a class="dct-button dct-is-primary" href="/proyectos/nuevo">Crear proyecto</a></div>
  </div>
</nav>
<div class="dct-container dct-sidebar-layout dct-p-4">
  <aside class="dct-sidebar">
    <nav class="dct-menu" aria-label="Secciones del panel">
      <ul class="dct-menu-list">
        <li><a class="dct-menu-item" href="/panel" aria-current="page">Resumen</a></li>
        <li><a class="dct-menu-item" href="/panel/actividad">Actividad</a></li>
      </ul>
    </nav>
  </aside>
  <main class="dct-main dct-stack" id="admin-main" tabindex="-1">
    <h1 class="dct-title">Resumen operativo</h1>
    <p class="dct-subtitle">Pedidos, capacidad y riesgos del equipo.</p>
  </main>
</div>
<script>
  const adminToggle = document.querySelector("#admin-menu-toggle");
  const adminMenu = document.querySelector("#admin-menu");
  const adminDesktop = window.matchMedia("(min-width: 64rem)");

  function syncAdminMenu(desktop) {
    adminToggle.hidden = desktop;
    adminMenu.hidden = !desktop;
    adminToggle.setAttribute("aria-expanded", String(desktop));
  }

  syncAdminMenu(adminDesktop.matches);
  adminDesktop.addEventListener("change", (event) => syncAdminMenu(event.matches));
  adminToggle.addEventListener("click", () => {
    const expanded = adminToggle.getAttribute("aria-expanded") === "true";
    adminMenu.hidden = expanded;
    adminToggle.setAttribute("aria-expanded", String(!expanded));
  });
</script>
```

## Panel de filtros responsive

El panel y su toolbar son composición de aplicación. El `fieldset` mantiene un nombre de grupo y el cluster permite que controles y acciones envuelvan sin una cuadrícula rígida.

```css
.app-filter-panel {
  background: var(--dct-color-surface);
  border: 1px solid var(--dct-color-border-subtle);
  border-radius: var(--dct-radius-lg);
  padding: var(--dct-panel-padding);
}

.app-filter-toolbar {
  align-items: end;
  display: flex;
  flex-wrap: wrap;
  gap: var(--dct-space-4);
}

.app-filter-toolbar .dct-field {
  flex: 1 1 12rem;
}
```

```html
<form class="app-filter-panel" action="/pedidos" method="get">
  <fieldset class="dct-fieldset">
    <legend class="dct-legend">Filtrar pedidos</legend>
    <div class="app-filter-toolbar">
      <div class="dct-field">
        <label class="dct-label" for="filter-status">Estado</label>
        <select class="dct-select" id="filter-status" name="status"><option value="">Todos</option><option value="open">Abiertos</option></select>
      </div>
      <div class="dct-field">
        <label class="dct-label" for="filter-query">Buscar</label>
        <input class="dct-input" id="filter-query" name="query" type="search">
      </div>
      <div class="dct-cluster">
        <button class="dct-button dct-is-primary" type="submit">Aplicar filtros</button>
        <a class="dct-button" href="/pedidos">Limpiar</a>
      </div>
    </div>
  </fieldset>
</form>
```

## Formulario móvil con acciones sticky

La barra sticky pertenece a la aplicación. Mantiene fondo, borde y separación para no ocultar contenido; los botones siguen el orden DOM y vuelven a ancho automático desde `40rem`.

```css
.app-mobile-form {
  padding-block-end: calc(var(--dct-control-height) + var(--dct-space-6));
}

.app-sticky-actions {
  background: var(--dct-color-surface);
  border-block-start: 1px solid var(--dct-color-border-subtle);
  bottom: 0;
  display: flex;
  flex-wrap: wrap;
  gap: var(--dct-space-2);
  padding: var(--dct-space-3) var(--dct-container-gutter);
  position: sticky;
  z-index: 10;
}
```

```html
<form class="app-mobile-form dct-stack" action="/perfil" method="post">
  <fieldset class="dct-fieldset">
    <legend class="dct-legend">Datos del perfil</legend>
    <div class="dct-field">
      <label class="dct-label" for="profile-name">Nombre</label>
      <input class="dct-input" id="profile-name" name="name" type="text" autocomplete="name" required>
    </div>
    <div class="dct-field">
      <label class="dct-label" for="profile-email">Correo electrónico</label>
      <input class="dct-input" id="profile-email" name="email" type="email" autocomplete="email" required>
    </div>
  </fieldset>
  <div class="app-sticky-actions">
    <button class="dct-button dct-is-primary dct-is-fullwidth-mobile" type="submit">Guardar cambios</button>
    <a class="dct-button dct-is-fullwidth-mobile" href="/perfil">Cancelar</a>
  </div>
</form>
```

No uses sticky si tapa errores, ayuda o controles al ampliar el texto. La aplicación debe probar teclado virtual, zoom, safe areas de su plataforma, validación y retorno del foco; si no puede garantizarlo, usa acciones normales al final del formulario.

## Resumen por contenedor

El mismo resumen de entrega cabe en una columna lateral o en el área principal. El padre declara el contenedor y su hijo cambia de distribución al disponer de 28rem. Sin consultas de contenedor se conserva una columna. `app-panel-lateral` solo representa el espacio más reducido en este ejemplo.

```css
.app-panel-entrega
{
  container-type: inline-size;
}

.app-panel-lateral
{
  max-inline-size: 20rem;
}

.app-resumen-entrega
{
  display: grid;
  grid-template-columns: minmax(0, 1fr);
  gap: var(--dct-space-3);
  overflow-wrap: anywhere;
}

.app-resumen-entrega dd
{
  margin-inline-start: 0;
}

@container (min-width: 28rem)
{
  .app-resumen-entrega
  {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }

}
```

```html
<div class="dct-stack">
  <div class="app-panel-entrega app-panel-lateral">
    <dl class="app-resumen-entrega">
      <div><dt>Pedido</dt><dd>Atlas</dd></div>
      <div><dt>Destino</dt><dd>Almacén de suministros del norte</dd></div>
    </dl>
  </div>
  <div class="app-panel-entrega">
    <dl class="app-resumen-entrega">
      <div><dt>Pedido</dt><dd>Boreal</dd></div>
      <div><dt>Destino</dt><dd>Centro de distribución regional</dd></div>
    </dl>
  </div>
</div>
```

## Acordeones exclusivos por grupo

Comparte un `name` no vacío entre los `details` del mismo grupo. Usa otro nombre para cada grupo independiente, incluso si está en otro contenedor: la envoltura visual no delimita la exclusividad. Como máximo un panel por grupo debe llevar `open` inicialmente. Si el navegador ignora `name`, todos siguen pudiendo abrirse y cerrarse por separado.

```html
<h3>Envío</h3>
<div class="dct-accordion">
  <details class="dct-disclosure" name="pedido-envio" open>
    <summary class="dct-disclosure-summary">Preparación</summary>
    <p class="dct-disclosure-content">El almacén prepara el pedido.</p>
  </details>
  <details class="dct-disclosure" name="pedido-envio">
    <summary class="dct-disclosure-summary">Seguimiento</summary>
    <p class="dct-disclosure-content">Recibirás el localizador por correo.</p>
  </details>
</div>
<h3>Devolución</h3>
<div class="dct-accordion">
  <details class="dct-disclosure" name="pedido-devolucion" open>
    <summary class="dct-disclosure-summary">Plazo</summary>
    <p class="dct-disclosure-content">Dispones de treinta días desde la entrega.</p>
  </details>
  <details class="dct-disclosure" name="pedido-devolucion">
    <summary class="dct-disclosure-summary">Recogida</summary>
    <p class="dct-disclosure-content">Elige un horario para la recogida.</p>
  </details>
</div>
```
