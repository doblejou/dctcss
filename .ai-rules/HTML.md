# HTML

## Ámbito

Documentos y marcado HTML de los proyectos que seleccionen este documento en su
`APP.md` raíz. Las particularidades y excepciones se declaran en
[APP.md](../APP.md), conforme a [AGENTS.md](../AGENTS.md).

## Semántica y accesibilidad

Utilizar HTML5 semántico y accesible.

- Elegir los elementos por su significado, no por su apariencia visual.
- Utilizar enlaces para navegar y botones para ejecutar acciones.
- Mantener una jerarquía coherente de encabezados que refleje la estructura del
  contenido.
- Evitar contenedores y anidaciones que no aporten estructura ni sean necesarios
  para la presentación o el comportamiento.
- Priorizar controles nativos que ya ofrezcan interacción por teclado. No
  sustituirlos por elementos genéricos con comportamiento recreado manualmente.
- Utilizar ARIA solo cuando la semántica nativa no sea suficiente, sin duplicarla
  ni contradecirla.

## Estructura del documento

- En documentos completos, incluir `<!doctype html>`, el atributo `lang`, la
  codificación mediante `meta charset`, un título descriptivo y `meta viewport`.
- No exigir esa estructura en fragmentos o plantillas parciales que se integren
  en un documento completo.
- Utilizar elementos como `main`, `header`, `nav`, `section`, `article` y `footer`
  cuando correspondan al significado del contenido.
- Mantener los identificadores `id` únicos dentro del documento final, también
  cuando se componga a partir de fragmentos reutilizables.

## SEO y marcado limpio

En páginas destinadas a indexación, orientar el HTML al SEO mediante contenido
comprensible y una estructura semántica clara, sin añadir marcado innecesario.

- Incluir un título único y descriptivo y una meta description pertinente para
  cada página indexable.
- Utilizar un encabezado principal `h1` que identifique el contenido de la página
  y una jerarquía lógica de encabezados, sin elegir sus niveles por el tamaño
  visual deseado.
- Mantener el contenido principal y los enlaces de navegación disponibles en
  el HTML entregado cuando sea posible, sin depender de JavaScript para descubrir
  información esencial. Utilizar enlaces con `href` y textos descriptivos.
- Añadir URL canónica, directivas de indexación y datos estructurados cuando
  correspondan a la estrategia del proyecto. Los datos estructurados deben
  describir fielmente el contenido visible.
- Mantener un árbol de elementos poco profundo: utilizar el mínimo de niveles
  de hijos necesario para expresar la estructura y el comportamiento.
- Evitar cadenas de contenedores que solo envuelvan otro contenedor, elementos
  vacíos y marcado duplicado sin una función concreta.
- No imponer una profundidad máxima arbitraria ni eliminar elementos necesarios
  para la semántica o la accesibilidad con el único objetivo de reducir niveles.
- Evitar contenido oculto destinado a buscadores y la repetición artificial de
  palabras clave.

## Formularios

- Asociar cada control que lo requiera a una etiqueta `label`, mediante `for` e
  `id` o mediante anidación adecuada. El placeholder no sustituye a la etiqueta.
- Utilizar los tipos de entrada y atributos nativos adecuados, como `required`,
  `min`, `max`, `pattern` y `autocomplete`, cuando correspondan.
- Declarar explícitamente el `type` de los botones para evitar envíos accidentales
  del formulario.
- Asociar las instrucciones y los mensajes de error a los controles afectados
  cuando sea necesario para su comprensión y accesibilidad.
- Aprovechar la validación nativa del navegador sin considerarla un sustituto de
  la validación en el servidor.

## Imágenes y tablas

- Proporcionar un texto alternativo adecuado para las imágenes informativas.
  Utilizar `alt=""` para las imágenes puramente decorativas.
- Utilizar tablas para datos tabulares, no para maquetar.
- Estructurar las tablas con `thead`, `tbody` y, cuando corresponda, `tfoot`.
  Utilizar `th`, `scope` y un `caption` cuando sean necesarios para identificar
  los encabezados, sus asociaciones y el propósito de la tabla.

## Capacidades modernas de HTML

Aprovechar al máximo las capacidades modernas de HTML5 y del estándar HTML
actual cuando permitan ahorrar CSS o JavaScript y simplificar la solución.
Antes de añadir estilos o comportamiento personalizado, comprobar si existe
un elemento o atributo nativo que cubra adecuadamente la necesidad.

- Preferir soluciones declarativas, como `details` y `summary` para contenido
  desplegable, o los atributos `popover` y sus controles para paneles emergentes,
  cuando correspondan al comportamiento requerido.
- Utilizar `dialog` para diálogos cuando resulte adecuado, aprovechando sus
  capacidades nativas en lugar de recrearlas. Añadir solo el JavaScript necesario
  cuando el comportamiento o la compatibilidad lo requieran.
- Aprovechar los tipos de entrada, la validación nativa y los atributos de
  imágenes y carga para evitar implementaciones personalizadas equivalentes.
- Comprobar la compatibilidad con los navegadores definidos en `APP.md`. Si no
  están definidos, aclarar el soporte requerido antes de depender de una capacidad
  reciente cuyo soporte pueda condicionar la solución.
- Adoptar novedades por su beneficio real en simplicidad, accesibilidad y
  mantenimiento, no únicamente por ser nuevas.

## Separación de responsabilidades

- Mantener la presentación en CSS; evitar estilos en línea salvo una necesidad
  concreta justificada.
- Evitar atributos de eventos en línea, como `onclick`. Cuando sea necesario
  JavaScript, conectar el comportamiento desde el código correspondiente.
- No añadir CSS o JavaScript para reproducir una capacidad que el HTML nativo
  ya resuelva adecuadamente.
