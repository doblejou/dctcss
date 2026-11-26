---
name: dctcss
description: Usa dctcss 1.6.4 para construir aplicaciones web con su API CSS estable, accesible y legible por agentes.
---

# dctcss 1.6.4

Usa esta skill para generar marcado con dctcss. El framework solo aporta CSS: la aplicación controla datos, validación, estado interactivo, foco y navegación. `references/dctcss-api.json`, con `schemaVersion: 2`, inventaría la API, `references/RECIPES.md` contiene composiciones completas y `references/CHANGELOG.md` permite evaluar actualizaciones.

La unidad instalable es la carpeta completa `skills/dctcss/`, incluidos `SKILL.md`, `LICENSE` y `references/`. Copiar solo este Markdown es una instalación incompleta.

El inventario `attributes` del manifiesto no es una lista blanca de HTML. Solo enumera atributos requeridos o restringidos por contratos de dctcss; usa con normalidad atributos semánticos como `type`, `name`, `autocomplete`, `action`, `method`, `accept`, `checked`, `rows` o `placeholder` cuando corresponda.

## Flujo operativo

1. Lee las reglas de decisión de esta skill antes de generar marcado.
2. Elige elementos semánticos, layouts, componentes y estados.
3. Consulta `references/dctcss-api.json` cuando necesites confirmar nombres, elementos, ancestros, hijos, atributos o modificadores. No necesitas leerlo completo para tareas pequeñas.
4. Consulta `references/RECIPES.md` para composiciones completas y `references/CHANGELOG.md` antes de actualizar una versión instalada.
5. Genera solo API inventariada.
6. Si dispones de la distribución, ejecuta `php dctcss-validate.php --manifest references/dctcss-api.json ruta/al/html` para comprobar contratos estáticos.
7. Ejecuta el checklist de salida antes de entregar el resultado.

## Matriz de decisión

| Necesidad | Elección |
|---|---|
| Acción | `button.dct-button` |
| Navegación | Enlace real, opcionalmente `a.dct-button` |
| Flujo vertical | `.dct-stack` |
| Fila que envuelve | `.dct-cluster` |
| Columnas autoajustables | `.dct-grid` |
| Contenido independiente | `article.dct-card` |
| Feedback estático | Message nombrado sin live region |
| Feedback dinámico no urgente | `role="status"` |
| Error dinámico urgente | `role="alert"` |
| Datos tabulares | Tabla nativa dentro de `.dct-table-wrap` |
| Tabla con profundidad visual | `.dct-table-wrap.dct-is-elevated` |
| Navbar sobre superficie oscura | `.dct-navbar.dct-is-inverse` |
| Navegación entre páginas | Breadcrumb, tabs de enlaces o pagination |
| Disclosure | `details.dct-disclosure` |
| Modal | `dialog.dct-dialog` controlado por la aplicación |
| Título destacado único | `.dct-display` |
| Título de página o sección | `.dct-title` |
| Introducción asociada | `.dct-subtitle` |
| Metadato breve | `.dct-meta` |
| Zona de muchos controles | `data-dct-density="compact"` en su ancestro |
| Acción solo con icono | `.dct-button.dct-is-icon-only` con nombre accesible |
| Sombra de botón solo al pasar el cursor | `.dct-button.dct-is-hover-elevated` |

## Inclusión y nombres

Incluye una sola variante:

```html
<link rel="stylesheet" href="/ruta/a/dct.min.css">
```

Usa `dct.css` para depuración. Carga las personalizaciones después y nunca edites artefactos generados.

Toda API propia lleva `dct-`: clases `.dct-*`, tokens `--dct-*`, atributos `data-dct-theme` y `data-dct-density`, y animación `dct-spin`. No atribuyas al framework clases genéricas ni nombres fuera del inventario.

## Recetas autoritativas

Consulta `references/RECIPES.md` para copiar composiciones completas de formulario, tabla responsive, lista compacta, navbar, diálogo, encabezado de página, dashboard, empty state, actividad, ajustes compactos, shell administrativo, panel de filtros, formulario móvil sticky e icon button. Sus clases `app-*`, CSS y scripts de aplicación no amplían la API de dctcss. Toolbar, page header, KPI, panel, actividad y empty state no son componentes estables: no los renombres con `dct-*`.

Para detectar actualizaciones, compara la versión instalada con el campo `version` de `/release.json`. Revisa después `references/CHANGELOG.md` o `/changelog.html` y ejecuta las pruebas de la aplicación antes de sustituir el CSS. Las URLs `/downloads/*` son `latest`: no actualices automáticamente producción ni las trates como artefactos históricos inmutables.

## Tokens y temas

Sobrescribe tokens desde CSS de aplicación:

```css
:root {
  --dct-color-accent: #8f3dff;
  --dct-color-focus: #7022d1;
  --dct-radius-md: 0.25rem;
}
```

Sin atributo se usa claro y se respeta `prefers-color-scheme: dark`. Fija un contexto con `data-dct-theme="light"` o `data-dct-theme="dark"`; la aplicación cambia y persiste el atributo si ofrece selector de tema.

