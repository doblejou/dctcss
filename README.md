# dctcss

Framework CSS reutilizable basado en CSS moderno nativo, sin JavaScript ni dependencias externas de ejecución. La versión actual es `1.6.6` y toda su API pública usa el prefijo `dct-`.

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
SITE_URL=https://dctcss.com
```

`SITE_URL` identifica el sitio oficial, no la dirección donde se abre la vista previa. La navegación, los estilos y los botones de descarga usan el servidor que visitas; las URLs absolutas de los metadatos y `release.json` apuntan al sitio oficial. Regenera `public/` al cambiar `DCTCSS_ENV`: solo el HTML de producción incluye el script de DCT Hits.

En `production`, `SITE_URL` debe usar HTTPS y no puede ser local, de loopback, link-local, privada ni tener un hostname de una sola etiqueta.

```sh
sh scripts/build.sh
sh scripts/check.sh
sh scripts/serve.sh
```

Si `make` está disponible, los atajos equivalentes son `make build`, `make check` y `make serve`. Antes de publicar, ejecuta además `make check-release`; aplica todas las comprobaciones con reglas de producción y rechaza configuraciones locales.

### Commits, pushes y releases de GitHub

Instala Gitleaks (probado con 8.16.0). En cada clon ejecuta una vez
`git config --local core.hooksPath .githooks` para activar los hooks del proyecto.
Si ya usas hooks propios, integra ambos antes de cambiar esa configuración.
Cada commit analiza los cambios preparados y cada push el historial. Un hallazgo
o un fallo del escáner bloquea la operación; los resultados se redactan.

Para publicar los enlaces locales de `.ai-rules/` como ficheros normales:

```sh
sh scripts/prepare-rules-commit.sh URL_DEL_REMOTO ../dctcss-publicacion
cd ../dctcss-publicacion
git diff --cached
git commit -m "Actualiza las reglas compartidas"
git push
```

El script exige un destino nuevo fuera del proyecto, clona la rama predeterminada
del remoto y prepara únicamente `.ai-rules/`, incluidas las bajas respecto al
remoto. El clon usa los hooks de la carpeta de trabajo original. Si la preparación
falla, no uses ese clon para publicar; corrige el fallo y repite con un destino nuevo.

Para publicar en GitHub, sube la etiqueta `v` seguida de `VERSION` y ejecuta
`make github-release` desde ese commit, con `origin` apuntando al proyecto y
GitHub CLI autenticado. El comando comprueba la etiqueta remota, extrae `dist/`
del commit etiquetado, verifica sus checksums y analiza el historial y los
adjuntos antes de subirlos. Para el despliegue web, `make check-release` analiza
también `public/` y `dist/`. Los hooks son locales: un clon nuevo necesita
activarlos y la interfaz web de GitHub no los ejecuta.

Las regresiones visuales se comparan con los cuatro PNG de `scripts/visual-baselines/`. Tras revisar un cambio visual deliberado en ambos tamaños y temas, actualízalos explícitamente con `sh scripts/visual-check.sh public /tmp/dctcss-visual --update-baselines` y vuelve a ejecutar `sh scripts/check.sh`.

El servidor local expone exclusivamente el document root generado `public/` en `http://localhost:8000/`. El puerto puede cambiarse con `PORT=8080 sh scripts/serve.sh`, sin cambiar `SITE_URL`. Para usar `http://dctcss.local/`, configura ese dominio en el servidor local con el mismo document root `public/`.

## Publicación

### Despliegue desde el servidor

Usa un clon de `https://github.com/doblejou/dctcss` con la rama que quieras
publicar siguiendo su rama remota. En la raíz del clon crea un `.env` local:

```dotenv
DCTCSS_ENV=production
SITE_URL=https://dctcss.com
```

Desde ese clon ejecuta en cada despliegue:

```sh
sh scripts/deploy.sh
```

Sin argumento, el destino es la raíz del clon (el directorio padre de `scripts/`).
Por ejemplo, ejecutar `./deploy.sh` desde `/var/www/dctcss.com/scripts`
publica en `/var/www/dctcss.com/public` y guarda las versiones en `.deploy/`.
Si `public/` ya es un directorio real, el script se detiene sin reemplazarlo.
También se admite un destino externo: `sh scripts/deploy.sh /srv/www/dctcss`
publica en `/srv/www/dctcss/public`.

