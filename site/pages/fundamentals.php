<?php

declare(strict_types=1);

$page = [
    'slug' => 'fundamentals',
    'title' => 'Empezar con tu agente',
    'description' => 'Cuéntale a tu agente qué interfaz necesitas. La skill le enseña a elegir los componentes de dctcss y combinarlos en tu proyecto.',
    'index' => [
        'trabajar-con-ia' => 'Instalar la skill',
        'ejemplo' => 'Un encargo de ejemplo',
        'temas' => 'Claro y oscuro',
        'actualizar-con-ia' => 'Actualizar un proyecto',
    ],
    'indexLabel' => 'En esta página: Empezar con tu agente',
];
$page['content'] = static function (): void {
?>
<header class="dct-stack">
  <h1 class="dct-display">Empezar con tu agente</h1>
  <p>Cuéntale a tu agente qué interfaz necesitas. La skill le enseña a elegir los componentes de dctcss y combinarlos en tu proyecto.</p>
</header>

<section class="dct-stack" id="trabajar-con-ia" aria-labelledby="trabajar-con-ia-title">
  <h2 id="trabajar-con-ia-title">Instalar la skill</h2>
  <p>Abre tu proyecto y pega este mensaje en tu agente:</p>
  <?= agent_install_prompt() ?>
</section>

<section class="dct-stack" id="ejemplo" aria-labelledby="ejemplo-title">
  <h2 id="ejemplo-title">Un encargo de ejemplo</h2>
  <div class="dct-card">
    <div class="dct-card-body">
      <blockquote>Añade al formulario de facturación una ayuda que pueda abrir con el teclado para encontrar mi número de cliente.</blockquote>
      <details class="dct-disclosure">
        <summary class="dct-disclosure-summary">¿Dónde encuentro mi número de cliente?</summary>
        <div class="dct-disclosure-content">
          <p>Lo encontrarás en la primera página de tu factura, junto a la fecha de emisión.</p>
        </div>
      </details>
    </div>
  </div>
</section>

<section class="dct-stack" id="temas" aria-labelledby="temas-title">
  <h2 id="temas-title">Claro y oscuro</h2>
  <div class="dct-card">
    <div class="dct-card-body">
      <div class="dct-grid">
        <article class="dct-card" data-dct-theme="light">
          <div class="dct-card-body">
            <h3 class="dct-card-title">Tema claro</h3>
            <p class="dct-card-content">Entrega prevista para el jueves.</p>
          </div>
        </article>
        <article class="dct-card" data-dct-theme="dark">
          <div class="dct-card-body">
            <h3 class="dct-card-title">Tema oscuro</h3>
            <p class="dct-card-content">Entrega prevista para el jueves.</p>
          </div>
        </article>
      </div>
    </div>
  </div>
</section>

<section class="dct-stack" id="actualizar-con-ia" aria-labelledby="actualizar-con-ia-title">
  <h2 id="actualizar-con-ia-title">Actualizar un proyecto</h2>
  <?= code_example(
      'actualizar-con-agente',
      'Prompt de actualización',
      "Compara la versión de dctcss de mi proyecto con la publicada en dctcss.com/release.json.\n"
      . "Si ya está al día, indícamelo. Si hay una nueva, revisa el historial de cambios y dime si hay incompatibilidades antes de aplicarlas.\n"
      . "Actualiza el CSS y la skill a la misma versión en mi entorno de pruebas, conserva las personalizaciones y ejecuta las comprobaciones del proyecto.\n"
      . "Resume los cambios y el resultado de las pruebas sin desplegar a producción.",
      'prompt de actualización de dctcss'
  ) ?>
</section>
<?php
};
return $page;
