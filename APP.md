# dctcss: reglas del proyecto

dctcss es un framework CSS nativo, reutilizable y sin dependencias de ejecución.
El CSS, la web estática que lo demuestra y la skill para agentes evolucionan
juntos. La API debe permitir generar HTML válido sin deducir reglas a partir
de los selectores. Para instalar, desarrollar o desplegar, consulta [README.md](README.md).

## Documentos aplicables

- `.ai-rules/FILOSOFIA_TECNICA.md`: diseño y mantenimiento.
- `.ai-rules/PHP.md`: generador del sitio y validadores CLI.
- `.ai-rules/HTML.md`: páginas, recetas y ejemplos.
- `.ai-rules/CSS.md`: framework y estilos privados del sitio.
- `.ai-rules/JAVASCRIPT.md`: mejoras del sitio y pruebas de navegador.
- `.ai-rules/CIERRE_DE_TAREAS.md`: informe de cierre.

## Mapa del proyecto: dónde hacer cada cambio

| Fuente editable | Qué define |
|---|---|
| `VERSION` y [CHANGELOG.md](CHANGELOG.md) | Versión exacta, fecha e historial de releases. El historial alimenta la distribución, la skill, `/changelog.html` y `/release.json`. |
| `src/dct.css` y módulos de `src/` | Orden de capas e inclusión de tokens, base, layouts, componentes, utilidades y temas. |
| `src/dctcss-api.json` | Inventario público y requisitos de marcado que se pueden verificar sobre HTML estático. |
| `skills/dctcss/SKILL.md` | Decisiones de consumo, marcado válido, accesibilidad y responsabilidades de la aplicación. Se instala la carpeta `skills/dctcss/` completa, incluida la licencia. |
| [docs/RECIPES.md](docs/RECIPES.md) | Composiciones canónicas. El CSS de aplicación de una receta usa nombres propios (`app-*`); su aparición en una receta no lo convierte en API. |
| `site/build.php`, `site/template.php` y `site/pages/` | Generador, plantilla y páginas de documentación. `site/site.css` y `site/site.js` son privados del sitio. |
| `scripts/` y `Makefile` | Ensamblado, minificación, validación y servidor local. `scripts/dctcss-validate.php` es el validador distribuible para aplicaciones consumidoras. |
| [COMPATIBILITY.md](COMPATIBILITY.md) | Mínimos por navegador, capacidades requeridas y procedimiento de revisión. Actualizarlo en el mismo cambio si suben los mínimos. |
| `benchmarks/framework-sizes.txt` | Tamaños de referencia y procedencia de las comparaciones del build. |

`dist/`, `public/` y `skills/dctcss/references/` son salidas generadas: editar
las fuentes. El build normal prepara y valida las tres en un área temporal y
las publica juntas, con reversión si falla el reemplazo. Mantener inventarios
cerrados, permisos `0755` para directorios y `0644` para archivos, licencia y
checksums de distribución. La comparación de tamaño usa CSS minificado sin
gzip; el tamaño de Tailwind depende de la aplicación.

## Cambios en el contrato público

- Preferir HTML nativo. El framework no contiene ni requiere JavaScript: el
  estado que HTML y CSS no puedan gestionar corresponde a la aplicación.
  No cargar fuentes, iconos ni servicios remotos; usar SVG local.
- Combinar componentes semánticos, modificadores explícitos y utilidades
  acotadas. Reservar `dct-` para clases y animaciones públicas, `--dct-` para
  tokens y `data-dct-*` para atributos propios. Mantener baja la especificidad;
  no usar `!important` en componentes. Solo admitirlo en utilidades con una
  justificación pública documentada.
- Mantener los tokens estructurales en `:root`. Los temas cambian la paleta y
  las sombras sin restablecer el espaciado, la tipografía ni la densidad que
  haya personalizado la aplicación.
