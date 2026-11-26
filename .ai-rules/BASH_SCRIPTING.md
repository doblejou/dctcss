# Convenciones para scripts Bash

## Ámbito

Creación, modificación y revisión de scripts Bash en los proyectos que seleccionen
este documento en su `APP.md` raíz, incluidos scripts de proyectos de otros ámbitos.
Las particularidades y excepciones se declaran en [APP.md](../APP.md), conforme
a [AGENTS.md](../AGENTS.md).

## Versión y capacidades de Bash

- Utilizar Bash 5.2 como versión de referencia, salvo que `APP.md` indique otra.
- Aprovechar las capacidades de Bash 5.2 cuando simplifiquen el código y mejoren
  su claridad, como arrays indexados y asociativos, referencias a variables con
  `local -n`, expansión de parámetros y condicionales con `[[ ... ]]`.
- Preferir las capacidades internas de Bash frente a comandos externos cuando
  resuelvan adecuadamente la necesidad y eviten procesos o dependencias innecesarios.
- Respetar la versión y los sistemas compatibles definidos en `APP.md`. No exigir
  compatibilidad con `sh` ni utilizar funcionalidades únicamente por ser nuevas.

## Simplicidad y reutilización

- Cuando varios scripts de una misma carpeta compartan funcionalidad, intentar centralizarla en `common.inc` o `common.sh`, cargándolo mediante `source`.
- Mantener la lógica específica en cada script y extraer únicamente la funcionalidad realmente compartida.
- Resolver la ruta del archivo compartido respecto al script, utilizando
  `BASH_SOURCE` cuando corresponda, sin depender del directorio de ejecución.
- Evitar efectos secundarios inesperados al cargar el archivo compartido: no
  ejecutar operaciones de negocio ni cambiar el directorio, las opciones del
  intérprete o los traps del script consumidor de forma implícita.

## Variables, argumentos y rutas

- Entrecomillar las expansiones que no requieran separación de palabras o
  expansión de patrones, especialmente las rutas y los datos externos.
- Utilizar arrays para listas de argumentos y expandirlos con `"${argumentos[@]}"`.
  No construir una cadena de comando para ejecutarla con `eval`.
- Reenviar argumentos con `"$@"` para conservar sus límites y los valores vacíos.
- Declarar con `local` las variables internas de las funciones. Separar la
  declaración de una asignación mediante sustitución de comandos cuando sea
  necesario comprobar su código de salida.
- Validar los argumentos y las dependencias externas antes de ejecutar operaciones
  que los necesiten. Utilizar `--` para separar opciones de operandos cuando el
  comando lo admita y los valores puedan comenzar por un guion.
- Resolver las rutas respecto a una base explícita o documentar el directorio
  de trabajo requerido, sin depender accidentalmente del directorio de ejecución.

## Gestión de errores y códigos de salida

- Comprobar explícitamente los fallos relevantes y devolver un código distinto
  de cero cuando no se complete correctamente la operación solicitada.
- No confiar únicamente en `set -e`: su comportamiento depende del contexto.
  Si se utilizan pipelines, contemplar los fallos de sus comandos, mediante
  `pipefail` o comprobaciones explícitas según corresponda.
- No continuar con pasos que dependan de una operación fallida ni anunciar éxito
  después de un error no resuelto.
- Utilizar `return` en funciones reutilizables para comunicar errores; reservar
  `exit` para la terminación deliberada del script principal.

## Recursos temporales y limpieza

- Crear archivos o directorios temporales con `mktemp`, no con nombres predecibles.
- Utilizar `trap` para liberar los recursos temporales cuando el script termine,
  también por error o interrupción cuando corresponda.
- Limitar la limpieza a recursos creados por el script, validar las rutas antes
  de eliminarlas y conservar el código de salida que motivó la terminación.

## Formato del código

