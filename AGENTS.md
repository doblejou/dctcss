# Instrucciones de desarrollo

Este proyecto utiliza OpenCode. Este fichero es el punto de entrada de sus reglas
específicas, que complementan las instrucciones de OpenCode.

## Lectura y aplicación

Leer el `APP.md` raíz y los documentos enumerados en su sección «Documentos
aplicables». Las rutas son relativas a la raíz del proyecto.

Aplicar las particularidades y excepciones de `APP.md`, y cada documento
seleccionado únicamente dentro de su ámbito. Un proyecto puede combinar varios
ámbitos, como webapp y Bash.

La presencia de un documento en `.ai-rules/` no lo activa: debe estar seleccionado
en `APP.md`. Si falta `APP.md` o un documento seleccionado, señalar la referencia
ausente y no inferir su contenido.

## Precedencia

- Respetar siempre las instrucciones de mayor prioridad de OpenCode.
- Ante un conflicto entre `APP.md` y una regla compartida, prevalece `APP.md`.
- Las reglas compartidas no sobrescritas siguen vigentes.
- El orden de los documentos seleccionados no establece prioridad.
- Si dos documentos compartidos presentan un conflicto que `APP.md` no resuelve,
  solicitar aclaración para resolverlo en `APP.md`.

## Personalización

No modificar `.ai-rules/` ni este fichero para adaptar las reglas al proyecto.
Registrar la selección, las particularidades y las excepciones en el `APP.md`
raíz. Las actualizaciones de los documentos compartidos proceden de su repositorio
de origen.
