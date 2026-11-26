<?php

declare(strict_types=1);

$page = [
    'slug' => 'navigation',
    'title' => 'Navegación',
    'description' => 'Tres maneras de orientarse: volver a una sección anterior, moverse entre apartados y recorrer una lista por páginas.',
    'index' => [
        'breadcrumb' => 'Ruta del proyecto',
        'tabs' => 'Apartados de la cuenta',
        'pagination' => 'Páginas de resultados',
    ],
    'indexLabel' => 'En esta página: Navegación',
];
$page['content'] = static function (): void {
?>
<header class="dct-stack">
  <h1 class="dct-display">Navegación</h1>
  <p>Tres maneras de orientarse: volver a una sección anterior, moverse entre apartados y recorrer una lista por páginas.</p>
</header>

<section class="dct-stack" id="breadcrumb" aria-labelledby="breadcrumb-title">
  <h2 id="breadcrumb-title">Ruta del proyecto</h2>
  <div class="dct-card">
    <div class="dct-card-body">
      <nav class="dct-breadcrumb" aria-label="Ruta de navegación">
        <ol class="dct-breadcrumb-list">
          <li class="dct-breadcrumb-item">
            <a href="#breadcrumb">Inicio</a></li>
          <li class="dct-breadcrumb-item">
            <a href="#breadcrumb">Proyectos</a></li>
          <li class="dct-breadcrumb-item">
            <span aria-current="page">Atlas</span></li>
        </ol>
      </nav>
    </div>
  </div>
</section>

<section class="dct-stack" id="tabs" aria-labelledby="tabs-title">
  <h2 id="tabs-title">Apartados de la cuenta</h2>
  <div class="dct-card">
    <div class="dct-card-body">
      <nav class="dct-tabs" aria-label="Secciones de cuenta">
        <ul class="dct-tabs-list">
          <li>
            <a class="dct-tab" href="#tabs" aria-current="page">Perfil</a></li>
          <li>
            <a class="dct-tab" href="#tabs">Seguridad</a></li>
          <li>
            <a class="dct-tab" href="#tabs">Notificaciones</a></li>
        </ul>
      </nav>
    </div>
  </div>
</section>

<section class="dct-stack" id="pagination" aria-labelledby="pagination-title">
  <h2 id="pagination-title">Páginas de resultados</h2>
  <div class="dct-card">
    <div class="dct-card-body">
      <nav class="dct-pagination" aria-label="Paginación de resultados">
        <ul class="dct-pagination-list">
          <li>
            <span class="dct-pagination-link" aria-disabled="true">Anterior</span></li>
          <li>
            <a class="dct-pagination-link" href="#pagination" aria-current="page">1</a></li>
          <li>
            <a class="dct-pagination-link" href="#pagination">2</a></li>
          <li>
            <a class="dct-pagination-link" href="#pagination">3</a></li>
          <li>
            <a class="dct-pagination-link" href="#pagination">Siguiente</a></li>
        </ul>
      </nav>
    </div>
  </div>
</section>
<?php
};
return $page;