Tokens de superficie y borde: `--dct-color-background`, `--dct-color-surface`, `--dct-color-surface-subtle`, `--dct-color-surface-raised`, `--dct-color-surface-hover`, `--dct-color-border`, `--dct-color-border-subtle`, `--dct-color-border-strong`.

Tokens de contenido y estado: `--dct-color-text`, `--dct-color-text-muted`, `--dct-color-accent`, `--dct-color-accent-hover`, `--dct-color-accent-soft`, `--dct-color-accent-contrast`, `--dct-color-danger`, `--dct-color-danger-hover`, `--dct-color-danger-soft`, `--dct-color-danger-contrast`, `--dct-color-success`, `--dct-color-success-soft`, `--dct-color-success-contrast`, `--dct-color-info`, `--dct-color-info-soft`, `--dct-color-info-contrast`, `--dct-color-warning`, `--dct-color-warning-soft`, `--dct-color-warning-contrast`, `--dct-color-focus`.

Tokens tipográficos: `--dct-font-family-sans`, `--dct-font-family-mono`, `--dct-font-size-sm`, `--dct-font-size-base`, `--dct-font-size-lg`, `--dct-font-size-xl`, `--dct-font-size-2xl`, `--dct-font-size-3xl`, `--dct-font-weight-normal`, `--dct-font-weight-medium`, `--dct-font-weight-bold`, `--dct-line-height-tight`, `--dct-line-height-base`, `--dct-line-height-relaxed`, `--dct-content-measure`.

Tokens estructurales: `--dct-space-1`, `--dct-space-2`, `--dct-space-3`, `--dct-space-4`, `--dct-space-5`, `--dct-space-6`, `--dct-radius-sm`, `--dct-radius-md`, `--dct-radius-lg`, `--dct-focus-width`, `--dct-focus-offset`, `--dct-shadow-sm`, `--dct-shadow-md`, `--dct-shadow-lg`, `--dct-duration-fast`, `--dct-duration-normal`, `--dct-easing-standard`.

Tokens de densidad: `--dct-control-height`, `--dct-control-padding-block`, `--dct-control-padding-inline`, `--dct-control-gap`, `--dct-panel-padding`, `--dct-navigation-padding-block`, `--dct-navigation-padding-inline`.

Tokens de navbar inversa: `--dct-navbar-inverse-background`, `--dct-navbar-inverse-text`, `--dct-navbar-inverse-text-muted`, `--dct-navbar-inverse-hover`. Personalízalos juntos y conserva contraste de texto, hover, activo, botones y foco.

Tokens de layout y componentes: `--dct-container-max`, `--dct-container-gutter`, `--dct-stack-gap`, `--dct-cluster-gap`, `--dct-cluster-align`, `--dct-cluster-justify`, `--dct-grid-min`, `--dct-grid-gap`, `--dct-card-padding`, `--dct-card-gap`, `--dct-card-media-ratio`, `--dct-table-min`, `--dct-table-cell-padding`.

Personaliza grupos coherentes, no un estado aislado. Si cambias el acento, revisa también hover, soft, contrast y focus; si cambias superficies, revisa fondo, surface, subtle, raised, hover y los tres bordes; si cambias densidad, conserva alturas y paddings coherentes entre controles, paneles y navegación.

La elevación comunica profundidad: `--dct-shadow-sm` corresponde a popover, `--dct-shadow-md` a `.dct-card.dct-is-elevated` y `.dct-table-wrap.dct-is-elevated`, y `--dct-shadow-lg` a dialog. Las superficies normales permanecen planas.

### Densidad

El modo cómodo no necesita atributo. Aplica `data-dct-density="compact"` en `html` o, preferentemente, en un subárbol con muchos controles. La herencia ajusta controles, paneles y navegación; también armoniza `--dct-card-padding` y `--dct-table-cell-padding`. No crees variantes compactas por componente. `.dct-table.dct-is-compact` sigue siendo un modificador estable independiente.

```html
<section class="dct-stack" data-dct-density="compact" aria-labelledby="settings-title">
  <h2 class="dct-title" id="settings-title">Ajustes avanzados</h2>
  <div class="dct-field">
    <label class="dct-label" for="region">Región</label>
    <select class="dct-select" id="region" name="region"><option>Europa</option></select>
  </div>
</section>
```

No crees temas copiando reglas de componentes. Conserva contraste de texto, superficies, estados y foco en ambos temas.

## Layout

### Container, stack, cluster y grid

- `.dct-container`: centra y limita una región; ajusta `--dct-container-max` y `--dct-container-gutter`.
- `.dct-stack`: flujo vertical; ajusta `--dct-stack-gap`.
- `.dct-cluster`: fila Flex que envuelve; ajusta gap, alineación y justificación con sus tres tokens.
- `.dct-grid`: columnas autoajustables; ajusta `--dct-grid-min` y `--dct-grid-gap`, sin forzar un número fijo.

