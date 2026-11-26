<?php

declare(strict_types=1);

$page = [
    'slug' => 'layout',
    'title' => 'Distribución de página',
    'description' => 'Una misma pantalla puede reunir una cuadrícula de proyectos, una fila de acciones y ajustes en columna. Reduce el ancho para ver cómo se reorganizan.',
    'index' => [
        'grid' => 'Resumen de proyectos',
        'cluster' => 'Acciones en grupo',
        'stack' => 'Ajustes en columna',
    ],
    'indexLabel' => 'En esta página: Distribución de página',
];
$page['content'] = static function (): void {
?>
<header class="dct-stack">
  <h1 class="dct-display">Distribución de página</h1>
  <p>Una misma pantalla puede reunir una cuadrícula de proyectos, una fila de acciones y ajustes en columna. Reduce el ancho para ver cómo se reorganizan.</p>
</header>

<section class="dct-stack" id="grid" aria-labelledby="grid-title">
  <h2 id="grid-title">Resumen de proyectos</h2>
  <div class="dct-card">
    <div class="dct-card-body">
      <div class="dct-grid" style="--dct-grid-min: 12rem; --dct-grid-gap: var(--dct-space-4);">
        <article class="dct-card">
          <div class="dct-card-body">
            <p class="dct-card-subtitle">Activos</p>
            <h3 class="dct-card-title">12 proyectos</h3>
          </div>
        </article>
        <article class="dct-card">
          <div class="dct-card-body">
            <p class="dct-card-subtitle">Pendientes</p>
            <h3 class="dct-card-title">4 revisiones</h3>
          </div>
        </article>
        <article class="dct-card">
          <div class="dct-card-body">
            <p class="dct-card-subtitle">Equipo</p>
            <h3 class="dct-card-title">8 personas</h3>
          </div>
        </article>
      </div>
    </div>
  </div>
</section>

<section class="dct-stack" id="cluster" aria-labelledby="cluster-title">
  <h2 id="cluster-title">Acciones en grupo</h2>
  <div class="dct-card">
    <div class="dct-card-body">
      <div class="dct-cluster" style="--dct-cluster-gap: var(--dct-space-2); --dct-cluster-justify: space-between;">
        <strong>3 proyectos seleccionados</strong>
        <div class="dct-cluster" style="--dct-cluster-gap: var(--dct-space-2);">
          <button class="dct-button dct-is-primary" type="button">Mover</button>
          <button class="dct-button dct-is-danger" type="button">Eliminar</button>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="dct-stack" id="stack" aria-labelledby="stack-title">
  <h2 id="stack-title">Ajustes en columna</h2>
  <div class="dct-card">
    <div class="dct-card-body">
      <section class="dct-stack" style="--dct-stack-gap: var(--dct-space-5);">
        <h3>Preferencias de cuenta</h3>
        <p class="dct-text-muted">Revisa la información antes de guardar.</p>
        <div class="dct-field">
          <label class="dct-label" for="layout-name-preview">Nombre visible</label>
          <input class="dct-input" id="layout-name-preview" name="display-name" type="text" value="Ada Lovelace">
        </div>
        <button class="dct-button dct-is-primary" type="button">Guardar</button>
      </section>
    </div>
  </div>
</section>
<?php
};
return $page;
