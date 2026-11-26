# JavaScript

## Ámbito

Código JavaScript y sus dependencias en proyectos que seleccionen este documento
en su `APP.md` raíz. Las particularidades y excepciones se declaran en
[APP.md](../APP.md), conforme a [AGENTS.md](../AGENTS.md).

## Criterios de implementación

Utilizar únicamente el JavaScript razonablemente necesario.

Priorizar JavaScript nativo y las capacidades estándar del entorno de ejecución
antes que librerías externas.

No utilizar JavaScript por costumbre.

## Organización y claridad

- Organizar el código por responsabilidades claras. Centralizar los comportamientos
  compartidos en funciones o módulos reutilizables, evitando duplicarlos entre
  páginas, componentes o scripts.
- Comprobar si existe una solución adecuada en el proyecto antes de crear otra.
- Evitar variables globales y dependencias implícitas. Utilizar módulos nativos
  cuando el entorno lo permita, sin introducir un bundler por ese único motivo.
- Preferir funciones con nombres descriptivos y un flujo explícito frente a
  cadenas de operaciones, callbacks anidados o expresiones compactas difíciles
  de seguir.
- No fragmentar el código ni introducir abstracciones que añadan indirecciones
  sin mejorar su comprensión.

## Funcionalidades modernas y compatibilidad

Aprovechar las funcionalidades actuales del lenguaje y las API estándar del
entorno cuando reduzcan código o mejoren su claridad y mantenimiento.

- Respetar los navegadores o las versiones del entorno de ejecución definidos
  en `APP.md`. No asumir que una API del navegador está disponible en el servidor,
  ni a la inversa.
- Si el soporte requerido no está definido, aclararlo antes de depender de una
  funcionalidad reciente cuya compatibilidad condicione la solución.
- No introducir transpiladores ni polyfills únicamente para utilizar una novedad
  que pueda sustituirse por una solución sencilla compatible.

## Asincronía y errores

- Utilizar `async`/`await` cuando haga más claro el flujo, sin serializar operaciones
  independientes que puedan ejecutarse de forma concurrente.
- Gestionar los rechazos de promesas y los errores relevantes. No silenciarlos
  con bloques `catch` vacíos ni continuar como si la operación hubiera funcionado.
- En llamadas de red, distinguir los fallos de transporte de las respuestas de
  error del servicio; una respuesta HTTP de error no provoca por sí sola el
  rechazo de una promesa de `fetch`.
- Evitar que resultados asíncronos obsoletos sobrescriban el estado actual.
  Cancelar operaciones cuando dejen de ser necesarias si el entorno lo permite.

## Código en el navegador

Estas reglas se aplican únicamente al JavaScript ejecutado en el navegador.

### HTML y CSS antes que JavaScript

- Comprobar si HTML o CSS resuelven adecuadamente la necesidad antes de añadir
  comportamiento con JavaScript.
- No recrear controles, validaciones o interacciones que las capacidades nativas
  del navegador ya proporcionen de forma accesible y mantenible.
- Evitar mover lógica al cliente cuando deba resolverse en el servidor. Las
  comprobaciones del cliente no sustituyen la validación ni la autorización
  del servidor.

### Eventos y DOM

- Registrar eventos mediante `addEventListener`, en lugar de atributos de eventos
  en línea o de sobrescribir manejadores existentes.
- Limitar las búsquedas y modificaciones del DOM al componente o ámbito responsable,
  evitando cambios globales accidentales.
- Utilizar `textContent` para insertar texto. No insertar contenido externo mediante
  `innerHTML` sin un tratamiento de seguridad adecuado para ese contexto.
- Evitar registrar listeners duplicados y liberarlos cuando termine la vida del
  componente, si el mecanismo de gestión utilizado no lo hace automáticamente.
- Mantener accesibles los controles y el estado de la interfaz al modificar el DOM,
  incluido el foco cuando una interacción lo requiera.

### Estado de la interfaz

- Representar los estados de carga, éxito y error de las operaciones asíncronas.
- Evitar envíos duplicados y restaurar el estado de los controles cuando la
  operación termine o falle.

## Formato

- Utilizar Allman Style: colocar las llaves de apertura y cierre de bloques en
  líneas separadas.
- Dejar una línea en blanco después de cada cierre `}` antes del siguiente código,
  incluido el cierre de un bloque exterior.
- No dejar una línea en blanco antes de `else`, `catch` ni `finally`: deben seguir
  inmediatamente al cierre anterior, manteniendo sus llaves en líneas separadas.
- Aplicar Allman a los bloques, no indiscriminadamente a cualquier expresión con
  llaves. Respetar las restricciones sintácticas y la inserción automática de
  punto y coma: no separar `return` o `throw` de su expresión mediante un salto
  de línea. Al devolver un objeto multilínea, utilizar paréntesis abiertos en la
  misma línea que `return`. No insertar un salto de línea entre los parámetros
  de una función flecha y `=>`.

## Dependencias

Preferir soluciones sin dependencias externas. Antes de incorporar una librería:

- justificar su incorporación;
- consultar antes de añadir dependencias que introduzcan herramientas de build,
  servicios externos, cambios de arquitectura o un coste relevante de carga;
- para otras librerías, seguir la política documentada del proyecto. Si no existe
  una política que autorice su incorporación, consultar antes de añadirlas;
- reutilizar las dependencias existentes cuando cubran adecuadamente la necesidad;
- valorar siempre una solución sin dependencias.

## npm y procesos de build

No introducir npm, bundlers ni procesos de build salvo decisión explícita.

Si el proyecto ya utiliza npm, un bundler o un proceso de build, seguir sus
procedimientos documentados. No eliminarlos ni sustituirlos como parte de una
tarea que no lo requiera.

En proyectos sin un proceso de build autorizado, mantener JavaScript servible
como archivos estáticos.

Esta restricción afecta a la aplicación. No impide utilizar herramientas de
desarrollo externas o ya disponibles para validarla.
