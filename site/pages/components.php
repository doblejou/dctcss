<?php

declare(strict_types=1);

$page = [
    'slug' => 'components',
    'title' => 'Componentes CSS para un panel de proyectos',
    'description' => 'Botones, tarjetas e indicadores para un panel de proyectos. Abre los detalles o prueba el diálogo de confirmación.',
    'index' => [
        'button' => 'Acciones',
        'card' => 'Tarjeta de proyecto',
        'badge' => 'Estados de un proyecto',
        'progress' => 'Indicadores de progreso',
        'accordion' => 'Paneles desplegables',
        'dialog' => 'Confirmación',
    ],
    'indexLabel' => 'En esta página: Componentes',
];
$page['content'] = static function (): void {
?>
<section class="dct-stack" id="button" aria-labelledby="button-title">
  <h2 id="button-title">Acciones</h2>
  <div class="dct-card">
    <div class="dct-card-body">
      <div class="dct-cluster">
        <button class="dct-button" type="button">Cancelar</button>
        <button class="dct-button dct-is-primary" type="button">Guardar</button>
        <button class="dct-button dct-is-secondary" type="button">Vista previa</button>
        <button class="dct-button dct-is-danger" type="button">Eliminar</button>
      </div>
    </div>
  </div>
</section>

<section class="dct-stack" id="card" aria-labelledby="card-title">
  <h2 id="card-title">Tarjeta de proyecto</h2>
  <div class="dct-card">
    <div class="dct-card-body">
      <article class="dct-card dct-is-elevated">
        <figure class="dct-card-media">
          <svg viewBox="0 0 640 360" role="img" aria-label="Panel del proyecto Atlas">
            <rect width="640" height="360" fill="#10201d"></rect>
            <rect x="55" y="50" width="530" height="260" rx="18" fill="#78e2cd"></rect>
            <path d="M100 245l90-80 75 48 105-110 170 142" fill="none" stroke="#10201d" stroke-width="18"></path></svg>
        </figure>
        <div class="dct-card-body">
          <p class="dct-card-subtitle">Proyecto activo</p>
          <h3 class="dct-card-title">Atlas</h3>
          <p class="dct-card-content">Panel de seguimiento para el equipo de operaciones.</p>
          <div class="dct-cluster dct-card-actions">
            <a class="dct-button dct-is-primary" href="#card">Abrir proyecto</a>
            <button class="dct-button" type="button">Archivar</button>
          </div>
        </div>
      </article>
    </div>
  </div>
</section>

<section class="dct-stack" id="badge" aria-labelledby="badge-title">
  <h2 id="badge-title">Estados de un proyecto</h2>
  <div class="dct-card">
    <div class="dct-card-body">
      <div class="dct-cluster">
        <span class="dct-badge">Borrador</span>
        <span class="dct-badge dct-is-info">Informativo</span>
        <span class="dct-badge dct-is-success">Completado</span>
        <span class="dct-badge dct-is-warning">Pendiente</span>
        <span class="dct-badge dct-is-danger">Bloqueado</span>
      </div>
    </div>
  </div>
</section>

<section class="dct-stack" id="progress" aria-labelledby="progress-title">
  <h2 id="progress-title">Indicadores de progreso</h2>
  <div class="dct-card">
    <div class="dct-card-body">
      <label for="progress-base">Importación, 25 %</label>
      <progress class="dct-progress" id="progress-base" max="100" value="25" aria-label="Importación, 25 %">25 %</progress>
      <label for="progress-info">Análisis, 45 %</label>
      <progress class="dct-progress dct-is-info" id="progress-info" max="100" value="45" aria-label="Análisis, 45 %">45 %</progress>
      <label for="progress-success">Completado, 100 %</label>
      <progress class="dct-progress dct-is-success" id="progress-success" max="100" value="100" aria-label="Completado, 100 %">100 %</progress>
      <label for="progress-warning">Cuota utilizada, 75 %</label>
      <progress class="dct-progress dct-is-warning" id="progress-warning" max="100" value="75" aria-label="Cuota utilizada, 75 %">75 %</progress>
      <label for="progress-danger">Límite utilizado, 92 %</label>
      <progress class="dct-progress dct-is-danger" id="progress-danger" max="100" value="92" aria-label="Límite utilizado, 92 %">92 %</progress>
    </div>
  </div>
</section>

<section class="dct-stack" id="accordion" aria-labelledby="accordion-title">
  <h2 id="accordion-title">Paneles desplegables</h2>
  <div class="dct-card">
    <div class="dct-card-body">
      <div class="dct-accordion">
        <details class="dct-disclosure" open>
          <summary class="dct-disclosure-summary">Requisitos</summary>
          <div class="dct-disclosure-content">
            <p>Necesitas permisos de administración y una copia reciente.</p>
          </div>
        </details>
        <details class="dct-disclosure">
          <summary class="dct-disclosure-summary">Proceso</summary>
          <div class="dct-disclosure-content">
            <p>Ejecuta las comprobaciones antes de publicar.</p>
          </div>
        </details>
      </div>
    </div>
  </div>
</section>

<section class="dct-stack" id="dialog" aria-labelledby="dialog-title">
  <h2 id="dialog-title">Confirmación</h2>
  <div class="dct-card">
    <div class="dct-card-body">
      <button class="dct-button dct-is-danger" type="button" aria-haspopup="dialog" aria-controls="confirm-dialog">Eliminar proyecto</button>
      <dialog class="dct-dialog" id="confirm-dialog" aria-labelledby="confirm-dialog-title">
        <header class="dct-dialog-header">
          <h3 class="dct-dialog-title" id="confirm-dialog-title">Confirmar eliminación</h3></header>
        <div class="dct-dialog-body">
          <p>El proyecto Atlas y su historial se eliminarán de forma permanente.</p></div>
        <form class="dct-dialog-actions" method="dialog">
          <button class="dct-button" type="submit" value="cancel">Cancelar</button>
          <button class="dct-button dct-is-danger" type="submit" value="confirm">Eliminar</button>
        </form>
      </dialog>
    </div>
  </div>
</section>
<?php
};
return $page;
