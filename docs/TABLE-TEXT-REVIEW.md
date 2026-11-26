# Resistencia de tablas y badges al ajuste heredado

Revisión: 2026-10-09. Corrección resultante: dctcss 1.6.2.

## Diagnóstico

`overflow-wrap` se hereda. `body { overflow-wrap: anywhere; }` permite
cortes arbitrarios y reduce la anchura min-content del texto. El algoritmo
automático de tablas puede entonces estrechar columnas hasta fragmentar palabras
y cifras. `overflow-x: auto` no obliga a la tabla a crecer: solo ofrece scroll
cuando existe desbordamiento.

La tabla de 1.6.1 ya tiene un mínimo de 40rem y un wrapper desplazable. Con ese
mínimo y los datos iniciales no se reprodujo la fragmentación de «Activo» ni de
los encabezados breves; sí la de badges largos y la herencia de `anywhere` en
celdas numéricas. Al modelar una integración que reduce `--dct-table-min` a cero,
se reprodujo la compresión completa. Esto demuestra una condición suficiente,
no que GestProp necesariamente cambie ese token: habría que inspeccionar sus
estilos computados, columnas, contenido y reglas de anchura para identificar
su condición concreta.

En Chromium a 320 px, con ese mínimo reducido y el comportamiento de texto
anterior, `-999.999.999.999,99` ocupaba 10 líneas, «Activo» 5, «Importe» 4 y
«Estado» 2; el wrapper no tenía exceso de anchura que desplazar. Con las
protecciones de 1.6.2 esos valores ocupan una línea y el wrapper tiene scroll.

## Decisiones del framework

| Elemento | Protección | Límite deliberado |
| --- | --- | --- |
| `.dct-badge` | `white-space: nowrap` | Es una etiqueta breve. Una larga no se trunca y necesita anchura o scroll local. |
| `.dct-table th` | `overflow-wrap: normal` | Los encabezados de varias palabras pueden saltar entre palabras. |
| `.dct-is-numeric` en `th`/`td` | `overflow-wrap: normal` | Mantiene cifras sin espacios íntegras; permite ajustar números con texto. |
| `.dct-table-wrap` | `min-inline-size: 0`, conservando `overflow-x: auto` | Los ancestros de aplicación deben permitir que el área disponible se contraiga. |

No se aplica nowrap a toda la tabla. Tampoco a todas las celdas numéricas:
la skill ya documentaba «128 EUR, completado», y la web usa cantidades con
unidades y valores como «variable». La clase numérica comunica alineación y
cifras tabulares, no contenido universalmente indivisible.

Un badge largo en una tabla incrementa su anchura intrínseca y puede necesitar
varios desplazamientos para leerse a 200%. En un contenedor independiente de
32 px, el texto permanece íntegro pero exige desplazamiento local. No sería
correcto ocultarlo con elipsis o recorte silencioso.

## Responsabilidad de la integración

- Limitar `anywhere` a texto libre, descripciones y URLs, en vez de todo el body.
- Mantener juntos signo, cifra y moneda con `.app-amount { white-space: nowrap; }`
  cuando el importe sea una unidad de lectura. Esa clase es de aplicación.
- Conservar el mínimo de tabla de 40rem o validar otro con datos representativos.
  No forzar `table-layout: fixed` o anchuras de columna incompatibles con ellos.
- Permitir contracción de ancestros Grid/Flex mediante `min-inline-size: 0`
  o pistas `minmax(0, 1fr)` cuando corresponda.
- Mantener `tabindex="0"`, `role="region"` y nombre accesible en el wrapper.
- Preferir etiquetas breves; las explicaciones largas deben ser texto normal.

La receta canónica está en [RECIPES.md](RECIPES.md#tabla-responsive). Incluye CSS
de aplicación para importes grandes y negativos; la preview web y el snippet
copiable se generan desde esa misma representación.

## Cobertura y evidencia

Prueba permanente: `scripts/table-text-check.cjs`, integrada en `make check`.
Se ejecutaron 36 combinaciones: Chromium 151.0.7922.34, Firefox 153.0 y WebKit
26.5; 320 y 375 px; normal, texto al 200% y zoom CSS al 200%; CSS legible y
minificado. Datos: importes grandes y negativos, moneda, contenido numérico
mixto, badges breves y largos, contenedor de 32 px, descripciones largas y sin
espacios, mínimo predeterminado y mínimo reducido a cero.

Resultados: todas las combinaciones pasan. Se comprueban líneas del texto,
scroll local frente a desbordamiento de página, ajuste del contenido mixto y
descripciones, acceso por Tab, foco visible y desplazamiento con ArrowRight.
Se generan capturas de la posición inicial y del extremo horizontal.

El modo `diagnose` reconstruye el comportamiento heredado anterior mediante
sobrescrituras de texto sobre el build actual; no es una copia íntegra de 1.6.1.
La primera prueba de diagnóstico sí se ejecutó sobre el CSS original de 1.6.1.

Artefactos de esta ejecución:

- `/tmp/opencode/dctcss-table-before/`: diagnóstico y `results.json`.
- `/tmp/opencode/dctcss-table-after/`: matriz corregida, capturas y `results.json`.
- `/tmp/opencode/dctcss-site-review/`: las diez páginas en Chromium a 390/1440 px,
  vistas generales inspeccionadas y detalles de componentes/receta a 375 px
  con `anywhere` heredado.
- `/tmp/dctcss-visual/`: aplicación de referencia en claro/oscuro a 390/1440 px;
  comparación con baselines aprobada, sin actualizarlos.

Además pasan `make build`, `make check` (contratos, configuración, sintaxis,
ShellCheck, builds reproducibles, artefactos sincronizados, todas las páginas en
Chromium a 390/844/1440 px y regresión visual) y `make visual-check`.
El CSS minificado pasa de 39.680 a 39.781 bytes: +101 bytes.

## Límites de la revisión

La matriz usa viewport estrecho con `deviceScaleFactor: 1` y `hasTouch: true`,
no dispositivos móviles físicos. El texto se amplía mediante tamaño raíz al
200% y el zoom mediante `body { zoom: 2; }`: no se comprobó el zoom nativo de la
interfaz del navegador. WebKit de Playwright no equivale a una prueba en Safari
real de iOS. Las capturas de detalle se inspeccionaron en Chromium; Firefox y
WebKit tienen verificaciones automáticas y capturas, no una revisión visual
exhaustiva manual. No se auditó directamente GestProp ni se desplegó una release.