- Actualizar juntos CSS, manifiesto, skill y demostración cuando cambie la API.
  Expresar en el manifiesto las restricciones verificables (elementos, clases,
  relaciones, atributos y nombres accesibles); explicar en la skill lo que deba
  resolver la aplicación. Usar el mismo contrato para snippets, recetas y skill.
- La web consume el CSS distribuido como una aplicación externa. Generar la
  vista y su snippet desde el mismo marcado, sin filtrar atributos privados al
  ejemplo. Contenido, navegación, snippets y descargas deben funcionar sin JS.
- Clases, tokens documentados, marcado requerido y nombres de artefactos son
  contrato público: corrección → patch, adición compatible → minor, ruptura →
  major. Consultar antes de cambios amplios o irreversibles. Mientras el proyecto
  siga sin repositorio Git, no realizar operaciones Git.

## Desarrollo, comprobación y publicación

- Si se solicita un commit, push o release con cambios locales de este árbol,
  preparar el commit en un clon aparte con `sh scripts/prepare-rules-commit.sh
  URL_DEL_REMOTO CLON_NUEVO`. El script prepara solo `.ai-rules/`: incorporar
  también los demás cambios solicitados y revisar `git diff --cached` antes
  del commit. Conservar los enlaces del árbol local y comprobar en el índice
  del clon que las reglas se publicarán como ficheros normales. Si no se conoce
  el remoto, pedir su URL. Realizar solo las operaciones de publicación solicitadas.

- En el clon Git, activar los hooks de `.githooks/` antes de hacer commits o
  pushes. Pasar Gitleaks con redacción y detener la operación si falla o no
  está instalado. Publicar releases de GitHub mediante `make github-release`,
  que analiza el historial y los adjuntos exactos antes de crear la release.
  No omitir los hooks ni publicar desde la interfaz web saltándose el análisis.

- `make build` regenera las salidas; `make serve` sirve solo `public/`.
  Las herramientas provisionadas en `/opt/dctserver/` y `/opt/dctfrw/` se
  consideran confiables por ubicación. Una nueva dependencia externa fuera de
  esos árboles requiere justificación y aprobación.
- `make check` valida contratos, configuración, reproducibilidad y vigencia de
  los artefactos. Para comprobaciones dirigidas: `make contract-test`,
  `make config-test` y `make visual-check`. En cambios visuales, revisar las
  páginas en Chromium con Playwright: móvil y escritorio, teclado y foco,
  contenido largo y zoom, contraste, temas y movimiento reducido. Actualizar
  los patrones visuales solo tras revisar un cambio deliberado.
- La configuración se resuelve en orden: entorno del proceso, `.env`,
  `.env.example`. `DCTCSS_ENV` admite `development` y `production`; `SITE_URL`
  es un origen HTTP(S) sin credenciales, ruta, barra final, query ni fragmento.
  Se permite HTTP local para desarrollo. Producción exige HTTPS y host público;
  el validador descarta direcciones locales o reservadas sin consultar DNS.
- Regenerar con la configuración definitiva y ejecutar `make check-release`
  antes de publicar. Desplegar exclusivamente `public/`. `/release.json` anuncia
  la release y `/downloads/` sirve la última versión: revisar el changelog y
  probar la actualización antes de sustituir el CSS de una aplicación.

## Particularidades respecto a las reglas compartidas

- **PHP:** se usa PHP CLI nativo con DOM/XML, compatible con PHP 8.1 o posterior.
  No se usa `dctfrw` como framework PHP; se reutiliza su minificador externo.
- **CSS:** este proyecto desarrolla `dctcss`. La regla de personalizar sin
  modificar el framework se aplica a la web consumidora; los cambios del
  framework se hacen en `src/`.
- **Shell:** los scripts propios declaran `#!/bin/sh` y el Makefile los invoca
  con `sh`. Mantener esa interfaz; las convenciones específicas de Bash 5.2
  no se seleccionan para estos scripts.