```html
<main class="dct-container">
  <section class="dct-stack" aria-labelledby="projects-title">
    <div class="dct-cluster">
      <h1 id="projects-title">Proyectos</h1>
      <a class="dct-button dct-is-primary" href="/proyectos/nuevo">Crear proyecto</a>
    </div>
    <div class="dct-grid">
      <article class="dct-card"><div class="dct-card-body"><h2 class="dct-card-title">Atlas</h2></div></article>
    </div>
  </section>
</main>
```

### Sidebar

`.dct-sidebar-layout` requiere descendientes `.dct-sidebar` y `.dct-main`. Es una columna en móvil y dos desde `64rem`; no colapsa ni crea landmarks.

```html
<div class="dct-sidebar-layout">
  <aside class="dct-sidebar">
    <nav class="dct-menu" aria-label="Ajustes">
      <ul class="dct-menu-list"><li><a class="dct-menu-item" href="/ajustes" aria-current="page">Perfil</a></li></ul>
    </nav>
  </aside>
  <main class="dct-main"><h1>Perfil</h1></main>
</div>
```

Elige `aside`, `main`, `section` o `div` por semántica y conserva un orden DOM útil en móvil.

Cuando un resumen deba responder al ancho de un panel en lugar del viewport, consulta «Resumen por contenedor» en `references/RECIPES.md`. El CSS `app-*` pertenece a la aplicación.

## Componentes

### Content

Usa `.dct-content` en `article`, `section` o `div` para dar ritmo a HTML editorial semántico.

```html
<article class="dct-content">
  <h1>Guía de despliegue</h1>
  <p>Ejecuta las comprobaciones antes de publicar.</p>
  <blockquote>Conserva una ruta de vuelta.</blockquote>
</article>
```

No la uses como layout ni sobre controles. La aplicación debe sanear HTML generado por usuarios y mantener una jerarquía de encabezados correcta.

### Tipografía semántica

`.dct-display` destaca el encabezado único de una pantalla o pieza editorial; `.dct-title` nombra una página o sección; `.dct-subtitle` introduce el título asociado; `.dct-meta` presenta fecha, etiqueta contextual o metadato breve. Definen tipografía y color, no márgenes externos: compón el ritmo con `.dct-stack` o CSS de aplicación. Elige el elemento `h1`-`h6`, `p` o `time` por semántica, no por tamaño visual.

```html
<header class="dct-stack">
  <p class="dct-meta">Informe mensual / julio de 2026</p>
  <h1 class="dct-display">Operaciones</h1>
  <p class="dct-subtitle">Estado de entregas, capacidad y riesgos del equipo.</p>
</header>
```

### Button

Usa `button.dct-button` para acciones y `a.dct-button` para navegación. Variantes: `.dct-is-primary`, `.dct-is-secondary`, `.dct-is-danger`. Tamaños: `.dct-is-small`, `.dct-is-medium`, `.dct-is-large`. `.dct-is-fullwidth` ocupa todo el ancho siempre; `.dct-is-fullwidth-mobile` ocupa todo el ancho por debajo de `40rem` y vuelve al ancho automático desde ese punto. `.dct-is-icon-only` crea un botón cuadrado y conserva los tamaños. Combina como máximo una variante visual, un tamaño y un modificador de ancho o icono.

`.dct-is-hover-elevated` añade opcionalmente una sombra `--dct-shadow-sm` y una subida visual de 1 px solo durante hover en dispositivos que lo admiten. Úsalo únicamente con `.dct-button`, tanto en acciones como en enlaces, y combínalo con las variantes, tamaños y estados normales. No cambia dimensiones ni el layout; disabled, `aria-disabled="true"` y loading no reciben el efecto. El foco sigue usando el outline existente y active conserva el efecto de pulsación. No dependas del hover para comunicar información ni acciones; sin hover el botón conserva su apariencia normal. Con `prefers-reduced-motion: reduce` se omite la subida y se conserva la sombra con la transición reducida.

```html
<button class="dct-button dct-is-primary dct-is-hover-elevated" type="button">Guardar</button>
<a class="dct-button dct-is-secondary dct-is-hover-elevated" href="/documentacion">Documentación</a>
```

```html
<button class="dct-button dct-is-primary" type="button">Guardar</button>
<a class="dct-button dct-is-secondary" href="/documentacion">Documentación</a>
<button class="dct-button dct-is-fullwidth-mobile" type="button">Continuar</button>
<button class="dct-button dct-is-primary dct-is-loading" type="button" aria-busy="true" disabled>Guardando</button>
```

Loading requiere `aria-busy="true"`, texto accesible y bloqueo real. Usa `disabled` en botones. Un enlace temporalmente no disponible conserva su destino, usa `aria-disabled="true"` y `tabindex="-1"`, y la aplicación cancela todas sus activaciones:

```html
<a class="dct-button" href="/informe" aria-disabled="true" tabindex="-1">Informe no disponible</a>
<script>
  document.querySelectorAll('a[aria-disabled="true"]').forEach((link) => {
    link.addEventListener("click", (event) => event.preventDefault());
  });
</script>
```

