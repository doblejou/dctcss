<?php

declare(strict_types=1);

$page = array (
  'slug' => 'application',
  'title' => 'Aplicación de referencia',
  'description' => 'Aplicación de referencia de dctcss {{VERSION}} construida dentro de la muestra únicamente con API pública del framework.',
  'index' => array (
    'alcance' => 'Alcance de la muestra',
    'aplicacion-demo' => 'Aplicación completa',
  ),
  'indexLabel' => 'Índice de la aplicación de referencia',
);
$page['content'] = static function (string $version): void {
$reportFilters = <<<'HTML'
<form action="/informes" method="get">
  <fieldset class="dct-fieldset">
    <legend class="dct-legend">Periodo y responsable</legend>
    <div class="dct-field"><label class="dct-label" for="application-period">Periodo</label><select class="dct-select" id="application-period" name="period"><option value="today">Hoy</option><option value="week">Esta semana</option><option value="month">Este mes</option></select></div>
    <div class="dct-field"><label class="dct-label" for="application-owner">Responsable</label><input class="dct-input" id="application-owner" name="owner" type="search" placeholder="Nombre o equipo"></div>
    <label class="dct-switch"><input name="risk-only" type="checkbox" role="switch"><span>Mostrar solo pedidos con riesgo</span></label>
    <button class="dct-button dct-is-primary" type="submit">Aplicar filtros</button>
  </fieldset>
</form>
HTML;
?>
        <header class="site-doc-title dct-stack">
          <h1 class="dct-display">Aplicación de referencia</h1>
          <p>Explora un panel operativo construido con HTML y clases públicas de dctcss. El marco tramado delimita la demostración.</p>
        </header>

        <section class="site-reference" id="alcance" aria-labelledby="alcance-title">
          <h2 id="alcance-title">Qué permite evaluar</h2>
          <p>La pantalla reúne navegación, jerarquía tipográfica, KPIs compuestos, tabla, formulario, mensaje, empty state y dialog. Redimensiona el viewport y cambia la preferencia de color del sistema para revisar móvil, escritorio, claro y oscuro.</p>
          <div class="site-note"><strong>Separación deliberada</strong>La muestra interior no usa clases <code>site-*</code>, clases <code>app-*</code> ni estilos inline. Los KPI y el empty state se componen con primitives estables sin afirmar que existan componentes dedicados.</div>
        </section>

        <section class="site-reference" id="aplicacion-demo" aria-labelledby="aplicacion-demo-title">
          <h2 id="aplicacion-demo-title">Aplicación completa</h2>
          <div class="dct-card dct-is-elevated" id="application-preview">
            <div class="dct-card-body dct-p-4"><div class="dct-sidebar-layout">
              <aside class="dct-sidebar">
                <nav class="dct-menu" aria-label="Secciones de Acme Operaciones">
                  <ul class="dct-menu-list">
                    <li><a class="dct-menu-item" href="#aplicacion-demo" aria-current="page">Resumen</a></li>
                    <li><a class="dct-menu-item" href="#application-orders">Pedidos recientes</a></li>
                    <li><a class="dct-menu-item" href="#application-settings">Filtros del informe</a></li>
                  </ul>
                </nav>
              </aside>
              <div class="dct-main dct-stack">
              <nav class="dct-navbar dct-is-inverse" aria-label="Navegación de Acme Operaciones">
                <a class="dct-navbar-brand" href="#aplicacion-demo">Acme Operaciones</a>
                <div class="dct-navbar-menu">
                  <a class="dct-navbar-item" href="#aplicacion-demo" aria-current="page">Resumen</a>
                  <a class="dct-navbar-item" href="#application-orders">Pedidos</a>
                  <a class="dct-navbar-item" href="#application-settings">Ajustes</a>
                  <div class="dct-navbar-actions"><button class="dct-button dct-is-icon-only" type="button" aria-label="Buscar en la aplicación"><svg class="dct-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-4-4"></path></svg></button></div>
                </div>
              </nav>

              <div class="dct-container dct-stack">
                <header class="dct-stack">
                  <nav class="dct-breadcrumb" aria-label="Ruta de navegación"><ol class="dct-breadcrumb-list"><li class="dct-breadcrumb-item"><a href="#aplicacion-demo">Inicio</a></li><li class="dct-breadcrumb-item"><span aria-current="page">Resumen</span></li></ol></nav>
                  <div class="dct-cluster dct-justify-between">
                    <div class="dct-stack"><p class="dct-meta">Lunes, 27 de julio de 2026</p><h1 class="dct-display">Resumen operativo</h1><p class="dct-subtitle">Pedidos, capacidad y riesgos del equipo en una vista preparada para contenido real y etiquetas largas.</p></div>
                    <div class="dct-cluster"><button class="dct-button" id="application-dialog-trigger" type="button" aria-haspopup="dialog" aria-controls="application-dialog">Revisar publicación</button><a class="dct-button dct-is-primary" href="#application-settings">Nuevo pedido</a></div>
                  </div>
                </header>

                <section class="dct-grid" aria-label="Indicadores principales">
                  <article class="dct-card"><div class="dct-card-body"><p class="dct-meta">Ingresos confirmados</p><p class="dct-display">128 k EUR</p><p class="dct-card-content">Un 12 % más que el periodo anterior.</p></div></article>
                  <article class="dct-card"><div class="dct-card-body"><p class="dct-meta">Pedidos abiertos</p><p class="dct-display">42</p><p class="dct-card-content">Siete necesitan revisión antes de mañana.</p></div></article>
                  <article class="dct-card"><div class="dct-card-body"><p class="dct-meta">Entrega en plazo</p><p class="dct-display">94 %</p><p class="dct-card-content">Objetivo mensual: al menos 92 %.</p></div></article>
                </section>

                <aside class="dct-message dct-is-warning" aria-labelledby="application-warning-title"><span class="dct-message-icon" aria-hidden="true"><svg class="dct-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 2.5 20h19L12 3Z"></path><path d="M12 9v5m0 3h.01"></path></svg></span><div class="dct-message-body"><h2 class="dct-message-title" id="application-warning-title">Tres pedidos esperan confirmación de inventario</h2><p class="dct-message-content">Revisa las referencias de almacén antes de cerrar la preparación de esta tarde.</p><div class="dct-message-actions"><a class="dct-button dct-is-small" href="#application-orders">Ver pedidos afectados</a></div></div></aside>

                <section class="dct-stack" id="application-orders" aria-labelledby="application-orders-title">
                  <div class="dct-cluster dct-justify-between"><div class="dct-stack"><p class="dct-meta">Últimas 24 horas</p><h2 class="dct-title" id="application-orders-title">Pedidos recientes</h2></div><span class="dct-badge dct-is-info">42 abiertos</span></div>
                  <div class="dct-table-wrap" tabindex="0" role="region" aria-labelledby="application-orders-caption"><table class="dct-table dct-is-striped dct-is-hoverable"><caption class="dct-table-caption" id="application-orders-caption">Pedidos recientes y estado de preparación</caption><thead><tr><th scope="col">Pedido</th><th scope="col">Cliente</th><th scope="col">Responsable</th><th class="dct-is-numeric" scope="col">Importe</th><th scope="col">Estado</th></tr></thead><tbody><tr class="dct-is-success"><th scope="row">DCT-1042</th><td>Observatorio de Innovación Europea</td><td>Ada Lovelace</td><td class="dct-is-numeric">128,00 EUR</td><td>Completado</td></tr><tr class="dct-is-warning"><th scope="row">DCT-1043</th><td>Cooperativa del Norte</td><td>Grace Hopper</td><td class="dct-is-numeric">84,50 EUR</td><td>Pendiente de inventario</td></tr><tr><th scope="row">DCT-1044</th><td>Laboratorio Hamilton</td><td>Lin Chen</td><td class="dct-is-numeric">215,00 EUR</td><td>En preparación</td></tr></tbody></table></div>
                </section>

                <div class="dct-grid">
                   <article class="dct-card" id="application-settings" data-dct-density="compact" aria-labelledby="application-settings-title"><div class="dct-card-body"><h2 class="dct-card-title" id="application-settings-title">Filtros del informe</h2><?= inert_form_preview($reportFilters) ?></div></article>
                  <article class="dct-card" aria-labelledby="application-empty-title"><div class="dct-card-body"><svg class="dct-icon dct-is-large" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5v14"></path></svg><h2 class="dct-card-title" id="application-empty-title">No hay notas internas</h2><p class="dct-card-content">Añade contexto para que el siguiente turno conozca decisiones, dependencias y próximos pasos.</p><div class="dct-cluster dct-card-actions"><button class="dct-button dct-is-primary" type="button">Añadir primera nota</button></div></div></article>
                </div>
               </div>

                <dialog class="dct-dialog" id="application-dialog" aria-labelledby="application-dialog-title"><header class="dct-dialog-header"><h2 class="dct-dialog-title" id="application-dialog-title">Publicar el informe operativo</h2></header><div class="dct-dialog-body"><p>El informe se compartirá con 24 personas y conservará los filtros actuales.</p></div><form class="dct-dialog-actions" method="dialog"><button class="dct-button" type="submit" value="cancel">Cancelar</button><button class="dct-button dct-is-primary" type="submit" value="publish">Publicar informe</button></form></dialog>
              </div>
            </div></div>
          </div>
        </section>
<?php
};
return $page;
