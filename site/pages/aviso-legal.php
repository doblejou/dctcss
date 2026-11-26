<?php

declare(strict_types=1);

$page = array();
$page['slug'] = 'aviso-legal';
$page['title'] = 'Aviso legal';
$page['description'] = 'Identificación del titular de dctcss, contacto y condiciones de reutilización del framework.';
$page['indexLabel'] = 'Índice del aviso legal';
$page['index'] = ['titular' => 'Titular y contacto', 'licencia' => 'Uso y licencia'];
$page['content'] = static function (): void
{
?>
        <header class="site-doc-title dct-stack">
          <h1 class="dct-display">Aviso legal</h1>
          <p>Información sobre el titular de dctcss y las condiciones de uso del proyecto.</p>
        </header>
        <section class="site-reference" id="titular" aria-labelledby="titular-title">
          <h2 id="titular-title">Titular y contacto</h2>
          <p>José González Mateo, profesional que opera bajo el nombre comercial Daycom Technologies. NIF: 40994604W. Domicilio de contacto: Pinar del Río 29, 08027 Barcelona, España.</p>
          <p>Para consultas o para comunicar una incidencia, escribe a <a href="mailto:jose.gm@daycomtech.com">jose.gm@daycomtech.com</a>.</p>
        </section>
        <section class="site-reference" id="licencia" aria-labelledby="licencia-title">
          <h2 id="licencia-title">Uso y licencia</h2>
          <p>El framework se distribuye bajo la licencia MIT. Puedes usarlo, modificarlo y redistribuirlo conservando el aviso de copyright y el texto de la licencia. Las marcas de terceros pertenecen a sus respectivos titulares.</p>
          <p>Los sitios enlazados tienen sus propias condiciones. Para saber cómo se tratan los datos en este sitio, consulta la <a href="privacidad.html">política de privacidad</a>.</p>
        </section>
<?php
};
return $page;