No confíes en `pointer-events`: no protege activación programática ni handlers de aplicación.

Un icon button requiere `aria-label` o `aria-labelledby` en el botón y un único SVG visible `.dct-icon` con `aria-hidden="true"`. El nombre describe la acción, no la forma del dibujo. Disabled y loading conservan sus requisitos normales.

### Icon

Aplica `.dct-icon` solo a SVG inline. Admite `.dct-is-small`, `.dct-is-medium` y `.dct-is-large`. Declara `fill="currentColor"` en SVG basados en relleno o `fill="none" stroke="currentColor"` en SVG basados en trazos. dctcss no impone globalmente `fill` ni `stroke`, porque rompería la otra familia. Un ícono decorativo usa `aria-hidden="true"`; uno autónomo usa `role="img"` con `aria-label` o `aria-labelledby`.

```html
<svg class="dct-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
  <path d="m5 12 4 4L19 6"></path>
</svg>
<svg class="dct-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
  <path d="M12 2 2 12l10 10 10-10L12 2Z"></path>
</svg>
```

No uses fuentes de íconos, librerías, sprites remotos ni servicios externos. `dct-spin` es la animación pública usada internamente por botones loading.

Todos los SVG copiables siguientes comparten `viewBox`, trazo, extremos y uniones. Úsalos como iconos decorativos dentro de controles con nombre; para un SVG informativo autónomo sustituye `aria-hidden` por `role="img"` y `aria-label` o `aria-labelledby`.

```html
<!-- Añadir --><svg class="dct-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14"></path></svg>
<!-- Editar --><svg class="dct-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4 20 4.5-1 10-10a2.1 2.1 0 0 0-3-3l-10 10L4 20Z"></path><path d="m14 7 3 3"></path></svg>
<!-- Eliminar --><svg class="dct-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16M9 7V4h6v3m3 0-1 13H7L6 7m4 4v5m4-5v5"></path></svg>
<!-- Buscar --><svg class="dct-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-4-4"></path></svg>
<!-- Cerrar --><svg class="dct-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"></path></svg>
<!-- Menú --><svg class="dct-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"></path></svg>
```

### Message

`.dct-message` requiere `.dct-message-body`. Ícono, título, contenido y acciones son opcionales; `.dct-message-title`, `.dct-message-content` y `.dct-message-actions` van dentro del body. Variantes: `.dct-is-info`, `.dct-is-success`, `.dct-is-warning`, `.dct-is-danger`.

```html
<aside class="dct-message dct-is-info" aria-labelledby="plan-title">
  <span class="dct-message-icon" aria-hidden="true"><svg class="dct-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"></circle></svg></span>
  <div class="dct-message-body">
    <h2 class="dct-message-title" id="plan-title">Límite del plan</h2>
    <p class="dct-message-content">Puedes mantener cinco proyectos activos.</p>
    <div class="dct-message-actions"><a class="dct-button dct-is-small" href="/planes">Ver planes</a></div>
  </div>
</aside>
```

Un mensaje estático usa `section` o `aside` nombrado. Una actualización dinámica no urgente usa `role="status"`; solo un error dinámico urgente usa `role="alert"`. La aplicación inserta y retira mensajes; no existe API dismissible.

### Card

`.dct-card` admite `article` o `div` y requiere `.dct-card-body`. `.dct-card-media` es hermana opcional del body. Dentro del body usa `.dct-card-title`, `.dct-card-subtitle`, `.dct-card-content` y `.dct-card-actions`; actions requiere también `.dct-cluster`. Su modificador propio es `.dct-is-elevated`.

```html
<article class="dct-card dct-is-elevated">
  <div class="dct-card-body">
    <p class="dct-card-subtitle">Proyecto activo</p>
    <h2 class="dct-card-title">Atlas</h2>
    <p class="dct-card-content">Seguimiento de operaciones.</p>
    <div class="dct-cluster dct-card-actions"><a class="dct-button dct-is-primary" href="/proyectos/atlas">Abrir</a></div>
  </div>
</article>
```

No envuelvas toda la tarjeta en un enlace, no simules interactividad en `article` y proporciona `alt` apropiado a imágenes informativas.

### Table

`.dct-table-wrap` requiere `tabindex="0"`, `role="region"` y `aria-label` o `aria-labelledby`. `table.dct-table` requiere `caption.dct-table-caption` y encabezados con `scope`.

```html
<div class="dct-table-wrap dct-is-elevated" tabindex="0" role="region" aria-labelledby="orders-caption">
  <table class="dct-table dct-is-striped dct-is-hoverable">
    <caption class="dct-table-caption" id="orders-caption">Pedidos recientes</caption>
    <thead><tr><th scope="col">Pedido</th><th class="dct-is-numeric" scope="col">Importe</th></tr></thead>
    <tbody><tr class="dct-is-success"><th scope="row">DCT-1042</th><td class="dct-is-numeric">128 EUR, completado</td></tr></tbody>
  </table>
</div>
```

