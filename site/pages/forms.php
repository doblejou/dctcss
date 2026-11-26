<?php

declare(strict_types=1);

$page = array (
  'slug' => 'forms',
  'title' => 'Formularios',
  'description' => 'Referencia de formularios de dctcss {{VERSION}}: estructura, controles, estados, validación accesible y ejemplos copiables.',
  'index' => 
  array (
    'estructura' => 'Estructura',
    'controles' => 'Controles de texto',
    'tipos-input' => 'Tipos de input',
    'elecciones' => 'Checkbox y radio',
    'controles-avanzados' => 'Controles avanzados',
    'estados-nativos' => 'Estados nativos',
    'validacion' => 'Error y éxito',
    'formulario-completo' => 'Formulario completo',
    'responsabilidades' => 'Responsabilidades',
    'errores' => 'Errores frecuentes',
  ),
  'indexLabel' => 'Índice de formularios',
);
$page['content'] = static function (string $version): void {
?>
        <header class="site-doc-title dct-stack">
          <h1 class="dct-display">Formularios</h1>
          <p>Asocia controles con sus etiquetas, ayudas y mensajes de validación. Para compactar un formulario, aplica <code>data-dct-density="compact"</code> al contenedor.</p>
        </header>

        <section class="site-reference" id="estructura" aria-labelledby="estructura-title">
          <h2 id="estructura-title">Estructura del formulario</h2>
          <p><code>.dct-field</code> agrupa una etiqueta, su control y la ayuda relacionada. <code>.dct-field-group</code> distribuye varios fields en columnas autoajustables. Para un conjunto con un nombre común, usa <code>fieldset.dct-fieldset</code> y su primer <code>legend.dct-legend</code>.</p>
          <div class="site-note">
            <strong>Asociación obligatoria</strong>
            El valor de <code>for</code> en cada <code>.dct-label</code> debe coincidir con el <code>id</code> único de su control. Cada <code>.dct-help</code> lleva <code>id</code>; si la ayuda aporta instrucciones necesarias, enlázala con <code>aria-describedby</code>.
          </div>
          <?= html_example(
'structure',
<<<'HTML'
<fieldset class="dct-fieldset">
  <legend class="dct-legend">Datos de acceso</legend>
  <div class="dct-field-group">
    <div class="dct-field">
      <label class="dct-label" for="structure-user">Usuario</label>
      <input class="dct-input" id="structure-user" name="username" type="text" autocomplete="username">
    </div>
    <div class="dct-field">
      <label class="dct-label" for="structure-password">Contraseña</label>
      <input class="dct-input" id="structure-password" name="password" type="password" autocomplete="new-password" aria-describedby="structure-password-help">
      <p class="dct-help" id="structure-password-help">Usa al menos doce caracteres.</p>
    </div>
  </div>
</fieldset>
HTML,
'html de Estructura del formulario'
) ?>

          <h3>Inventario estructural</h3>
          <table class="site-api-table">
            <thead><tr><th scope="col">API</th><th scope="col">Elemento y uso</th></tr></thead>
            <tbody>
              <tr><th scope="row"><code>.dct-field</code></th><td><code>div</code> para un control, su label y su help.</td></tr>
              <tr><th scope="row"><code>.dct-field-group</code></th><td><code>div</code> para fields que forman una fila responsive.</td></tr>
              <tr><th scope="row"><code>.dct-fieldset</code></th><td><code>fieldset</code> para controles relacionados.</td></tr>
              <tr><th scope="row"><code>.dct-legend</code></th><td><code>legend</code> descendiente del fieldset; proporciona el nombre del grupo.</td></tr>
              <tr><th scope="row"><code>.dct-label</code></th><td><code>label</code> asociado al control mediante <code>for</code> e <code>id</code>.</td></tr>
              <tr><th scope="row"><code>.dct-help</code></th><td><code>p</code> o <code>div</code> para ayuda o mensajes de validación.</td></tr>
            </tbody>
          </table>
        </section>

        <section class="site-reference" id="controles" aria-labelledby="controles-title">
          <h2 id="controles-title">Input, textarea y select</h2>
          <p>Usa <code>.dct-input</code> para entrada de una línea, <code>.dct-textarea</code> para texto largo y <code>.dct-select</code> sobre el <code>select</code> nativo. No sustituyen labels, nombres, autocomplete ni opciones válidas.</p>
          <?= html_example(
'controls',
<<<'HTML'
<div class="dct-stack">
  <div class="dct-field">
    <label class="dct-label" for="control-email">Correo electrónico</label>
    <input class="dct-input" id="control-email" name="email" type="email" autocomplete="email" placeholder="nombre@ejemplo.com">
  </div>
  <div class="dct-field">
    <label class="dct-label" for="control-bio">Presentación</label>
    <textarea class="dct-textarea" id="control-bio" name="bio" rows="4" aria-describedby="control-bio-help"></textarea>
    <p class="dct-help" id="control-bio-help">Máximo 500 caracteres.</p>
  </div>
  <div class="dct-field">
    <label class="dct-label" for="control-country">País</label>
    <select class="dct-select" id="control-country" name="country">
      <option value="">Selecciona un país</option>
      <option value="es">España</option>
      <option value="mx">México</option>
    </select>
  </div>
</div>
HTML,
'html de Input, textarea y select'
) ?>
        </section>

        <section class="site-reference" id="tipos-input" aria-labelledby="tipos-input-title">
          <h2 id="tipos-input-title">Tipos permitidos para input</h2>
          <p><code>.dct-input</code> solo se publica para los siguientes valores de <code>type</code>. Elige el tipo que represente el dato para obtener teclado, validación y controles nativos adecuados.</p>
          <table class="site-api-table">
            <thead><tr><th scope="col">Tipo permitido</th><th scope="col">Uso habitual</th></tr></thead>
            <tbody>
              <tr><th scope="row"><code>text</code></th><td>Texto corto sin formato especializado.</td></tr>
              <tr><th scope="row"><code>email</code></th><td>Dirección de correo electrónico.</td></tr>
              <tr><th scope="row"><code>password</code></th><td>Contenido secreto oculto visualmente.</td></tr>
              <tr><th scope="row"><code>search</code></th><td>Consulta de búsqueda.</td></tr>
              <tr><th scope="row"><code>url</code></th><td>Dirección web absoluta.</td></tr>
              <tr><th scope="row"><code>tel</code></th><td>Número de teléfono.</td></tr>
              <tr><th scope="row"><code>number</code></th><td>Valor numérico con restricciones opcionales.</td></tr>
              <tr><th scope="row"><code>date</code></th><td>Fecha sin hora.</td></tr>
              <tr><th scope="row"><code>time</code></th><td>Hora sin fecha.</td></tr>
              <tr><th scope="row"><code>datetime-local</code></th><td>Fecha y hora local, sin zona horaria.</td></tr>
            </tbody>
          </table>
          <div class="site-note">
            <strong>Tipos no admitidos</strong>
            No apliques <code>.dct-input</code> a <code>checkbox</code>, <code>radio</code>, <code>file</code>, <code>range</code>, <code>color</code> o <code>hidden</code>. Usa <code>.dct-choice</code> para checkbox y radio, y <code>.dct-file</code> para archivos. Los otros tipos conservan su presentación nativa.
          </div>
        </section>

        <section class="site-reference" id="elecciones" aria-labelledby="elecciones-title">
          <h2 id="elecciones-title">Checkbox y grupos de radio</h2>
          <p><code>.dct-choice</code> se aplica a un <code>label</code> que contiene directamente un checkbox o radio. Un conjunto de radios necesita <code>fieldset</code>, <code>legend</code> y el mismo <code>name</code> para expresar una sola elección.</p>
          <?= html_example(
'choices',
<<<'HTML'
<div class="dct-stack">
  <label class="dct-choice">
    <input name="updates" type="checkbox">
    <span>Recibir novedades del producto</span>
  </label>
  <fieldset class="dct-fieldset">
    <legend class="dct-legend">Frecuencia del resumen</legend>
    <label class="dct-choice">
      <input name="frequency" type="radio" value="daily">
      <span>Diario</span>
    </label>
    <label class="dct-choice">
      <input name="frequency" type="radio" value="weekly" checked>
      <span>Semanal</span>
    </label>
  </fieldset>
</div>
HTML,
'html de Checkbox y grupos de radio'
) ?>
        </section>

        <section class="site-reference" id="controles-avanzados" aria-labelledby="controles-avanzados-title">
          <h2 id="controles-avanzados-title">Grupo, addon, switch y archivo</h2>
          <p><code>.dct-input-group</code> une visualmente inputs, selects y <code>.dct-input-addon</code>. El addon aporta contexto visible, pero no sustituye la etiqueta. <code>.dct-switch</code> requiere un checkbox directo con <code>role="switch"</code>. <code>.dct-file</code> se usa exclusivamente con <code>type="file"</code>.</p>
          <?= html_example(
'advanced',
<<<'HTML'
<div class="dct-stack">
  <div class="dct-field">
    <label class="dct-label" for="advanced-domain">Dominio del proyecto</label>
    <div class="dct-input-group">
      <span class="dct-input-addon">https://</span>
      <input class="dct-input" id="advanced-domain" name="domain" type="text" inputmode="url" placeholder="ejemplo.com">
    </div>
  </div>
  <label class="dct-switch">
    <input name="notifications" type="checkbox" role="switch" checked>
    <span>Activar notificaciones</span>
  </label>
  <div class="dct-field">
    <label class="dct-label" for="advanced-file">Adjuntar documento</label>
    <input class="dct-file" id="advanced-file" name="document" type="file" accept=".pdf,.txt" aria-describedby="advanced-file-help">
    <p class="dct-help" id="advanced-file-help">PDF o TXT de hasta 5 MB.</p>
  </div>
</div>
HTML,
'html de Grupo, addon, switch y archivo'
) ?>
        </section>

        <section class="site-reference" id="estados-nativos" aria-labelledby="estados-nativos-title">
          <h2 id="estados-nativos-title">Required, readonly y disabled</h2>
          <p>Usa los atributos HTML nativos: <code>required</code> participa en la validación, <code>readonly</code> conserva el valor en el envío sin permitir su edición y <code>disabled</code> impide la interacción y excluye el control del envío. No existen clases visuales equivalentes.</p>
          <?= html_example(
'native-states',
<<<'HTML'
<div class="dct-field-group">
  <div class="dct-field">
    <label class="dct-label" for="native-required">Nombre público</label>
    <input class="dct-input" id="native-required" name="display-name" type="text" required aria-describedby="native-required-help">
    <p class="dct-help" id="native-required-help">Campo obligatorio.</p>
  </div>
  <div class="dct-field">
    <label class="dct-label" for="native-readonly">Identificador</label>
    <input class="dct-input" id="native-readonly" name="account-id" type="text" value="DCT-1042" readonly>
  </div>
  <div class="dct-field">
    <label class="dct-label" for="native-disabled">Plan asignado</label>
    <select class="dct-select" id="native-disabled" name="plan" disabled>
      <option value="team" selected>Equipo</option>
    </select>
  </div>
</div>
HTML,
'html de Required, readonly y disabled'
) ?>
          <p>El atributo <code>required</code> comunica la obligatoriedad y el texto visible "Campo obligatorio" se asocia como descripción mediante <code>aria-describedby</code>. Si no aportas esa ayuda adicional, <code>required</code> basta por sí solo.</p>
        </section>

        <section class="site-reference" id="validacion" aria-labelledby="validacion-title">
          <h2 id="validacion-title">Error y éxito</h2>
          <p>Coloca <code>.dct-is-error</code> o <code>.dct-is-success</code> en <code>.dct-field</code>, nunca directamente en el control. El error requiere <code>aria-invalid="true"</code> y <code>aria-describedby</code> hacia un mensaje visible. El éxito no necesita ARIA si solo confirma visualmente que el valor es válido.</p>
          <?= html_example(
'validation',
<<<'HTML'
<div class="dct-field-group">
  <div class="dct-field dct-is-error">
    <label class="dct-label" for="validation-email">Correo electrónico</label>
    <input class="dct-input" id="validation-email" name="email" type="email" value="correo-invalido" aria-invalid="true" aria-describedby="validation-email-error">
    <p class="dct-help" id="validation-email-error">Introduce una dirección de correo válida.</p>
  </div>
  <div class="dct-field dct-is-success">
    <label class="dct-label" for="validation-user">Nombre de usuario</label>
    <input class="dct-input" id="validation-user" name="username" type="text" value="dct-user" aria-describedby="validation-user-success">
    <p class="dct-help" id="validation-user-success">Nombre disponible.</p>
  </div>
</div>
HTML,
'html de Error y éxito'
) ?>
          <div class="site-note">
            <strong>Validación dinámica</strong>
             Cuando la aplicación detecte un error, debe actualizar juntos el modificador, <code>aria-invalid</code>, la descripción asociada y el texto visible. Si un resultado de error o éxito aparece de forma asíncrona y necesita anunciarse, insértalo en una región <code>role="status"</code>; reserva <code>role="alert"</code> para errores urgentes. El color nunca es la única explicación.
          </div>
        </section>

        <section class="site-reference" id="formulario-completo" aria-labelledby="formulario-completo-title">
          <h2 id="formulario-completo-title">Formulario completo</h2>
          <p>Este ejemplo combina estructura, controles, ayuda, elección obligatoria y acción de envío. La documentación evita el envío de formularios dentro de las previews sin añadir atributos privados al HTML copiable.</p>
<?php $completeForm = recipe_code_block('Formulario completo', 'html'); ?>
          <?= html_example(
              'complete-form',
              $completeForm,
              'html de Formulario completo',
              inert_form_preview($completeForm),
          ) ?>
        </section>

        <section class="site-reference" id="responsabilidades" aria-labelledby="responsabilidades-title">
          <h2 id="responsabilidades-title">Responsabilidades de la aplicación</h2>
          <p>dctcss solo aplica presentación. La aplicación conserva el control del flujo y de los datos:</p>
          <ul>
            <li>Define <code>action</code>, <code>method</code>, nombres, valores iniciales y políticas de autocomplete.</li>
            <li>Valida datos en cliente cuando aporte valor y siempre vuelve a validarlos en el servidor.</li>
            <li>Sincroniza valores y estados dinámicos, incluidos <code>disabled</code>, <code>readonly</code>, <code>required</code> y <code>checked</code>.</li>
            <li>Inserta mensajes de error, mueve el foco cuando el flujo lo requiera y mantiene <code>aria-invalid</code> y <code>aria-describedby</code> coherentes.</li>
            <li>Envía el formulario, comunica progreso y resultado, gestiona archivos y protege contra envíos repetidos.</li>
            <li>Comprueba formato, tamaño y contenido real de los archivos; <code>accept</code> solo orienta al selector nativo.</li>
          </ul>
          <p>El framework no distribuye JavaScript para validación, cambio de valores, envío, subida de archivos ni mensajes dinámicos.</p>
        </section>

        <section class="site-reference" id="errores" aria-labelledby="errores-title">
          <h2 id="errores-title">Errores frecuentes y límites</h2>
          <ul>
            <li>No uses un placeholder como sustituto de <code>.dct-label</code>; desaparece al escribir y no proporciona una etiqueta persistente.</li>
            <li>No apliques <code>.dct-input</code> a checkbox, radio o file. Usa <code>.dct-choice</code> o <code>.dct-file</code> según corresponda.</li>
            <li>No apliques <code>.dct-is-error</code> o <code>.dct-is-success</code> directamente al control; pertenecen a <code>.dct-field</code>.</li>
            <li>No muestres error solo con color ni omitas <code>aria-invalid="true"</code>, <code>aria-describedby</code> y un mensaje visible asociado.</li>
            <li>No añadas ARIA de error a un field de éxito. El éxito rutinario no necesita <code>role="alert"</code> ni live region.</li>
            <li>No sustituyas <code>required</code>, <code>readonly</code> o <code>disabled</code> con clases visuales o <code>aria-disabled</code> en controles nativos.</li>
            <li>No agrupes radios relacionados sin <code>fieldset</code>, <code>legend</code> y un <code>name</code> compartido.</li>
            <li>No uses <code>.dct-input-addon</code> como única etiqueta del control.</li>
            <li>No uses un switch sin un checkbox directo y <code>role="switch"</code>.</li>
            <li>No atribuyas validación o envío automáticos a dctcss: los comportamientos JavaScript no forman parte de la API <?= escape($version) ?>.</li>
          </ul>
        </section>
<?php
};
return $page;