La raíz web se llama **`public/` en los dos entornos**. Configúrala sin
ejecución de PHP. El destino debe ser escribible por el usuario de despliegue.
`deploy.sh` exige `DCTCSS_ENV=production` y un `SITE_URL` válido de producción
antes de ejecutar Git. En desarrollo usa `make build`: trabaja con los archivos
locales, incluidos los cambios sin commit, y no hace `git pull`.
En producción, el script actualiza la rama con `git pull --ff-only` y ejecuta la construcción
y la activación desde el commit remoto en un directorio privado. Si las ramas
han divergido o hay commits solo locales, se detiene sin cambiar `public/`.
Usa el `.env` del servidor (las variables de proceso mantienen su prioridad).
Necesita Git, `flock` y las herramientas de `make build`, incluido el
minificador de `/opt/dctfrw/`. El servidor comprueba la configuración de
producción y el build valida los artefactos antes de activarlos. Ejecuta
`make check-release` durante la preparación de la publicación; el despliegue
no repite esa suite ni la auditoría HTML y de accesibilidad. No necesita Java,
Node, npm, Chromium ni Gitleaks.
Solo cambia el enlace `public` después de pasar las comprobaciones. Si el
servidor ya apunta a `current`, realiza un despliegue con este script y cambia
la raíz web a `public`. Conserva las
publicaciones anteriores en `release.*`; se pueden retirar las que ya no se usen.

### Auditoría obligatoria del build local y de la release

Cada build regenera `robots.txt` y `sitemap.xml` con `SITE_URL`. En `make build` y al preparar la release, Nu HTML Checker valida todas las páginas (los avisos también bloquean) y axe-core las revisa en Chromium a 320 y 1440 px. También se comprueba que no haya desplazamiento horizontal de página y que Tab y Enter permitan saltar directamente al contenido principal. Un fallo o la ausencia de una herramienta detiene el build. El despliegue usa `scripts/build.sh --deploy`: exige configuración de producción y conserva la validación PHP de los artefactos antes de activarlos. Los distintivos del pie describen las pruebas automáticas de preparación; la evaluación con lectores de pantalla y personas usuarias sigue siendo manual.

El build usa `/opt/dctserver/scripts/dct_vnu/dct_vnu_validate_html.sh` con Java 21. El script descarga `vnu.jar` en la primera ejecución y lo reutiliza desde `${XDG_CACHE_HOME:-$HOME/.cache}/dctcss/vnu.jar`. Para construir sin red, aprovisiona ese fichero de antemano. Node, npm, Playwright y Chromium se usan para la accesibilidad. Con Playwright instalado globalmente, prepara axe y Chromium como el usuario que ejecuta el build:

```sh
npm install --prefix "$HOME/.local/share/dctcss-audit" --ignore-scripts axe-core@4.14.1
playwright install chromium
```

Para otra ubicación, define `DCTCSS_AUDIT_MODULES` con la ruta a `node_modules`. Para repetir solo la auditoría: `sh scripts/audit-site.sh public`.

### Despliegue manual

Ejecuta `make check-release` con `DCTCSS_ENV=production` y el `SITE_URL` público definitivo. Después despliega el contenido generado de `public/` haciendo que ese directorio sea el document root del dominio. No publiques la raíz del repositorio: `site/`, `src/`, `dist/`, `skills/`, `scripts/` y `.env` no forman parte de la superficie web.

`public/` es un conjunto cerrado y reemplazable. Incluye las páginas estáticas, assets, robots, sitemap, las descargas actuales y `.custom_apache_rules.conf`; no debe editarse manualmente. El build copia las reglas desde `site/.custom_apache_rules.conf`. Si Apache no tiene ya una integración para ese nombre, inclúyelo en el bloque `<Directory>` del document root con `mod_rewrite` habilitado. El archivo por sí solo no activa la redirección de `/index.html` a `/`.

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

`make refresh-benchmarks` consulta la versión `latest` en npm y descarga el CSS minificado de cada framework desde una URL con versión fija. Guarda fecha, bytes, tamaño con gzip nivel 9 y SHA-256 en [benchmarks/framework-sizes.txt](benchmarks/framework-sizes.txt). En Tailwind solo registra la versión: el CSS depende del proyecto. `make build` usa esa instantánea sin red para la comparativa y mide el artefacto actual de dctcss.

## Licencia

MIT. Consulta `LICENSE`.
