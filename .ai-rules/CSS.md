# CSS

## Ámbito

Hojas de estilo CSS de los proyectos que seleccionen este documento en su
`APP.md` raíz. Las particularidades y excepciones se declaran en
[APP.md](../APP.md), conforme a [AGENTS.md](../AGENTS.md).

## Framework y skill

Utilizar `dctcss` como framework CSS por defecto, salvo que `APP.md` indique otra
opción o establezca que no se utilice un framework.

Cuando el proyecto utilice `dctcss`, su skill debe estar disponible. Cargarlo y
consultarlo antes de trabajar con el framework. Si no está disponible, avisar al
usuario y no inferir sus convenciones ni su API.

Reutilizar las clases, componentes y variables de `dctcss` antes de crear estilos
equivalentes. Mantener los estilos propios separados del framework; no modificar
sus archivos para personalizar la aplicación.

## Criterios de implementación

Antes de escribir JavaScript preguntarse siempre:

> ¿Puede resolverse únicamente con HTML5 o CSS?

Preferir CSS cuando la solución sea:

- más simple;
- accesible;
- mantenible.

Aprovechar CSS moderno siempre que simplifique el código y sea compatible con
los navegadores definidos en `APP.md`.

## Organización y cascada

- Organizar los estilos por responsabilidades y componentes, sin imponer un
  archivo por componente si no mejora su mantenimiento.
- Centralizar valores compartidos mediante propiedades personalizadas, reutilizando
  las variables existentes antes de definir otras equivalentes.
- Mantener selectores claros y una especificidad baja y predecible. Evitar
  selectores excesivamente largos o dependientes de anidaciones profundas del HTML.
- Evitar `!important` salvo una necesidad concreta que no pueda resolverse de
  forma sencilla mediante la cascada o las opciones del framework.

## Formato

- Utilizar Allman Style: colocar las llaves de apertura y cierre de bloques en
  líneas separadas.
- Dejar una línea en blanco después de cada cierre `}` antes del siguiente código,
  incluido el cierre de un bloque exterior.

## Distribución

En proyectos sin un proceso de build autorizado, mantener CSS servible como
archivos estáticos.