- Mantener una indentación consistente en todo el script.
- Hacer que el código respire y sea legible para una persona: usar líneas en blanco para separar funciones, bloques lógicos y pasos distintos del proceso.
- Evitar acumular instrucciones en líneas densas o bloques compactos. Añadir saltos de línea cuando ayuden a distinguir la estructura y seguir la lógica, respetando la prohibición de continuaciones con `\`.
- Usar siempre estilo Allman: colocar las llaves de apertura y cierre en líneas propias, alineadas con la declaración. Aplicarlo a todas las funciones.
- En las estructuras de control de Bash, colocar `then` y `do` en líneas propias y alinear `fi`, `done` y `esac` con la instrucción que abre la estructura.
- No usar una barra invertida (`\`) al final de una línea para continuar una instrucción en la siguiente. Simplificar los comandos demasiado complejos siguiendo KISS.

## Cabeceras y documentación

### Comentarios en el código

- Comentar el código dentro del script para explicar la finalidad de los bloques y las decisiones relevantes.
- Usar comentarios claros y didácticos que ayuden a entender la lógica, evitando repetir lo que ya expresa una instrucción evidente.
- Mantener los comentarios actualizados cuando cambie el código.

### Plantillas de cabecera

Conservar las líneas delimitadoras y las líneas de comentario vacías de las siguientes plantillas. Sustituir los textos descriptivos por información real.

#### Scripts

- Colocar la cabecera al inicio, inmediatamente después del shebang.
- Incluir comentarios didácticos que expliquen el objetivo del script, los parámetros aceptados y ejemplos de uso.
- Explicar para qué sirve cada parámetro y si es obligatorio u opcional. Omitir la sección de parámetros cuando no haya ninguno.
- Mantener esta documentación coherente con la ayuda de `--help`, cuando corresponda.

```bash
################################################
#
#  Nombre del script
#
#  Objetivo:
#  Explicación de qué hace el script y qué se pretende conseguir.
#
#  Parámetros:
#  Describir cada parámetro aceptado e indicar si es obligatorio u opcional.
#
#  Ejemplos de uso:
#  Mostrar invocaciones representativas del script.
#
################################################
```

#### Funciones

- Colocar un encabezado inmediatamente antes de cada función, con su nombre real y una explicación breve de qué hace.

```bash
################################################
#
#  Nombre función
#
#  Explicación breve de qué hace la función
#
################################################
```

## Plantillas para generar archivos

- Cuando un script genere archivos, intentar usar archivos de plantilla externos en lugar de incrustar su contenido en el código.
- Evitar los bloques heredoc, como `<<'FILEA' ... FILEA`, para incluir ese contenido. Mantener las plantillas separadas del script para facilitar su lectura y mantenimiento.
- Esta preferencia se aplica al contenido de archivos generados; no obliga a
  trasladar mensajes breves o textos de ayuda a archivos externos.

## Mensajes durante la ejecución

- Los scripts deben ser muy didácticos durante su ejecución: explicar con mensajes claros qué están haciendo y con qué finalidad.
- Anunciar los pasos relevantes e informar de su resultado, de los errores y de las acciones omitidas, cuando corresponda.
- Al finalizar, mostrar un resumen claro del resultado de la ejecución.
- Usar mensajes comprensibles que ayuden a seguir el proceso, evitando ruido repetitivo que no aporte información.
- Cuando otros comandos consuman el resultado del script, reservar stdout para
  esos datos y enviar el progreso, los diagnósticos y el resumen a stderr.
- Enviar los mensajes de error a stderr y no mostrar secretos en los diagnósticos.

## Parámetros y ayuda

- Preferir opciones largas y descriptivas con valores separados por un espacio,
  usando la forma `--clave valor`; por ejemplo, `--archivo datos.csv` o
  `--modo completo`.
- Para opciones booleanas, utilizar flags sin valor, como `--verbose`.
- Evitar opciones cortas y argumentos posicionales, salvo que `APP.md` indique
  otra convención o sea necesario mantener compatibilidad con una interfaz existente.
- Todos los scripts que acepten parámetros deben ofrecer la opción `--help`.
- La ayuda debe explicar el uso del script y los parámetros que acepta, distinguiendo los obligatorios de los opcionales.
- Para cada opción con valor, indicar el formato o tipo esperado, los valores
  admitidos y el valor predeterminado cuando exista. Bash recibe cadenas: validar
  explícitamente enteros, rutas, enumeraciones y otras restricciones necesarias.
- Rechazar opciones desconocidas y comprobar que cada opción que requiera valor
  lo reciba. Definir el comportamiento de opciones repetidas o incompatibles
  cuando el script las admita.
- Permitir consultar `--help` sin proporcionar los parámetros obligatorios.
- Terminar con código de salida cero al mostrar `--help`, sin ejecutar las
  operaciones del script ni exigir las dependencias que solo estas necesitan.
- Ante argumentos ausentes o inválidos, explicar el problema y mostrar la ayuda
  en stderr, terminando con un código de salida distinto de cero.

## Validación específica de Bash

- Comprobar la sintaxis de los scripts modificados con `bash -n`.
- Utilizar ShellCheck si está disponible o si lo exige `APP.md`. Resolver sus
  advertencias pertinentes y justificar las exclusiones necesarias.
- Al verificar la ejecución, utilizar entradas y recursos de prueba que permitan
  comprobar los errores y códigos de salida sin efectos destructivos sobre datos
  reales.
