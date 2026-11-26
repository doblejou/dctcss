# dctcss

Framework CSS reutilizable basado en CSS moderno nativo, sin JavaScript ni dependencias externas de ejecución. La versión actual es `1.6.4` y toda su API pública usa el prefijo `dct-`.

dctcss está diseñado principalmente para agentes de IA. El [manifiesto estructurado](src/dctcss-api.json) contiene el inventario de la API y la [skill](skills/dctcss/SKILL.md) define el marcado, las decisiones y las reglas de accesibilidad.

## Instalación

La web pública ofrece siempre el CSS de la última release en dos variantes. Usa la minificada en producción:

```html
<link rel="stylesheet" href="https://tu-dominio.example/downloads/dct.min.css">
```

Las URLs `/downloads/dct.css` y `/downloads/dct.min.css` son enlaces `latest`: su contenido cambia al publicar una release y no fija una versión histórica. Para despliegues reproducibles, conserva una copia del artefacto o publícalo bajo una URL versionada propia.

No edites `dist/*`. Personaliza los tokens después de enlazar el framework:

```css
:root {
  --dct-color-accent: #8f3dff;
  --dct-radius-md: 0.25rem;
}
```

Para instalar la skill en un agente, copia la carpeta completa `skills/dctcss/`. Esa carpeta, no `SKILL.md` por separado, es la unidad instalable: incluye el manual operativo, la licencia y las referencias locales necesarias para validar la API y consultar recetas. Copiar solo el Markdown es una instalación incompleta.

## Distribución

- `dist/dct.css`: CSS legible para desarrollo y depuración.
- `dist/dct.min.css`: CSS equivalente y minificado para producción.
- `dist/dctcss-api.json`: inventario estructurado de la API.
- `dist/RECIPES.md`: recetas autocontenidas para agentes.
- `dist/CHANGELOG.md`: historial de releases y guía para evaluar actualizaciones.
- `dist/dctcss-validate.php`: validador estático de contratos para HTML consumidor.
- `dist/LICENSE`: licencia MIT completa de la distribución.
- `dist/SHA256SUMS`: integridad de los siete artefactos anteriores.

La distribución para agentes es `skills/dctcss/`: `SKILL.md` es la autoridad operativa, `LICENSE` conserva los términos de distribución y `references/` contiene copias generadas del manifiesto, las recetas y el changelog. Las referencias hacen autocontenida la carpeta, pero no son nuevas fuentes de verdad ni deben editarse manualmente. El manifiesto usa `schemaVersion: 2`, primera versión publicada de su contrato estructurado.

La web publica `/release.json` para descubrir la release actual y `/changelog.html` para revisar sus cambios. Las aplicaciones deben comparar versiones y probar la actualización; no deben sustituir automáticamente el CSS en producción.

Los íconos usan SVG inline y declaran `fill="currentColor"` o `stroke="currentColor"` según su geometría; dctcss no impone globalmente ninguno de los dos. Ningún artefacto carga fuentes, íconos, librerías o servicios remotos.

La versión 1.6.2 protege badges, encabezados y celdas numéricas frente a reglas heredadas de ajuste de texto; la receta responsive mantiene indivisibles los importes mediante CSS de aplicación sin bloquear las descripciones. Ejecuta `php dist/dctcss-validate.php ruta/al/html` para detectar API inexistente, estructuras incompatibles y requisitos accesibles verificables antes de probar el comportamiento real en navegador.

## Desarrollo

El framework no requiere Node ni npm para su consumo. El entorno de desarrollo es GNU/Linux con PHP 8.1 o posterior, la extensión DOM/XML, GNU coreutils y diffutils. El servidor local usa Python 3 o BusyBox. `make check` usa Node para comprobar la sintaxis de los scripts de prueba; las pruebas de navegador usan npm para localizar Playwright instalado globalmente y las comparaciones visuales requieren ImageMagick. La minificación requiere `/opt/dctfrw/util/bin/minify_css.sh` y su dependencia `/opt/dctfrw/util/lib/common.inc`. Las herramientas provisionadas bajo `/opt/dctserver/` y `/opt/dctfrw/` se consideran confiables por ubicación; el build comprueba que las necesarias existan y sean accesibles, pero no fija su versión ni su checksum.

