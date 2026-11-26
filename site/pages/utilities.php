<?php

declare(strict_types=1);

$page = [
    'slug' => 'utilities',
    'title' => 'Detalles de diseño',
    'description' => 'La alineación, el espacio entre acciones y el reparto del ancho ayudan a leer una pantalla de un vistazo.',
    'index' => [
        'texto' => 'Alineación del texto',
        'gap' => 'Espacio entre acciones',
        'flex' => 'Distribución de elementos',
    ],
    'indexLabel' => 'En esta página: Detalles de diseño',
];
$page['content'] = static function (): void {
?>
<header class="dct-stack">
  <h1 class="dct-display">Detalles de diseño</h1>
  <p>La alineación, el espacio entre acciones y el reparto del ancho ayudan a leer una pantalla de un vistazo.</p>
</header>

<section class="dct-stack" id="texto" aria-labelledby="texto-title">
  <h2 id="texto-title">Alineación del texto</h2>
  <div class="dct-card">
    <div class="dct-card-body">
      <p class="dct-text-start">Inicio lógico</p>
      <p class="dct-text-center">Texto centrado</p>
      <p class="dct-text-end">Final lógico</p>
      <p class="dct-text-muted">Información secundaria</p>
    </div>
  </div>
</section>

<section class="dct-stack" id="gap" aria-labelledby="gap-title">
  <h2 id="gap-title">Espacio entre acciones</h2>
  <div class="dct-card">
    <div class="dct-card-body">
      <div class="dct-flex dct-gap-4 dct-wrap">
        <button class="dct-button" type="button">Cancelar</button>
        <button class="dct-button dct-is-primary" type="button">Guardar</button>
      </div>
    </div>
  </div>
</section>

<section class="dct-stack" id="flex" aria-labelledby="flex-title">
  <h2 id="flex-title">Distribución de elementos</h2>
  <div class="dct-card">
    <div class="dct-card-body">
      <div class="dct-flex dct-items-center dct-justify-between dct-wrap dct-gap-3">
        <div class="dct-grow">
          <strong>Proyecto Atlas</strong>
          <span class="dct-block dct-text-muted">Actualizado hoy</span>
        </div>
        <button class="dct-button" type="button">Abrir</button>
      </div>
    </div>
  </div>
</section>
<?php
};
return $page;