Wrapper: `.dct-is-elevated`, solo cuando la profundidad comunica jerarquía. Tabla: `.dct-is-striped`, `.dct-is-hoverable`, `.dct-is-compact`. Celdas `th`/`td`: `.dct-is-numeric`. Filas `tbody tr`: `.dct-is-selected`, `.dct-is-success`, `.dct-is-warning`, `.dct-is-danger`; repite el estado como texto. La aplicación ordena, pagina y selecciona; para ordenar usa botones y mantiene `aria-sort`.

Los encabezados y `.dct-is-numeric` usan `overflow-wrap: normal`: no heredan cortes arbitrarios de palabras. `.dct-is-numeric` alinea cifras tabulares pero permite saltos entre palabras en números con texto; para importes indivisibles aplica CSS de aplicación `white-space: nowrap` solo al valor o celda correspondiente. No apliques nowrap a toda la tabla. Conserva el mínimo `--dct-table-min` de 40rem o valida el mínimo alternativo con datos largos y texto al 200%; permite la contracción de ancestros Grid/Flex. Consulta la receta de tabla responsive.

### Badge, progress y avatar

`span.dct-badge` es una etiqueta breve no interactiva. Admite `.dct-is-info`, `.dct-is-success`, `.dct-is-warning` y `.dct-is-danger`; el texto expresa el estado sin depender del color.

Usa una sola línea (`white-space: nowrap` predeterminado). Las etiquetas largas no se truncan: necesitan espacio o desplazamiento local proporcionado por la aplicación; en una tabla aumentan su anchura intrínseca y el wrapper permite scroll. Para explicaciones largas usa texto normal, no un badge comprimido ni una nueva variante dctcss.

`progress.dct-progress` requiere `max`, un nombre accesible mediante `aria-label` o `aria-labelledby` y, para progreso determinado, `value`. Admite los mismos cuatro estados. La aplicación actualiza `value` y el texto; no uses `.dct-is-loading`.

`.dct-avatar` admite `img` o `span` y tamaños small, medium y large. Si el nombre aparece junto al avatar, usa `alt=""` en imagen o `aria-hidden="true"` en iniciales; si aporta información, proporciona `alt`. Nunca lo uses como único nombre de una persona.

```html
<span class="dct-badge dct-is-success">Completado</span>
<span id="upload-progress-label">Carga, 45 %</span>
<progress class="dct-progress dct-is-info" id="upload-progress" max="100" value="45" aria-labelledby="upload-progress-label">45 %</progress>
<span class="dct-avatar dct-is-medium" aria-hidden="true">AL</span><span>Ada Lovelace</span>
```

### Dialog

Usa `dialog.dct-dialog` con nombre mediante `aria-label` o `aria-labelledby`. `.dct-dialog-header` contiene `.dct-dialog-title`; `.dct-dialog-body` y `.dct-dialog-actions` son partes directas del diálogo.

```html
<button class="dct-button dct-is-danger" id="delete-project" type="button">Eliminar proyecto</button>
<dialog class="dct-dialog" id="delete-dialog" aria-labelledby="delete-title">
  <header class="dct-dialog-header"><h2 class="dct-dialog-title" id="delete-title">Eliminar proyecto</h2></header>
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

La aplicación llama a `showModal()`/`close()`, procesa `returnValue` y devuelve explícitamente el foco al disparador cuando se cierra. No sustituyas el elemento nativo por un `div` visual. Consulta la receta completa para el script requerido.

### Disclosure y accordion

`details.dct-disclosure` requiere `summary.dct-disclosure-summary` como primer hijo y `.dct-disclosure-content`. `open` fija el estado inicial. `.dct-accordion` agrupa uno o más disclosures, pero no impone exclusividad.

```html
<div class="dct-accordion">
  <details class="dct-disclosure" open>
    <summary class="dct-disclosure-summary">Requisitos</summary>
    <div class="dct-disclosure-content"><p>Permisos de administración.</p></div>
  </details>
</div>
```

No añadas roles, `tabindex` ni `aria-expanded` redundantes al `summary`. Para exclusividad nativa, comparte un `name` entre los `details` de un grupo y usa otro nombre para el siguiente. Sin soporte para `name`, los paneles funcionan de forma independiente. Consulta «Acordeones exclusivos por grupo» en `references/RECIPES.md`.

### Popover

`.dct-popover` requiere `popover`, `id` y un botón `popovertarget` que apunte a ese id. El navegador aporta apertura, cierre, foco y light dismiss básicos.

```html
<button class="dct-button" type="button" popovertarget="account-popover">Detalles</button>
<div class="dct-popover" id="account-popover" popover>
  <strong>Cuenta profesional</strong>
  <p>Renovación: 14 de agosto.</p>