La configuración contiene `DCTCSS_ENV` y `SITE_URL`. El entorno del proceso tiene prioridad sobre `.env`, y `.env` sobre `.env.example`. `DCTCSS_ENV` solo admite `development` o `production`. `SITE_URL` debe ser una URL HTTP o HTTPS absoluta, sin query, fragmento ni slash final; se usa para canonical, Open Graph, sitemap, robots y el snippet de instalación.

En `development` se permite una URL HTTP de loopback, como los valores predeterminados siguientes:

```ini
DCTCSS_ENV=development
SITE_URL=http://localhost:8000
```

En `production`, `SITE_URL` debe usar HTTPS y no puede ser local, de loopback, link-local, privada ni tener un hostname de una sola etiqueta. Cualquier cambio de configuración requiere regenerar `public/`.

```sh
sh scripts/build.sh
sh scripts/check.sh
sh scripts/serve.sh
```

Si `make` está disponible, los atajos equivalentes son `make build`, `make check` y `make serve`. Antes de publicar, ejecuta además `make check-release`; aplica todas las comprobaciones con reglas de producción y rechaza configuraciones locales.

Las regresiones visuales se comparan con los cuatro PNG de `scripts/visual-baselines/`. Tras revisar un cambio visual deliberado en ambos tamaños y temas, actualízalos explícitamente con `sh scripts/visual-check.sh public /tmp/dctcss-visual --update-baselines` y vuelve a ejecutar `sh scripts/check.sh`.

El servidor local expone exclusivamente el document root generado `public/` en `http://localhost:8000/`. El puerto puede cambiarse con `PORT=8080 sh scripts/serve.sh`; ajusta también `SITE_URL`, ejecuta de nuevo el build y abre la URL correspondiente.

## Publicación

Ejecuta `make check-release` con `DCTCSS_ENV=production` y el `SITE_URL` público definitivo. Después despliega el contenido generado de `public/` haciendo que ese directorio sea el document root del dominio. No publiques la raíz del repositorio: `site/`, `src/`, `dist/`, `skills/`, `scripts/` y `.env` no forman parte de la superficie web.

`public/` es un conjunto cerrado y reemplazable. Incluye las páginas estáticas, assets, robots, sitemap y las descargas actuales; no debe editarse manualmente.

## Familias

dctcss cubre fundamentos y temas, layouts, componentes y formularios, navegación, contenido, estado y utilidades responsive. Consulta la [skill](skills/dctcss/SKILL.md) para el inventario y el marcado completos, y las [recetas](docs/RECIPES.md) para composiciones listas para adaptar.

## Estructura

- `src/`: CSS fuente y manifiesto estructurado de la API.
- `dist/`: artefactos generados; no deben editarse manualmente.
- `site/`: fuente editable de la web pública y la documentación humana.
- `public/`: document root generado; no debe editarse manualmente.
- `docs/`: recetas y documentos de diseño e implementación.
- `scripts/`: build, comprobaciones y servidor local.
- `skills/dctcss/`: paquete instalable completo para agentes; `SKILL.md` es el manual operativo y `references/` contiene artefactos generados.
- `benchmarks/`: referencias externas de tamaño y su procedencia.

## Compatibilidad

Consulta la [política verificable de compatibilidad](COMPATIBILITY.md). dctcss conserva las capacidades modernas requeridas sin transpilar ni incluir polyfills.

## Comparativa de tamaño

Cada build compara CSS minificado sin gzip y muestra también el tamaño con gzip nivel 9. Las versiones, procedencia y comprobaciones están en [benchmarks/framework-sizes.txt](benchmarks/framework-sizes.txt); el build imprime el tamaño actual de dctcss y la comparación. Las referencias no se descargan durante el build normal, los rangos orientativos no generan porcentajes y Tailwind se trata como variable, no como una cifra universal.

## Licencia

MIT. Consulta `LICENSE`.
