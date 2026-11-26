# Changelog

Todos los cambios relevantes de dctcss se documentan en este archivo.

## 1.6.5 - 2026-11-26

- La web enlaza al código fuente y a las releases de GitHub. Las descargas actuales siguen en el sitio oficial y el mismo build estático sirve para desarrollo y producción.
- Añadidos scripts para preparar las reglas compartidas como ficheros normales y desplegar desde la rama remota. El despliegue valida la configuración y la release antes de activar `public/`.

## 1.6.4 - 2026-11-26

- Añadidas dos recetas optativas: resumen adaptable al ancho del contenedor y acordeones exclusivos por grupo con `details[name]`. La web y la skill muestran cómo usarlas sin JavaScript.
- Documentados los motores probados, los mínimos declarados y la degradación prevista. Las pruebas verifican ambas recetas con teclado y el layout con texto al 200%. No cambia la API CSS ni suben los mínimos de navegador.

## 1.6.3 - 2026-11-26

- Los validadores de HTML y snippets rechazan nombres ARIA requeridos en blanco y elementos sin capacidad nativa de botón usados como único disparador de un popover.
- El build serializa las publicaciones sobre el mismo destino y conserva los permisos de los artefactos con una máscara de creación restrictiva. Las pruebas de tablas usan un directorio temporal por ejecución.

## 1.6.2 - 2026-10-09

- Protegidos los badges breves con `white-space: nowrap`; las etiquetas largas requieren espacio o una solución de aplicación sin ocultar contenido.
- Los encabezados y las celdas `.dct-is-numeric` restablecen `overflow-wrap: normal` frente a reglas heredadas como `body { overflow-wrap: anywhere; }`, conservando los saltos entre palabras en contenido mixto.
- El wrapper de tabla puede contraerse dentro de Grid/Flex y mantiene el desplazamiento horizontal. La receta documenta importes indivisibles sin imponer nowrap a descripciones ni a toda la tabla.
- Añadidas comprobaciones de CSS legible/minificado en Chromium, Firefox y WebKit a 320/375 px, texto y zoom CSS al 200%, etiquetas largas y desplazamiento por teclado.

## 1.6.1 - 2026-09-30

- Refinado `.dct-is-hover-elevated` para subir visualmente el botón 1 px durante hover, además de mostrar la sombra, sin modificar dimensiones ni el layout.
- Conservado el efecto de pulsación; disabled y loading siguen excluidos. Con `prefers-reduced-motion: reduce` se omite la subida y se mantiene la sombra.
- Actualizados los ejemplos de la web, la skill y las pruebas de hover, retorno al reposo, active y movimiento reducido sin ampliar la API pública.

## 1.6.0 - 2026-09-30

- Añadido `.dct-is-hover-elevated` para botones y enlaces `.dct-button`: muestra una sombra `--dct-shadow-sm` únicamente durante hover en dispositivos que lo admiten, sin modificar el tamaño ni el layout.
- El efecto compone con las variantes y tamaños existentes, excluye disabled y loading, conserva el foco visible y respeta los temas y la reducción de movimiento mediante la transición del botón.
- Actualizados el manifiesto, la skill, la web y las comprobaciones contractuales y de navegador. Esta ampliación es compatible con el HTML válido de versiones 1.x anteriores y no eleva los mínimos de navegador.

## 1.5.5 - 2026-09-30

- Corregidos los botones `.dct-is-loading` dentro de `.dct-navbar.dct-is-inverse`: el texto permanece oculto visualmente y los spinners neutros y secundarios usan el color de contraste de la navbar, conservando los colores propios de primary y danger.
- Corregido el botón de copia de la documentación para recuperar su etiqueta original después de varias activaciones consecutivas.
- Añadidos un ejemplo canónico de acciones en carga y comprobaciones de regresión para la navbar inversa y la copia repetida, sin ampliar la API pública ni los mínimos de compatibilidad.

## 1.5.4 - 2026-08-03

- Corregido el estiramiento vertical de controles en `.dct-field-group` cuando campos adyacentes tienen cantidades distintas de contenido auxiliar, conservando la altura natural de inputs y selects con ayuda, errores y texto largo.

## 1.5.3 - 2026-07-29

- Corregida la alineación vertical de `.dct-message-icon` con la primera línea de mensajes sin título, conservando la separación entre `.dct-message-title` y `.dct-message-content` y la alineación superior en contenido multilínea.

## 1.5.2 - 2026-07-28

- Corregido el reset de `box-sizing` para conservar su aplicación en `::before` y `::after` tanto en el CSS legible como en el minificado, con validación distributiva y funcional de regresión.

## 1.5.1 - 2026-07-28

