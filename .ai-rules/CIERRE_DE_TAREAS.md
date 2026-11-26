# Cierre de tareas

## Ámbito

Este documento define el registro de ejecución que debe incluirse en la respuesta
final de las tareas de un proyecto web que seleccione expresamente este documento
en su `APP.md` raíz. Su objetivo es dejar constancia de las acciones, los recursos
utilizados y las comprobaciones reales. Las particularidades y excepciones se
declaran en [APP.md](../APP.md), conforme a [AGENTS.md](../AGENTS.md).

## Informe de cierre

Después de completar una tarea, incluidas preguntas informativas, revisiones y
cambios en archivos, presentar un registro con los siguientes apartados:

- **Resultado:** qué se ha completado y qué queda pendiente, si corresponde.
- **Acciones realizadas:** operaciones relevantes en orden de ejecución.
- **Documentos `.md` consultados:** rutas de los documentos leídos y aplicados.
  Distinguir los consultados durante la tarea de los consultados anteriormente y
  reutilizados desde el contexto.
- **Skills utilizados:** nombres de los skills realmente consultados. Indicar
  si se han reutilizado instrucciones consultadas anteriormente.
- **Ficheros afectados:** archivos creados, modificados o eliminados y resumen
  del cambio en cada uno. En revisiones, aclarar si se han modificado archivos o
  solo se han presentado hallazgos.
- **Herramientas utilizadas:** herramientas de lectura, edición y otras
  operaciones, así como herramientas de sistema y comandos ejecutados.
- **Herramientas de sistema no encontradas:** nombres de las herramientas cuya
  disponibilidad se haya comprobado y que no se hayan encontrado, comprobación
  realizada y tareas o validaciones afectadas. Distinguir una herramienta no
  encontrada de una encontrada que no se pueda ejecutar o que falle.
- **Scripts ejecutados:** ruta o nombre, argumentos relevantes y resultado.
- **Validaciones:** comprobaciones ejecutadas, resultado y limitaciones,
  incluidas las comprobaciones pendientes que afecten a la confianza en el
  resultado.

## Reglas del registro

- Mantener todos los apartados. Indicar `Ninguno`, `Ninguna` o `No ejecutadas`
  cuando corresponda, en lugar de omitirlos.
- No declarar acciones, comprobaciones ni recursos que no se hayan utilizado
  realmente.
- No indicar que una herramienta no está disponible si no se ha comprobado.
  Si no se ha comprobado la disponibilidad de herramientas de sistema, indicar
  `Disponibilidad no comprobada` en el apartado correspondiente.
- Separar las propuestas y recomendaciones de las acciones ejecutadas.
  Recomendar un comando o script no equivale a ejecutarlo.
- No registrar secretos, credenciales ni argumentos sensibles. Omitir o
  enmascarar esos valores si aparecen en una operación.
- Evitar duplicar detalles: un script puede describirse en su apartado y
  referenciarse desde las validaciones.
- Incluir el registro en la respuesta final. Guardarlo además en un archivo
  requiere una instrucción explícita o una regla documentada del proyecto.