</div>
```

La aplicación gestiona datos, acciones y apertura programática. No uses popover para confirmaciones modales ni lo presentes como `.dct-dropdown`, que no existe.

## Formularios

### Estructura y controles

- `.dct-field`: `div` con label, control y ayuda.
- `.dct-field-group`: `div` que distribuye fields.
- `fieldset.dct-fieldset`: requiere `legend.dct-legend`.
- `label.dct-label`: asocia `for` con el `id` del control.
- `.dct-help`: `p` o `div`; enlaza instrucciones necesarias con `aria-describedby`.
- `input.dct-input`: solo `text`, `email`, `password`, `search`, `url`, `tel`, `number`, `date`, `time`, `datetime-local`.
- `textarea.dct-textarea` y `select.dct-select`: controles nativos correspondientes.
- `label.dct-choice`: contiene directamente checkbox o radio; radios relacionados usan fieldset, legend y `name` común.

Usa `required`, `readonly` y `disabled`, no sustitutos visuales. La aplicación define nombres, autocomplete, validación cliente/servidor, envío y persistencia.

### Error y éxito

Aplica `.dct-is-error` o `.dct-is-success` a `.dct-field`, nunca al control. Error requiere `aria-invalid="true"`, `aria-describedby` y mensaje visible asociado. Éxito rutinario no requiere live region.

```html
<div class="dct-field dct-is-error">
  <label class="dct-label" for="email">Correo</label>
  <input class="dct-input" id="email" name="email" type="email" aria-invalid="true" aria-describedby="email-error">
  <p class="dct-help" id="email-error">Introduce una dirección válida.</p>
</div>
```

### Input group, switch y file

`.dct-input-group` requiere al menos `.dct-input` o `.dct-select`; `.dct-input-addon` solo va dentro del group y no sustituye label. Para un dominio parcial usa `type="text"`, porque `ejemplo.com` no es una URL absoluta:

```html
<div class="dct-field">
  <label class="dct-label" for="domain">Dominio del proyecto</label>
  <div class="dct-input-group">
    <span class="dct-input-addon">https://</span>
    <input class="dct-input" id="domain" name="domain" type="text" placeholder="ejemplo.com">
  </div>
</div>
```

`label.dct-switch` requiere un checkbox directo con `role="switch"`. `input.dct-file` solo admite `type="file"`; la aplicación comprueba formato, tamaño y contenido real.

```html
<label class="dct-switch"><input name="notifications" type="checkbox" role="switch"><span>Notificaciones</span></label>
<input class="dct-file" id="attachment" name="attachment" type="file" aria-label="Adjuntar archivo">
```

## Navegación

### Skip link, breadcrumb, tabs y paginación

`.dct-skip-link` es un enlace inicial a un destino existente y programáticamente enfocable.

```html
<a class="dct-skip-link" href="#main-content">Saltar al contenido</a>
<main id="main-content" tabindex="-1"><h1>Panel</h1></main>
```

`nav.dct-breadcrumb` requiere nombre accesible mediante `aria-label` o `aria-labelledby` y `ol.dct-breadcrumb-list`; cada `li.dct-breadcrumb-item` representa un nivel. Marca solo la página actual con `aria-current="page"`.

`nav.dct-tabs` requiere nombre mediante `aria-label` o `aria-labelledby` y `ul.dct-tabs-list`; `.dct-tab` solo se aplica a enlaces entre páginas. No uses roles de tab, `aria-selected` ni paneles dinámicos con esta API.

`nav.dct-pagination` requiere nombre mediante `aria-label` o `aria-labelledby` y `ul.dct-pagination-list`; `.dct-pagination-link` admite enlace o `span`. Usa `span aria-disabled="true"` para pasos no disponibles y `aria-current="page"` para la página actual. La aplicación genera URLs y datos.

### Navbar

`nav.dct-navbar` requiere nombre accesible mediante `aria-label` o `aria-labelledby`. `.dct-navbar-brand`, `.dct-navbar-toggle` y `.dct-navbar-menu` van dentro; `.dct-navbar-item` y `.dct-navbar-actions` van dentro del menú. El toggle requiere también `.dct-button`, `type="button"`, `aria-expanded` y `aria-controls` dirigido al `id` del menú. Aplica `.dct-is-inverse` únicamente al nav para una superficie oscura de alto contraste.

```html
<nav class="dct-navbar dct-is-inverse" aria-label="Navegación principal">
  <a class="dct-navbar-brand" href="/">Producto</a>
  <button class="dct-button dct-navbar-toggle" id="main-menu-toggle" type="button" hidden aria-expanded="true" aria-controls="main-menu">Menú</button>
  <div class="dct-navbar-menu" id="main-menu">
    <a class="dct-navbar-item" href="/dashboard" aria-current="page">Dashboard</a>
    <div class="dct-navbar-actions"><a class="dct-button dct-is-primary" href="/proyectos/nuevo">Crear proyecto</a></div>
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

El menú empieza visible y el toggle oculto para funcionar sin JavaScript. La aplicación sincroniza `hidden` y `aria-expanded` al iniciar, activar y cruzar el breakpoint; CSS no mantiene estado accesible.

