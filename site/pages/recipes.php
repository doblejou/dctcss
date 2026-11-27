<?php

declare(strict_types=1);

$page = [
    'slug' => 'recipes',
    'title' => 'Ejemplos de interfaz',
    'description' => 'Del componente a la pantalla: así se ven un panel de indicadores, una lista de actividad y un formulario de contacto.',
    'index' => [
        'dashboard' => 'Panel de indicadores',
        'actividad' => 'Actividad reciente',
        'formulario-completo' => 'Formulario de contacto',
        'resumen-contenedor' => 'Seguimiento de pedidos',
        'acordeones-exclusivos' => 'Preguntas por tema',
    ],
    'indexLabel' => 'En esta página: Ejemplos de interfaz',
];
$page['content'] = static function (): void {
?>
<section class="dct-stack" id="dashboard" aria-labelledby="dashboard-title">
  <h2 id="dashboard-title">Panel de indicadores</h2>
  <div class="dct-card">
    <div class="dct-card-body">
      <?= inert_html_preview(recipe_code_block('Dashboard básico con KPIs', 'html')) ?>
    </div>
  </div>
</section>

<section class="dct-stack" id="actividad" aria-labelledby="actividad-title">
  <h2 id="actividad-title">Actividad reciente</h2>
  <div class="dct-card">
    <div class="dct-card-body">
      <?= inert_html_preview(recipe_code_block('Lista de actividad', 'html')) ?>
    </div>
  </div>
</section>

<section class="dct-stack" id="formulario-completo" aria-labelledby="formulario-completo-title">
  <h2 id="formulario-completo-title">Formulario de contacto</h2>
  <div class="dct-card">
    <div class="dct-card-body">
      <?= inert_form_preview(recipe_code_block('Formulario completo', 'html')) ?>
    </div>
  </div>
</section>
<section class="dct-stack" id="resumen-contenedor" aria-labelledby="resumen-contenedor-title">
  <h2 id="resumen-contenedor-title">Seguimiento de pedidos</h2>
  <p>Compara las dos fichas: cuando hay espacio, los datos de entrega se reparten en dos columnas.</p>
  <?= recipe_code_block('Resumen por contenedor', 'html') ?>
</section>

<section class="dct-stack" id="acordeones-exclusivos" aria-labelledby="acordeones-exclusivos-title">
  <h2 id="acordeones-exclusivos-title">Preguntas por tema</h2>
  <p>Abre una pregunta de cada grupo para consultar las dos respuestas a la vez.</p>
  <?= recipe_code_block('Acordeones exclusivos por grupo', 'html') ?>
</section>
<?php
};
return $page;