- Corregida la validación de producción para rechazar de forma determinista hosts con punto final y rangos IP locales, privados, reservados, documentales o especiales, manteniendo IPv4 e IPv6 públicas.
- Corregido el build sobre un `OUTPUT_ROOT` alternativo ya generado y añadida una prueba de reconstrucción idempotente.
- Alineados los validadores interno y distribuido: los snippets rechazan clases, atributos `data-dct-*` y valores desconocidos, mientras `.dct-help` solo requiere `aria-describedby` cuando la ayuda aporta instrucciones necesarias.
- Añadidas matrices shell específicas para configuración y contratos, un probe funcional del CSS minificado y targets directos de Make para ejecutar las comprobaciones.
- Corregida documentación derivada desactualizada sin cambiar la API CSS pública de 1.5.

## 1.5.0 - 2026-07-28

- Añadida `.dct-navbar.dct-is-inverse` con tokens propios y estados accesibles para marca, enlaces, elemento actual, botones, hover y foco en temas claro y oscuro.
- Ampliado `.dct-is-elevated` a `.dct-table-wrap` para presentar tablas como superficies elevadas sin perder su región desplazable y enfocable.
- Publicado `dctcss-validate.php`, un validador de contratos estáticos para archivos y directorios HTML consumibles desde CI o por agentes.
- Añadidas recetas canónicas de shell administrativo, panel de filtros y formulario móvil con acciones sticky, todas con responsabilidades de aplicación explícitas.
- Ampliadas las comprobaciones automáticas de texto largo, teclado, contraste, zoom al 200 % y 400 %, temas y reducción de movimiento.
- Esta actualización es aditiva y conserva el HTML válido de versiones 1.x anteriores.

## 1.4.0 - 2026-07-27

- Añadida una escala tipográfica pública con `.dct-display`, `.dct-title`, `.dct-subtitle` y `.dct-meta`, aplicada también al contenido editorial y a títulos de componentes.
- Publicados nuevos tokens de tipografía, lectura, superficies, bordes, estados semánticos, controles, paneles, navegación, movimiento y elevación para una personalización visual coordinada.
- Añadido el contexto heredable `data-dct-density="compact"` sin sustituir el modo cómodo predeterminado ni el modificador de tabla compacta existente.
- Añadido `.dct-is-icon-only` para botones cuadrados accesibles y un catálogo copiable de seis SVG inline basados en `currentColor`.
- Refinados temas claro, oscuro y automático, estados interactivos, controles nativos, sombras deliberadas y reducción global de movimiento no esencial.
- Publicadas seis recetas canónicas de aplicación y una pantalla completa de referencia construida únicamente con API pública dctcss en su interior.
- Esta actualización es aditiva: no elimina marcado válido de 1.2.0, pero cambia de forma visible los estilos predeterminados.

## 1.2.0 - 2026-07-27

- Añadido `/release.json` para que aplicaciones, agentes y procesos CI descubran la versión pública actual, su fecha, el changelog y las descargas `latest`.
- Publicado el historial en `/changelog.html` a partir de esta autoridad, sin mantener una segunda copia editable.
- Incluido `CHANGELOG.md` en la distribución, los checksums y las referencias autocontenidas de la skill.
- Documentado un flujo de actualización deliberado: comparar versiones, revisar cambios y probar la aplicación antes de sustituir el CSS.

## 1.1.0 - 2026-07-27

- Corregidos y protegidos mediante tests contractuales los selectores de controles descendientes en `.dct-field.dct-is-error` y de `.dct-is-numeric` aplicado directamente a `th` o `td`.
- Añadido `.dct-is-fullwidth-mobile` para botones a ancho completo por debajo de `40rem` y ancho automático desde ese breakpoint.
- Documentados los SVG de relleno y trazado con `currentColor` sin imponer globalmente `fill` ni `stroke`.
- Añadida una receta semántica de lista compacta basada en `dl` y CSS de aplicación, sin promoverla todavía a componente estable.
- Actualizados manifiesto, skill, web pública, artefactos distribuibles y checksums. Al adoptar 1.1.0 pueden retirarse los overrides temporales para los selectores corregidos y el ancho móvil de botones.

## 1.0.0 - 2026-07-24

Lanzamiento inicial de dctcss.

- Framework CSS moderno nativo, sin dependencias externas de ejecución y con API pública prefijada mediante `dct-`.
- Tokens, temas, layouts, componentes semánticos, formularios, navegación y utilidades responsive.
- Artefactos CSS legible y minificado, manifiesto `schemaVersion: 2`, recetas, licencia MIT completa y checksums SHA-256.
- Web pública responsive generada en `public/`, descargas CSS de la última release, documentación operativa para agentes y validaciones reproducibles.
- Configuración explícita mediante `DCTCSS_ENV=development|production` y `SITE_URL`, con `check-release` obligatorio para aplicar las reglas de producción.
- Contratos estructurales y de accesibilidad verificables para navbar, tablas, formularios, progress, loading, dialog y popover.

Esta es la primera publicación. No existen versiones públicas anteriores ni una ruta de migración.