La variante inversa cubre marca, enlaces, actual, botones, hover y foco. Si personalizas sus cuatro tokens, prueba todos esos estados en temas claro y oscuro; no intentes obtenerla sobrescribiendo selectores internos aislados.

Los botones loading conservan el texto accesible aunque esté oculto visualmente. Dentro de la navbar inversa, los spinners neutros y secundarios usan `--dct-navbar-inverse-text`; primary y danger conservan sus tokens de contraste propios. Mantén `disabled` y `aria-busy="true"` mientras la aplicación ejecuta la acción.

### Menu

`nav.dct-menu` requiere nombre accesible mediante `aria-label` o `aria-labelledby` y `ul.dct-menu-list`; cada `.dct-menu-item` es un enlace dentro de la lista. Usa `aria-current="page"` en un solo enlace. No uses botones ni conviertas el menú en dropdown.

## Utilidades

Display: `.dct-block`, `.dct-inline`, `.dct-flex`, `.dct-inline-flex`, `.dct-hidden`.

Texto: `.dct-text-start`, `.dct-text-center`, `.dct-text-end`, `.dct-text-muted`.

Gap: `.dct-gap-1`, `.dct-gap-2`, `.dct-gap-3`, `.dct-gap-4`, `.dct-gap-5`, `.dct-gap-6`.

Padding: `.dct-p-0`, `.dct-p-1`, `.dct-p-2`, `.dct-p-3`, `.dct-p-4`, `.dct-p-5`, `.dct-p-6`.

Margen lógico inferior: `.dct-mb-0`, `.dct-mb-1`, `.dct-mb-2`, `.dct-mb-3`, `.dct-mb-4`, `.dct-mb-5`, `.dct-mb-6`.

Flex: `.dct-items-center`, `.dct-justify-between`, `.dct-wrap`, `.dct-grow`; no establecen `display`.

Ancho y overflow: `.dct-w-full`, `.dct-max-w-full`, `.dct-overflow-auto`, `.dct-overflow-hidden`. Una región desplazable relevante debe ser enfocable y nombrada; no recortes controles o contenido necesario.

Accesibilidad: `.dct-sr-only` oculta visualmente sin retirar del árbol accesible. `.dct-hidden` usa `display: none` y no es equivalente.

Display responsive mobile-first:

- Desde `40rem`: `.dct-sm-block`, `.dct-sm-inline`, `.dct-sm-flex`, `.dct-sm-inline-flex`, `.dct-sm-hidden`.
- Desde `64rem`: `.dct-md-block`, `.dct-md-inline`, `.dct-md-flex`, `.dct-md-inline-flex`, `.dct-md-hidden`.
- Desde `80rem`: `.dct-lg-block`, `.dct-lg-inline`, `.dct-lg-flex`, `.dct-lg-inline-flex`, `.dct-lg-hidden`.

Solo display tiene una familia de utilidades responsive. El modificador de botón `.dct-is-fullwidth-mobile` es la única excepción específica de componente. Para mostrar desde `md`, combina `class="dct-hidden dct-md-block"`; para ocultar desde `md`, combina un display base con `.dct-md-hidden`.

## Atributos estables

Esta sección cubre exclusivamente atributos que dctcss requiere o restringe. No prohíbe otros atributos HTML válidos: conserva `type`, `name`, `autocomplete`, `action`, `accept` y cualquier otro atributo semántico necesario para el elemento y el flujo de aplicación.

- Tema: `data-dct-theme="light|dark"`.
- Densidad: `data-dct-density="compact"`; sin atributo se usa densidad cómoda.
- Estados nativos: `disabled`, `readonly`, `required`, `hidden`, `open`.
- Relaciones y alternativas: `href`, `id`, `for`, `alt`, `tabindex="0|-1"`.
- Nombres y descripciones: `aria-label`, `aria-labelledby`, `aria-describedby`, `aria-hidden="true"`.
- Estados ARIA: `aria-disabled="true"`, `aria-busy="true"`, `aria-invalid="true"`, `aria-current="page"`, `aria-expanded="true|false"`, `aria-controls`.
- Tablas: `scope="col|row"`; la aplicación puede mantener `aria-sort="ascending|descending|none|other"`.
- Roles usados por contratos: `role="alert|img|region|status|switch"`.
- Nativos avanzados: `method="dialog"`, `popover`, `popovertarget`, `max`, `value`.

No todos los atributos inventariados se aplican a todos los componentes. Sigue el contrato de cada sección, usa identificadores únicos y completa el HTML con sus atributos semánticos ordinarios.

## Responsabilidades y límites

