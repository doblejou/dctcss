# Filosofía técnica

## Ámbito

Este documento define criterios generales de implementación y mantenimiento para
los proyectos que lo seleccionen en su `APP.md` raíz, con independencia de las
tecnologías utilizadas.

El objetivo no es utilizar las tecnologías más modernas por defecto, sino las
que aporten un beneficio real al proyecto.

Las decisiones específicas y excepciones se registran en [APP.md](../APP.md),
cuyas reglas explícitas tienen prioridad sobre este documento conforme a
[AGENTS.md](../AGENTS.md). En lo no especificado, se aplican estas reglas.

## Principios fundamentales

La claridad, la modularidad, la centralización de la lógica compartida y la
filosofía KISS son prioridades de diseño y mantenimiento. Las decisiones técnicas
deben favorecer que una persona pueda entender, localizar y modificar el código
sin tener que reconstruir su funcionamiento a partir de piezas dispersas.

### KISS (Keep It Simple, Stupid)

La simplicidad tiene prioridad sobre la sofisticación.

Se prefiere:

- código fácil de leer;
- código fácil de depurar;
- código fácil de mantener;
- soluciones explícitas frente a soluciones "inteligentes".

Una línea adicional suele ser preferible a una expresión difícil de entender.

Elegir la solución más sencilla que resuelva la necesidad real. No añadir capas,
abstracciones, opciones de configuración ni mecanismos de extensión para
necesidades hipotéticas.

### Legibilidad

El código será leído muchas más veces de las que será escrito.

Por tanto:

- utilizar nombres descriptivos;
- evitar abreviaturas innecesarias;
- evitar anidaciones profundas;
- evitar efectos secundarios ocultos;
- escribir código que pueda entenderse sin comentarios.

El flujo de ejecución y las responsabilidades deben resultar evidentes al leer
el código. Evitar expresiones compactas o trucos que dificulten su comprensión.

### Modularidad

- Separar el código por responsabilidades claras y cohesionadas.
- Mantener funciones y módulos centrados en un propósito reconocible.
- Definir interfaces explícitas y minimizar el acoplamiento entre módulos.
- Evitar funciones o módulos que acumulen responsabilidades no relacionadas.
- No fragmentar el código en piezas pequeñas si ello dificulta seguir su lógica
  o añade indirecciones sin aportar claridad.

La modularidad debe simplificar la comprensión y el mantenimiento, no aumentar
el número de archivos o capas por sí mismo.

### Centralización y reutilización

- Mantener una única implementación de cada regla de negocio o comportamiento
  compartido, en el módulo responsable de esa funcionalidad.
- Antes de crear una función o componente, comprobar si el proyecto ya dispone
  de una solución adecuada y reutilizarla cuando corresponda.
- Evitar copiar lógica entre puntos de uso: una corrección del comportamiento
  compartido debe poder realizarse en un único lugar.
- Mantener los valores de configuración compartidos en una fuente de referencia
  clara, en lugar de repetirlos en distintos módulos.
- No concentrar funcionalidades no relacionadas en un módulo genérico de
  utilidades ni crear dependencias globales para compartir código.
- No unificar fragmentos solo porque se parezcan: compartir implementación cuando
  representen la misma responsabilidad y deban evolucionar juntos.

Centralizar la lógica compartida es compatible con distribuir responsabilidades
en módulos independientes.

### Control del software

Evitar, en la medida de lo posible, dependencias de terceros, como librerías,
paquetes o servicios externos. Se prioriza controlar el propio código y utilizar
las capacidades estándar de la plataforma.

Antes de incorporar una dependencia:

- comprobar si las capacidades estándar o el código existente resuelven la
  necesidad de forma sencilla;
- valorar una implementación propia cuando sea pequeña, clara y mantenible;
- evaluar el mantenimiento, la seguridad, el tamaño, el acoplamiento y el coste
  de sustituir o eliminar la dependencia;
- incorporarla solo cuando aporte un beneficio técnico claro que compense esos
  costes, conforme a la política del proyecto.

Evitar dependencias no significa reimplementar soluciones complejas o sensibles
cuando una dependencia mantenida reduzca claramente el riesgo y el coste total.

## Estilo de código

- Mantener una indentación consistente.
- Evitar líneas excesivamente largas.
- Priorizar claridad antes que minimizar líneas.
- Utilizar líneas vacías cuando ayuden a separar bloques y mejoren la legibilidad
  del código para una persona.
- Aplicar estas reglas al código propio. No reformatear dependencias,
  distribuciones minificadas ni artefactos generados.
- Las convenciones de formato específicas se definen en los documentos de ámbito
  seleccionados por el proyecto, con las excepciones indicadas en `APP.md`.

## Arquitectura

### Presentación

La capa de presentación debe limitarse a:

- presentación;
- pequeñas funciones auxiliares de presentación sin lógica de negocio.

La lógica de negocio debe permanecer fuera de la capa de presentación.

## Seguridad

Aplicar estos criterios cuando correspondan a la funcionalidad desarrollada:

- Validar las entradas en las fronteras de confianza. La validación realizada
  por un cliente no sustituye la del componente que procesa los datos.
- Comprobar los permisos en cada operación protegida y sobre el recurso concreto
  al que se accede. Ocultar controles en la interfaz no constituye autorización.
- Utilizar consultas parametrizadas para los valores introducidos en consultas
  de base de datos. No concatenar entradas del usuario en las consultas.
- Escapar o codificar la salida según su contexto. La validación de entrada no
  sustituye el tratamiento de salida.
- Proteger frente a solicitudes falsificadas las operaciones que cambien estado
  cuando la autenticación se base en credenciales enviadas automáticamente por
  el cliente.
- No incluir secretos en el código ni exponer credenciales o datos sensibles en
  registros y mensajes de error.
- Reutilizar los mecanismos de seguridad existentes del proyecto antes de crear
  implementaciones propias.
