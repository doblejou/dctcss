<?php

declare(strict_types=1);

$page = [
    'slug' => 'forms',
    'title' => 'Formularios',
    'description' => 'Prueba los campos de un perfil, elige cómo recibir novedades y compara un dato válido con otro que necesita corrección.',
    'index' => [
        'controles' => 'Datos del perfil',
        'elecciones' => 'Preferencias de correo',
        'validacion' => 'Mensajes de validación',
        'formulario-completo' => 'Solicitud de contacto',
    ],
    'indexLabel' => 'En esta página: Formularios',
];
$page['content'] = static function (): void {
?>
<header class="dct-stack">
  <h1 class="dct-display">Formularios</h1>
  <p>Prueba los campos de un perfil, elige cómo recibir novedades y compara un dato válido con otro que necesita corrección.</p>
</header>

<section class="dct-stack" id="controles" aria-labelledby="controles-title">
  <h2 id="controles-title">Datos del perfil</h2>
  <div class="dct-card">
    <div class="dct-card-body">
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
    </div>
  </div>
</section>

<section class="dct-stack" id="elecciones" aria-labelledby="elecciones-title">
  <h2 id="elecciones-title">Preferencias de correo</h2>
  <div class="dct-card">
    <div class="dct-card-body">
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
    </div>
  </div>
</section>

<section class="dct-stack" id="validacion" aria-labelledby="validacion-title">
  <h2 id="validacion-title">Mensajes de validación</h2>
  <div class="dct-card">
    <div class="dct-card-body">
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
    </div>
  </div>
</section>

<section class="dct-stack" id="formulario-completo" aria-labelledby="formulario-completo-title">
  <h2 id="formulario-completo-title">Solicitud de contacto</h2>
  <div class="dct-card">
    <div class="dct-card-body">
      <?= inert_form_preview(recipe_code_block('Formulario completo', 'html')) ?>
    </div>
  </div>
</section>
<?php
};
return $page;