- La aplicación valida y envía formularios, gestiona archivos, evita envíos repetidos y sincroniza estados dinámicos.
- La aplicación impide activación de enlaces disabled/loading, incluso desde handlers o código.
- La aplicación inserta y retira mensajes, actualiza progress, ordena/pagina/selecciona tablas y genera URLs actuales.
- La aplicación controla navbar, dialog y cualquier política programática de popover, incluido foco cuando corresponda.
- Usa HTML semántico, nombres accesibles y texto que no dependa solo del color.
- No elimines `:focus-visible` sin alternativa equivalente; prueba teclado, zoom, contenido largo, ambos temas y reduced motion.
- `dctcss-validate.php` comprueba contratos estáticos, pero no sustituye validación HTML, contraste, teclado, comportamiento JavaScript ni pruebas en navegador.
- No existe `.dct-dropdown`, no hay JavaScript distribuido y no existen familias de utilidades responsive fuera de display; `.dct-is-fullwidth-mobile` es un modificador exclusivo de botón.
- No existen los componentes estables `.dct-toolbar`, `.dct-page-header`, `.dct-kpi`, `.dct-panel`, `.dct-activity-list` ni `.dct-empty-state`. Usa las recetas `app-*` y no inventes equivalentes `.dct-*`.
- Métricas financieras, grids de filtros de doce columnas, cabeceras administrativas y barras de acciones sticky son composiciones de aplicación, no componentes del núcleo.
- No inventes aliases, modificadores, tokens, atributos `data-dct-*` ni utilidades fuera del inventario.

## Checklist obligatorio de salida

Antes de devolver código, comprueba:

- Toda clase, modificador, token y atributo `data-dct-*` aparece en `references/dctcss-api.json`.
- Cada parte tiene sus clases base, ancestros e hijos requeridos.
- Las acciones usan `button`; la navegación usa enlaces con `href` real.
- Existen labels, captions, legends, nombres accesibles y relaciones ARIA cuando corresponden.
- Los estados no dependen solo del color.
- `disabled`, `readonly`, `required`, `open` y otros estados nativos no se sustituyen por clases visuales.
- El contenido tolera móvil, zoom y texto largo.
- La aplicación implementa los comportamientos que CSS no puede mantener.
- El validador de contratos termina sin errores cuando está disponible, sin confundir ese resultado con una auditoría completa de accesibilidad.
- No se inventan dropdowns, JavaScript del framework ni variantes responsive fuera del inventario.
- Solo se enlaza una variante CSS.

## Inventario estable por categoría

Layout: `.dct-container`, `.dct-stack`, `.dct-cluster`, `.dct-grid`, `.dct-sidebar-layout`, `.dct-sidebar`, `.dct-main`.

Componentes y partes: `.dct-button`, `.dct-icon`, `.dct-content`, `.dct-display`, `.dct-title`, `.dct-subtitle`, `.dct-meta`, `.dct-message`, `.dct-message-icon`, `.dct-message-body`, `.dct-message-title`, `.dct-message-content`, `.dct-message-actions`, `.dct-card`, `.dct-card-media`, `.dct-card-body`, `.dct-card-title`, `.dct-card-subtitle`, `.dct-card-content`, `.dct-card-actions`, `.dct-table-wrap`, `.dct-table`, `.dct-table-caption`, `.dct-badge`, `.dct-progress`, `.dct-avatar`.

Nativos: `.dct-dialog`, `.dct-dialog-header`, `.dct-dialog-title`, `.dct-dialog-body`, `.dct-dialog-actions`, `.dct-accordion`, `.dct-disclosure`, `.dct-disclosure-summary`, `.dct-disclosure-content`, `.dct-popover`.

Formularios: `.dct-field`, `.dct-field-group`, `.dct-fieldset`, `.dct-legend`, `.dct-label`, `.dct-help`, `.dct-input`, `.dct-textarea`, `.dct-select`, `.dct-choice`, `.dct-input-group`, `.dct-input-addon`, `.dct-switch`, `.dct-file`.

Navegación: `.dct-skip-link`, `.dct-breadcrumb`, `.dct-breadcrumb-list`, `.dct-breadcrumb-item`, `.dct-tabs`, `.dct-tabs-list`, `.dct-tab`, `.dct-pagination`, `.dct-pagination-list`, `.dct-pagination-link`, `.dct-navbar`, `.dct-navbar-brand`, `.dct-navbar-toggle`, `.dct-navbar-menu`, `.dct-navbar-item`, `.dct-navbar-actions`, `.dct-menu`, `.dct-menu-list`, `.dct-menu-item`.

Modificadores: `.dct-is-primary`, `.dct-is-secondary`, `.dct-is-danger`, `.dct-is-info`, `.dct-is-warning`, `.dct-is-elevated`, `.dct-is-hover-elevated`, `.dct-is-inverse`, `.dct-is-small`, `.dct-is-medium`, `.dct-is-large`, `.dct-is-fullwidth`, `.dct-is-fullwidth-mobile`, `.dct-is-icon-only`, `.dct-is-loading`, `.dct-is-error`, `.dct-is-success`, `.dct-is-striped`, `.dct-is-hoverable`, `.dct-is-compact`, `.dct-is-numeric`, `.dct-is-selected`.

Las utilidades estables son exclusivamente las enumeradas en la sección Utilidades. La animación estable es `dct-spin`.
