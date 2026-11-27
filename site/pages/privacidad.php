<?php

declare(strict_types=1);

$page = array();
$page['slug'] = 'privacidad';
$page['title'] = 'Política de privacidad';
$page['description'] = 'Información sobre navegación, cookies, consultas y ejercicio de derechos en el sitio de dctcss.';
$page['indexLabel'] = 'Índice de privacidad';
$page['index'] = ['navegacion' => 'Navegación y cookies', 'contacto' => 'Consultas y derechos'];
$page['intro'] = static function (): void
{
?>
          <p>El responsable del tratamiento es José González Mateo (Daycom Technologies). Sus datos de contacto figuran en el <a href="aviso-legal.html#titular">aviso legal</a>.</p>
<?php
};
$page['content'] = static function (): void
{
?>
        <section class="dct-content" id="navegacion" aria-labelledby="navegacion-title">
          <h2 id="navegacion-title">Navegación y cookies</h2>
          <p>Las páginas de documentación no instalan cookies analíticas ni publicitarias. Copiar un ejemplo o un prompt se realiza en tu navegador; el sitio no recibe el contenido de tu portapapeles.</p>
          <p>Al abrir una página o descargar el CSS, el servidor recibe tu dirección IP, la ruta solicitada y las cabeceras del navegador. Estos datos técnicos permiten servir el contenido y atender incidencias por interés legítimo en el funcionamiento y la seguridad del sitio.</p>
          <p>Los proveedores de alojamiento y correo pueden tratar los datos necesarios para prestar sus servicios. Si sigues un enlace a GitHub o a la web de Daycom Technologies, se aplica la política del sitio de destino.</p>
        </section>
        <section class="dct-content" id="contacto" aria-labelledby="contacto-title">
          <h2 id="contacto-title">Consultas y derechos</h2>
          <p>Si escribes al titular, se utilizarán tu dirección y el contenido del mensaje para responderte. La base es el interés legítimo en atender tu consulta. Se conservarán durante el tiempo necesario para su seguimiento y, cuando corresponda, para cumplir obligaciones legales.</p>
          <p>Puedes solicitar acceso, rectificación, supresión, oposición, limitación o portabilidad, cuando proceda. Indica el derecho que deseas ejercer a <a href="mailto:jose.gm@daycomtech.com">jose.gm@daycomtech.com</a>. También puedes reclamar ante la Agencia Española de Protección de Datos.</p>
        </section>
<?php
};
return $page;
