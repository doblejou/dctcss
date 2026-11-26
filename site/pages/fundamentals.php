<?php

declare(strict_types=1);

$page = array (
  'slug' => 'fundamentals',
  'title' => 'Fundamentos',
  'description' => 'Fundamentos de dctcss {{VERSION}}: prefijo público, capas de cascada, tokens, personalización y temas claro y oscuro.',
  'index' => 
  array (
    'trabajar-con-ia' => 'Trabajar con IA',
    'prefijo' => 'Prefijo público',
    'capas' => 'Capas de cascada',
    'tokens' => 'Tokens públicos',
    'densidad' => 'Densidad',
    'personalizacion' => 'Personalización',
    'temas' => 'Temas',
    'accesibilidad' => 'Accesibilidad y límites',
  ),
  'indexLabel' => 'Índice de fundamentos',
);
$page['content'] = static function (string $version): void {
?>
        <header class="site-doc-title dct-stack">
          <p class="dct-meta">01 / Fundamentos</p>
          <h1 class="dct-display">Una base predecible antes de componer.</h1>
          <p>Aprende qué nombres pertenecen al framework, cómo participa dctcss en la cascada y qué contratos puedes personalizar sin editar el artefacto distribuido.</p>
        </header>

        <section class="site-reference" id="trabajar-con-ia" aria-labelledby="ia-title">
          <h2 id="ia-title">Trabajar con IA: de un encargo a una interfaz</h2>
          <p>dctcss está creado para proyectos donde un agente escribe o modifica HTML. Si le pides «añade una ayuda desplegable», hay dos decisiones distintas: qué elemento ofrece esa interacción y qué clases lo presentan. La skill orienta la primera decisión; el manifiesto enumera los nombres y las relaciones que admite esta versión. Así puedes revisar la respuesta contra reglas concretas.</p>
          <h3>1. Dale al agente las referencias de tu versión</h3>
          <p>El navegador solo necesita el CSS. La skill es el manual para el agente. OpenCode, Codex y Claude Code pueden descubrirla en el proyecto y cargarla cuando una tarea lo requiere. También puedes invocarla directamente:</p>
          <ul>
            <li><strong>OpenCode:</strong> instala la skill en <code>.agents/skills/dctcss/</code> e invócala con <code>@dctcss</code>.</li>
            <li><strong>Codex:</strong> usa la misma carpeta e invócala con <code>$dctcss</code>.</li>
            <li><strong>Claude Code:</strong> usa <code>.claude/skills/dctcss/</code> e invócala con <code>/dctcss</code>. Si compartes proyecto con Codex, esa ruta puede ser un enlace simbólico a la carpeta ya instalada.</li>
          </ul>
          <p>Otros agentes compatibles con el formato Agent Skills pueden usar la misma carpeta completa. Consulta en la documentación de tu agente dónde busca las skills de proyecto y cómo se activan.</p>
          <p>Para preparar un proyecto, ábrelo en tu agente y pega este encargo. El agente necesitará acceso a GitHub y permiso para escribir en el proyecto.</p>
          <?= code_example(
              'instalar-con-agente',
              'Instrucciones para tu agente',
              "Integra dctcss en este proyecto desde el repositorio doblejou/dctcss de GitHub.\n"
              . "Si ya usamos dctcss, conserva nuestra versión; si no, usa la etiqueta v$version.\n"
              . "De esa etiqueta, instala skills/dctcss/ completa en la raíz del proyecto: usa .agents/skills/dctcss/ para OpenCode o Codex y .claude/skills/dctcss/ para Claude Code. Si eres otro agente compatible con Agent Skills, usa tu ruta documentada de skills de proyecto.\n"
              . "Incluye SKILL.md, LICENSE y references/. Conserva los cambios locales si ya existe la carpeta.\n"
              . "Si el CSS aún no está integrado, guarda dist/dct.min.css y su licencia en los recursos estáticos del proyecto y enlaza el CSS una sola vez desde la plantilla principal.\n"
              . "Indícame qué versión quedó instalada, dónde se carga el CSS y cómo invocar la skill.",
              'instrucciones de instalación para el agente'
          ) ?>
          <h3>2. Pide un resultado que puedas probar</h3>
          <blockquote>Usa la skill dctcss. En el formulario de facturación, añade una ayuda titulada «¿Dónde encuentro mi número de cliente?». Debe empezar cerrada y poder abrirse con el teclado, sin JavaScript.</blockquote>
          <p>Prueba el resultado: lleva el foco a la pregunta con Tab y pulsa Intro para abrirla. El navegador gestiona el estado de <code>details</code>; dctcss da estilo al título y al contenido.</p>
          <?= html_example(
              'ayuda-facturacion',
              <<<'HTML'
<details class="dct-disclosure">
  <summary class="dct-disclosure-summary">¿Dónde encuentro mi número de cliente?</summary>
  <div class="dct-disclosure-content">
    <p>Lo encontrarás en la primera página de tu factura, junto a la fecha de emisión.</p>
  </div>
</details>
HTML,
              'HTML de la ayuda de facturación'
          ) ?>
          <h3>Aplica el mismo método a otra tarea</h3>
          <ul>
            <li><a href="recipes.html#tabla-responsive">Mostrar facturas en una tabla responsive</a>: conserva los encabezados y los importes al reducir el ancho.</li>
            <li><a href="recipes.html#formulario-completo">Componer un formulario</a>: relaciona cada control con su etiqueta, instrucciones y errores.</li>
            <li><a href="recipes.html#dashboard">Montar un panel de indicadores</a>: combina tarjetas y layout con datos de tu aplicación.</li>
          </ul>
        </section>

        <section class="site-reference" id="prefijo" aria-labelledby="prefijo-title">
          <h2 id="prefijo-title">Prefijo público</h2>
          <p>Toda API propiedad de dctcss empieza por <code>dct-</code>. Las clases usan <code>.dct-*</code>, las custom properties <code>--dct-*</code>, la animación pública se llama <code>dct-spin</code> y los atributos personalizados estables son <code>data-dct-theme</code> y <code>data-dct-density</code>.</p>
          <p>El prefijo permite reconocer el contrato y evita tratar una clase genérica de la aplicación como parte del framework. En la versión <?= escape($version) ?> usa solo nombres inventariados; <code>.button</code>, <code>.container</code>, <code>.active</code> y <code>.hidden</code> no son aliases de dctcss.</p>
          <?= html_example(
'prefix',
<<<'HTML'
<div class="dct-cluster">
  <button class="dct-button dct-is-primary" type="button">Guardar cambios</button>
  <a class="dct-button" href="#tokens">Consultar tokens</a>
</div>
HTML,
'html de Prefijo público'
) ?>
          <div class="site-note"><strong>Regla de decisión</strong>Combina siempre un modificador con una clase base que lo admita. Un nombre como <code>.dct-is-primary</code> aislado no define un componente.</div>
        </section>

        <section class="site-reference" id="capas" aria-labelledby="capas-title">
          <h2 id="capas-title">Capas de cascada</h2>
          <p>dctcss declara una sola vez este orden estable: reset mínimo, tokens, base, layout, componentes, utilidades y temas. La especificidad interna se mantiene baja con <code>:where()</code>; el orden de capas, y no selectores crecientes, organiza las responsabilidades.</p>
          <?= code_example(
'layers', 'CSS',
<<<'CODE'
@layer dct-reset, dct-tokens, dct-base, dct-layout, dct-components, dct-utilities, dct-themes;
CODE,
'css de Capas de cascada'
) ?>
          <p>Una aplicación puede declarar su propia capa después de cargar dctcss. No abras ni reordenes las capas internas del framework: son parte de su arquitectura, no puntos de extensión independientes.</p>
          <?= code_example(
'app-layer', 'CSS',
<<<'CODE'
@layer app {
  :root {
    --dct-color-accent: #7c3aed;
    --dct-color-focus: #5b21b6;
  }
}
CODE,
'css de Capas de cascada'
) ?>
          <div class="site-note"><strong>Límite</strong>No uses <code>!important</code> para competir con componentes. Personaliza tokens o escribe reglas propias después del framework con la mínima especificidad necesaria.</div>
        </section>

        <section class="site-reference" id="tokens" aria-labelledby="tokens-title">
          <h2 id="tokens-title">Tokens públicos</h2>
          <p>Los tokens siguientes forman el contrato configurable de <?= escape($version) ?>. Los tokens temáticos cambian entre claro y oscuro; los estructurales se heredan. Los tokens específicos de card y table se explican también junto a esos componentes.</p>

          <h3>Color</h3>
          <table class="site-api-table">
            <thead><tr><th scope="col">Token</th><th scope="col">Valor claro</th><th scope="col">Uso</th></tr></thead>
            <tbody>
              <tr><td><code>--dct-color-background</code></td><td><code>#f5f7f9</code></td><td>Fondo general del documento.</td></tr>
              <tr><td><code>--dct-color-surface</code></td><td><code>#ffffff</code></td><td>Superficies elevadas o delimitadas.</td></tr>
              <tr><td><code>--dct-color-surface-subtle</code></td><td><code>#f0f3f5</code></td><td>Regiones de contraste contenido.</td></tr>
              <tr><td><code>--dct-color-surface-raised</code></td><td><code>#ffffff</code></td><td>Superficies flotantes.</td></tr>
              <tr><td><code>--dct-color-surface-hover</code></td><td><code>#e7ecef</code></td><td>Hover de superficies interactivas.</td></tr>
              <tr><td><code>--dct-color-text</code></td><td><code>#17202a</code></td><td>Texto principal.</td></tr>
              <tr><td><code>--dct-color-text-muted</code></td><td><code>#5d6975</code></td><td>Texto secundario, no información deshabilitada.</td></tr>
              <tr><td><code>--dct-color-border</code></td><td><code>#7d8893</code></td><td>Bordes y divisores.</td></tr>
              <tr><td><code>--dct-color-border-subtle</code></td><td><code>#d4dbe0</code></td><td>Divisores de baja intensidad.</td></tr>
              <tr><td><code>--dct-color-border-strong</code></td><td><code>#596570</code></td><td>Contornos que requieren énfasis.</td></tr>
              <tr><td><code>--dct-color-accent</code></td><td><code>#006b5f</code></td><td>Enlaces y acciones principales.</td></tr>
              <tr><td><code>--dct-color-accent-hover</code></td><td><code>#00594f</code></td><td>Hover de acciones sólidas de acento.</td></tr>
              <tr><td><code>--dct-color-accent-soft</code></td><td><code>#dcefeb</code></td><td>Fondo tonal de acento.</td></tr>
              <tr><td><code>--dct-color-accent-contrast</code></td><td><code>#ffffff</code></td><td>Contenido colocado sobre el acento.</td></tr>
              <tr><td><code>--dct-color-danger</code></td><td><code>#b42318</code></td><td>Estados y acciones peligrosas.</td></tr>
              <tr><td><code>--dct-color-danger-hover</code></td><td><code>#931d14</code></td><td>Hover de acciones sólidas peligrosas.</td></tr>
              <tr><td><code>--dct-color-danger-soft</code></td><td><code>#fbe9e7</code></td><td>Fondo tonal de peligro.</td></tr>
              <tr><td><code>--dct-color-danger-contrast</code></td><td><code>#ffffff</code></td><td>Contenido colocado sobre peligro.</td></tr>
              <tr><td><code>--dct-color-success</code></td><td><code>#18794e</code></td><td>Confirmación satisfactoria.</td></tr>
              <tr><td><code>--dct-color-success-soft</code></td><td><code>#e1f1e9</code></td><td>Fondo tonal satisfactorio.</td></tr>
              <tr><td><code>--dct-color-success-contrast</code></td><td><code>#ffffff</code></td><td>Contenido sobre success.</td></tr>
              <tr><td><code>--dct-color-info</code></td><td><code>#08689f</code></td><td>Información contextual.</td></tr>
              <tr><td><code>--dct-color-info-soft</code></td><td><code>#e1f0f8</code></td><td>Fondo tonal informativo.</td></tr>
              <tr><td><code>--dct-color-info-contrast</code></td><td><code>#ffffff</code></td><td>Contenido sobre info.</td></tr>
              <tr><td><code>--dct-color-warning</code></td><td><code>#946200</code></td><td>Advertencias.</td></tr>
              <tr><td><code>--dct-color-warning-soft</code></td><td><code>#fff1ce</code></td><td>Fondo tonal de advertencia.</td></tr>
              <tr><td><code>--dct-color-warning-contrast</code></td><td><code>#211600</code></td><td>Contenido sobre warning.</td></tr>
              <tr><td><code>--dct-color-focus</code></td><td><code>#007f70</code></td><td>Indicador de <code>:focus-visible</code>.</td></tr>
            </tbody>
          </table>

          <h3>Tipografía</h3>
          <table class="site-api-table">
            <thead><tr><th scope="col">Token</th><th scope="col">Valor predeterminado</th><th scope="col">Uso</th></tr></thead>
            <tbody>
              <tr><td><code>--dct-font-family-sans</code></td><td><code>ui-sans-serif, system-ui, -apple-system, blinkmacsystemfont, "Segoe UI", sans-serif</code></td><td>Familia base sin descarga de fuentes.</td></tr>
              <tr><td><code>--dct-font-family-mono</code></td><td><code>ui-monospace, sfmono-regular, consolas, "Liberation Mono", monospace</code></td><td>Código y datos técnicos.</td></tr>
              <tr><td><code>--dct-font-size-sm</code></td><td><code>0.875rem</code></td><td>Metadatos y texto auxiliar.</td></tr>
              <tr><td><code>--dct-font-size-base</code></td><td><code>1rem</code></td><td>Tamaño raíz; conserva unidades relativas.</td></tr>
              <tr><td><code>--dct-font-size-lg</code></td><td><code>1.125rem</code></td><td>Introducciones y títulos menores.</td></tr>
              <tr><td><code>--dct-font-size-xl</code></td><td><code>1.25rem</code></td><td>Títulos de sección.</td></tr>
              <tr><td><code>--dct-font-size-2xl</code></td><td><code>1.75rem</code></td><td>Títulos principales.</td></tr>
              <tr><td><code>--dct-font-size-3xl</code></td><td><code>2.25rem</code></td><td>Display destacado.</td></tr>
              <tr><td><code>--dct-font-weight-normal</code></td><td><code>400</code></td><td>Texto general.</td></tr>
              <tr><td><code>--dct-font-weight-medium</code></td><td><code>600</code></td><td>Énfasis y controles.</td></tr>
              <tr><td><code>--dct-font-weight-bold</code></td><td><code>700</code></td><td>Títulos y énfasis fuerte.</td></tr>
              <tr><td><code>--dct-line-height-tight</code></td><td><code>1.2</code></td><td>Títulos.</td></tr>
              <tr><td><code>--dct-line-height-base</code></td><td><code>1.5</code></td><td>Interlineado base sin unidad.</td></tr>
              <tr><td><code>--dct-line-height-relaxed</code></td><td><code>1.7</code></td><td>Lectura prolongada.</td></tr>
              <tr><td><code>--dct-content-measure</code></td><td><code>70ch</code></td><td>Ancho recomendado de texto.</td></tr>
            </tbody>
          </table>

          <h3>Espaciado</h3>
          <table class="site-api-table">
            <thead><tr><th scope="col">Token</th><th scope="col">Valor</th><th scope="col">Escala</th></tr></thead>
            <tbody>
              <tr><td><code>--dct-space-1</code></td><td><code>0.25rem</code></td><td>Separación mínima.</td></tr>
              <tr><td><code>--dct-space-2</code></td><td><code>0.5rem</code></td><td>Separación pequeña.</td></tr>
              <tr><td><code>--dct-space-3</code></td><td><code>0.75rem</code></td><td>Separación compacta.</td></tr>
              <tr><td><code>--dct-space-4</code></td><td><code>1rem</code></td><td>Separación base.</td></tr>
              <tr><td><code>--dct-space-5</code></td><td><code>1.5rem</code></td><td>Separación amplia.</td></tr>
              <tr><td><code>--dct-space-6</code></td><td><code>2rem</code></td><td>Separación máxima de la escala.</td></tr>
            </tbody>
          </table>

          <h3>Radios, foco y elevación</h3>
          <table class="site-api-table">
            <thead><tr><th scope="col">Token</th><th scope="col">Valor</th><th scope="col">Uso</th></tr></thead>
            <tbody>
              <tr><td><code>--dct-radius-sm</code></td><td><code>0.25rem</code></td><td>Radio pequeño.</td></tr>
              <tr><td><code>--dct-radius-md</code></td><td><code>0.5rem</code></td><td>Radio habitual.</td></tr>
              <tr><td><code>--dct-radius-lg</code></td><td><code>0.875rem</code></td><td>Radio amplio.</td></tr>
              <tr><td><code>--dct-focus-width</code></td><td><code>0.1875rem</code></td><td>Grosor del foco visible.</td></tr>
              <tr><td><code>--dct-focus-offset</code></td><td><code>0.1875rem</code></td><td>Separación del foco visible.</td></tr>
              <tr><td><code>--dct-shadow-md</code></td><td><code>0 0.75rem 2rem rgb(23 32 42 / 0.14)</code></td><td>Elevación media; cambia en oscuro.</td></tr>
              <tr><td><code>--dct-shadow-sm</code></td><td><code>0 0.25rem 0.75rem rgb(23 32 42 / 0.12)</code></td><td>Popover y flotantes pequeños.</td></tr>
              <tr><td><code>--dct-shadow-lg</code></td><td><code>0 1.5rem 4rem rgb(23 32 42 / 0.2)</code></td><td>Dialog modal.</td></tr>
              <tr><td><code>--dct-duration-fast</code></td><td><code>120ms</code></td><td>Estados inmediatos.</td></tr>
              <tr><td><code>--dct-duration-normal</code></td><td><code>200ms</code></td><td>Transiciones contenidas.</td></tr>
              <tr><td><code>--dct-easing-standard</code></td><td><code>cubic-bezier(0.2, 0, 0, 1)</code></td><td>Ritmo compartido.</td></tr>
            </tbody>
          </table>

          <h3>Layout y componentes</h3>
          <table class="site-api-table">
            <thead><tr><th scope="col">Token</th><th scope="col">Valor predeterminado</th><th scope="col">Contexto</th></tr></thead>
            <tbody>
              <tr><td><code>--dct-container-max</code></td><td><code>72rem</code></td><td><code>.dct-container</code></td></tr>
              <tr><td><code>--dct-container-gutter</code></td><td><code>clamp(var(--dct-space-4), 4vw, var(--dct-space-6))</code></td><td><code>.dct-container</code></td></tr>
              <tr><td><code>--dct-stack-gap</code></td><td><code>var(--dct-space-4)</code></td><td><code>.dct-stack</code></td></tr>
              <tr><td><code>--dct-cluster-gap</code></td><td><code>var(--dct-space-3)</code></td><td><code>.dct-cluster</code></td></tr>
              <tr><td><code>--dct-cluster-align</code></td><td><code>center</code></td><td><code>.dct-cluster</code></td></tr>
              <tr><td><code>--dct-cluster-justify</code></td><td><code>flex-start</code></td><td><code>.dct-cluster</code></td></tr>
              <tr><td><code>--dct-grid-min</code></td><td><code>16rem</code></td><td><code>.dct-grid</code></td></tr>
              <tr><td><code>--dct-grid-gap</code></td><td><code>var(--dct-space-5)</code></td><td><code>.dct-grid</code></td></tr>
              <tr><td><code>--dct-card-padding</code></td><td><code>var(--dct-space-5)</code></td><td><code>.dct-card</code></td></tr>
              <tr><td><code>--dct-card-gap</code></td><td><code>var(--dct-space-3)</code></td><td><code>.dct-card</code></td></tr>
              <tr><td><code>--dct-card-media-ratio</code></td><td><code>16 / 9</code></td><td><code>.dct-card-media</code></td></tr>
              <tr><td><code>--dct-table-min</code></td><td><code>40rem</code></td><td><code>.dct-table</code></td></tr>
              <tr><td><code>--dct-table-cell-padding</code></td><td><code>var(--dct-space-3) var(--dct-space-4)</code></td><td><code>.dct-table</code></td></tr>
              <tr><td><code>--dct-control-height</code></td><td><code>2.75rem</code></td><td>Botones y controles.</td></tr>
              <tr><td><code>--dct-control-padding-block</code></td><td><code>0.625rem</code></td><td>Eje de bloque de controles.</td></tr>
              <tr><td><code>--dct-control-padding-inline</code></td><td><code>var(--dct-space-3)</code></td><td>Eje inline de controles.</td></tr>
              <tr><td><code>--dct-control-gap</code></td><td><code>var(--dct-space-2)</code></td><td>Icono y texto o controles próximos.</td></tr>
              <tr><td><code>--dct-panel-padding</code></td><td><code>var(--dct-space-5)</code></td><td>Cards, mensajes y regiones.</td></tr>
              <tr><td><code>--dct-navigation-padding-block</code></td><td><code>var(--dct-space-3)</code></td><td>Eje de bloque.</td></tr>
              <tr><td><code>--dct-navigation-padding-inline</code></td><td><code>var(--dct-space-4)</code></td><td>Eje inline.</td></tr>
              <tr><td><code>--dct-navbar-inverse-background</code></td><td><code>#10201d</code></td><td>Superficie de navbar inversa.</td></tr>
              <tr><td><code>--dct-navbar-inverse-text</code></td><td><code>#ffffff</code></td><td>Texto principal y foco inversos.</td></tr>
              <tr><td><code>--dct-navbar-inverse-text-muted</code></td><td><code>#c4d2ce</code></td><td>Enlaces secundarios inversos.</td></tr>
              <tr><td><code>--dct-navbar-inverse-hover</code></td><td><code>#243a35</code></td><td>Hover sobre superficie inversa.</td></tr>
            </tbody>
          </table>
          <div class="site-note"><strong>Contrato, no garantía automática</strong>Un token acepta valores CSS compatibles con la propiedad que alimenta. dctcss no valida valores ni crea aliases; prueba el resultado en contenido largo, zoom y ambos temas.</div>
        </section>

        <section class="site-reference" id="densidad" aria-labelledby="densidad-title">
          <h2 id="densidad-title">Densidad cómoda y compacta</h2>
          <p>Sin atributo se usa la densidad cómoda. <code>data-dct-density="compact"</code> sobrescribe por herencia los tokens de control, panel y navegación en el documento o subárbol elegido. No publica una familia de modificadores compactos por componente; <code>.dct-is-compact</code> continúa siendo exclusivo de table.</p>
          <?= html_example(
              'density',
              <<<'HTML'
<section class="dct-stack" data-dct-density="compact" aria-labelledby="density-title">
  <h2 class="dct-title" id="density-title">Ajustes compactos</h2>
  <div class="dct-field">
    <label class="dct-label" for="density-region">Región</label>
    <select class="dct-select" id="density-region" name="region"><option>Europa</option></select>
  </div>
  <button class="dct-button dct-is-primary" type="button">Guardar</button>
</section>
HTML,
              'html de densidad compacta'
          ) ?>
          <div class="site-note"><strong>Decisión</strong>Usa compact solo en interfaces con muchos controles y conserva el modo cómodo como predeterminado táctil. Prueba foco, zoom, contenido largo y ambos temas.</div>
        </section>

        <section class="site-reference" id="personalizacion" aria-labelledby="personalizacion-title">
          <h2 id="personalizacion-title">Personalización</h2>
          <p>Carga primero dctcss y después tu hoja de aplicación. Sobrescribe tokens en <code>:root</code> para una identidad global o en un contenedor propio para limitar el alcance. No edites <code>dist/dct.css</code>: es un artefacto generado.</p>
          <?= preview_example(
'custom',
'CSS',
<<<'HTML'
:root {
  --dct-color-accent: #7c3aed;
  --dct-color-accent-hover: #6d28d9;
  --dct-color-accent-soft: #ede9fe;
  --dct-color-accent-contrast: #ffffff;
  --dct-color-focus: #5b21b6;
  --dct-radius-md: 0.25rem;
}

.account-panel {
  --dct-stack-gap: var(--dct-space-6);
  --dct-card-padding: var(--dct-space-6);
}
HTML,
'css de Personalización',
<<<'HTML'
<div class="dct-stack" style="--dct-stack-gap: var(--dct-space-6);">
                <article class="dct-card" style="--dct-card-padding: var(--dct-space-6);">
                  <div class="dct-card-body">
                    <h3 class="dct-card-title">Contexto personalizado</h3>
                    <p class="dct-card-content">Los tokens se heredan y pueden ajustarse por instancia.</p>
                  </div>
                </article>
                <a class="dct-button dct-is-primary" href="#temas">Continuar con temas</a>
              </div>
HTML
) ?>
          <?= code_example(
'custom-html', 'HTML',
<<<'CODE'
<section class="account-panel dct-stack">
  <article class="dct-card">
    <div class="dct-card-body">
      <h2 class="dct-card-title">Contexto personalizado</h2>
      <p class="dct-card-content">Los tokens se heredan y pueden ajustarse por instancia.</p>
    </div>
  </article>
  <a class="dct-button dct-is-primary" href="/temas">Continuar con temas</a>
</section>
CODE,
'html de Personalización'
) ?>
          <p>Al cambiar colores, conserva contraste entre fondo, superficie, texto, acento y sus tokens de contraste. Personaliza también <code>--dct-color-focus</code>; un color de marca no garantiza por sí solo un foco perceptible.</p>
        </section>

        <section class="site-reference" id="temas" aria-labelledby="temas-title">
          <h2 id="temas-title">Temas claro y oscuro</h2>
          <p>Sin atributo, el tema es claro y <code>prefers-color-scheme: dark</code> activa los tokens oscuros del sistema. Para fijar una elección, coloca <code>data-dct-theme="light"</code> o <code>data-dct-theme="dark"</code> en <code>html</code> o en una sección. Los dos valores son la API completa.</p>
          <?= html_example(
'themes-html',
<<<'HTML'
<article class="dct-card" data-dct-theme="light">
  <div class="dct-card-body">
    <h2 class="dct-card-title">Tema claro</h2>
    <p class="dct-card-content">Este contexto usa los tokens claros.</p>
  </div>
</article>

<article class="dct-card" data-dct-theme="dark">
  <div class="dct-card-body">
    <h2 class="dct-card-title">Tema oscuro</h2>
    <p class="dct-card-content">Este contexto usa los tokens oscuros.</p>
  </div>
</article>
HTML,
'html de Temas claro y oscuro',
<<<'HTML'
<div class="dct-grid">
                <article class="dct-card" data-dct-theme="light">
                  <div class="dct-card-body">
                    <h3 class="dct-card-title">Tema claro</h3>
                    <p class="dct-card-content">Este contexto usa los tokens claros.</p>
                  </div>
                </article>
                <article class="dct-card" data-dct-theme="dark">
                  <div class="dct-card-body">
                    <h3 class="dct-card-title">Tema oscuro</h3>
                    <p class="dct-card-content">Este contexto usa los tokens oscuros.</p>
                  </div>
                </article>
              </div>
HTML
) ?>
          <p>Personaliza ambos temas con selectores que mantengan el contrato. Si la aplicación ofrece un selector de tema, ella cambia y persiste el atributo; dctcss no incluye JavaScript.</p>
          <?= code_example(
'themes-css', 'CSS',
<<<'CODE'
[data-dct-theme="light"] {
  --dct-color-background: #fffdf8;
  --dct-color-surface: #ffffff;
}

[data-dct-theme="dark"] {
  --dct-color-background: #15131a;
  --dct-color-surface: #211e27;
}
CODE,
'css de Temas claro y oscuro'
) ?>
          <div class="site-note"><strong>Límite</strong>No crees un tema copiando reglas completas de componentes. Cambia tokens, conserva la misma API HTML y verifica contraste en los dos contextos.</div>
        </section>

        <section class="site-reference" id="accesibilidad" aria-labelledby="accesibilidad-title">
          <h2 id="accesibilidad-title">Accesibilidad y límites</h2>
          <p>Los tokens y temas modifican presentación, no semántica. Usa elementos HTML apropiados, estados nativos y nombres accesibles; no comuniques estado solo mediante color.</p>
          <ul>
            <li>No elimines el <code>outline</code> de <code>:focus-visible</code> sin una alternativa equivalente.</li>
            <li>No reduzcas el tamaño base o el interlineado hasta perjudicar lectura y zoom.</li>
            <li>Prueba contraste de texto normal, texto atenuado, acento, peligro y foco sobre todas las superficies.</li>
            <li>Comprueba tema claro, oscuro, preferencia del sistema, zoom al 200 % y contenido traducido.</li>
            <li>No uses tokens o atributos parecidos que no figuren en el inventario; <code>data-dct-theme</code> solo admite <code>light</code> y <code>dark</code>.</li>
            <li>dctcss no publica <code>.dct-dropdown</code> ni comportamientos JavaScript.</li>
          </ul>
        </section>
<?php
};
return $page;
