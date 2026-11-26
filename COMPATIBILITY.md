# Compatibilidad

dctcss 1.6 requiere estas versiones mínimas:

| Motor o navegador | Versión mínima |
|---|---:|
| Chrome y Chromium | 114 |
| Firefox | 125 |
| Safari | 17.0 |

Los navegadores basados en Chromium, incluidos Edge y Android WebView, deben usar una versión del motor equivalente o posterior. En iOS se aplica el mínimo de Safari/WebKit. No se declaran compatibles Internet Explorer ni navegadores anteriores a estos mínimos.

## Pruebas de las recetas por contenedor y acordeón (2026-11-26)

| Mínimo declarado del framework | Motor probado con Playwright | Layout | Acordeón con teclado |
|---|---|---|---|
| Chrome / Chromium 114 | Chromium 151.0.7922.34 | Correcto | Correcto |
| Firefox 125 | Firefox 153.0 | Correcto | Correcto |
| Safari 17.0 | WebKit 26.5 | Correcto | Correcto |

`make check` ejecuta `scripts/recipes-check.cjs` cuando está disponible Playwright. La prueba abre la página generada sin JavaScript: verifica dos anchos de contenedor en la misma ventana, una columna en móvil con texto al 200% sin desbordamiento horizontal y dos grupos de `details` independientes manejados con teclado.

Las recetas son optativas. Sin consultas de contenedor se aplica la cuadrícula base de una columna; si se ignora `details[name]`, cada panel se abre y cierra por separado. Estas degradaciones son las previstas por el marcado y el CSS. En esta revisión no se ejecutaron las versiones mínimas, Safari de distribución ni pruebas manuales con lector de pantalla.

## Criterio verificable

La política se revisó el 2026-07-28. Los mínimos corresponden a la disponibilidad conjunta de las capacidades que fijan el mínimo del framework:

- Cascade layers y custom properties.
- Selectores `:where()`, `:is()` y `:has()`.
- `color-mix()` en `srgb`.
- Propiedades lógicas de tamaño, margen, padding, borde e inset.
- Grid, Flexbox y `aspect-ratio`.
- Elementos nativos `dialog` y `details`.
- Popover API declarativa mediante `popover` y `popovertarget`.
- Preferencias `prefers-color-scheme` y `prefers-reduced-motion`.

Las reglas para `forced-colors` son una mejora progresiva: se aplican cuando el navegador expone esa preferencia y se ignoran en los demás sin perder la funcionalidad base.

Para reproducir la revisión, comprueba cada capacidad en las tablas de compatibilidad de [MDN](https://developer.mozilla.org/en-US/docs/Web) y toma, para cada navegador, la versión más alta requerida. Popover API fija los mínimos actuales: [Chrome 114, Firefox 125 y Safari 17](https://developer.mozilla.org/en-US/docs/Web/API/Popover_API#browser_compatibility). Si el CSS incorpora una capacidad con un mínimo superior, este documento debe actualizarse en el mismo cambio.

El framework no transpila CSS, no incluye polyfills ni distribuye JavaScript. La aplicación es responsable del estado interactivo, de probar sus plataformas concretas y de proporcionar degradaciones fuera del objetivo.

Durante 1.x, clases, tokens, atributos requeridos y nombres de artefacto permanecen compatibles. Las adiciones usan versiones menores, las correcciones versiones patch y los cambios incompatibles requieren una versión mayor.

`1.6.4` es la versión actual. El HTML válido en 1.2.0 continúa siendo válido; la navbar inversa, la elevación de tablas, la elevación hover de botones y el validador son ampliaciones opcionales. La protección de ajuste de texto en tablas y badges no eleva los mínimos de navegador. `1.0.0` fue la primera publicación y el manifiesto `schemaVersion: 2` continúa definiendo el contrato estructurado público de la serie 1.x.
