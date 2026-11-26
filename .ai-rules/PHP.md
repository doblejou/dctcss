# PHP

## Ámbito

Código PHP de los proyectos que seleccionen este documento en su `APP.md` raíz.
Las particularidades y excepciones se declaran en [APP.md](../APP.md), conforme
a [AGENTS.md](../AGENTS.md).

## Filosofía

Priorizar PHP nativo y las funcionalidades estándar del lenguaje antes que
librerías externas.

## Framework

Utilizar `dctfrw` como framework por defecto salvo que `APP.md` indique lo contrario.

Cuando el proyecto utilice `dctfrw`, su skill debe estar disponible. Cargarlo y
consultarlo antes de trabajar con el framework. Si no está disponible, avisar al
usuario y no inferir sus convenciones ni su API.

## Formato

- Utilizar Allman Style: colocar las llaves de apertura y cierre de bloques en
  líneas separadas.
- Dejar una línea en blanco después de cada cierre `}` antes del siguiente código,
  incluido el cierre de un bloque exterior.
- No dejar una línea en blanco antes de `else`, `elseif`, `catch` ni `finally`:
  deben seguir inmediatamente al cierre anterior, manteniendo sus llaves en
  líneas separadas.

### Arrays en PHP

Como criterio general, al crear un array nuevo, inicializarlo con `array()` y
definir sus elementos mediante asignaciones individuales.

Estilo preferido:

```php
$a = array();

$a['a'] = 1;
$a['b'] = 2;
$a['c'] = 3;
```

Fuera de la excepción siguiente, evitar inicializaciones con elementos, tanto
con `array(...)` como con `[...]`:

```php
$a = array('a' => 1, 'b' => 2, 'c' => 3);
```

Si se crea un array cuyos elementos utilizan los índices numéricos automáticos
de PHP, sin claves personalizadas, preferir una única asignación con `array` en
formato multilínea frente a inicializarlo vacío y añadir cada valor con `[]`.
Este criterio se refiere a las claves de los elementos del nuevo array, no a la
clave del destino, como `'app_helper_files'` en el ejemplo siguiente.
Colocar los paréntesis en líneas separadas, un elemento por línea, una coma tras
el último elemento y una línea en blanco antes del cierre:

```php
$autoload['app_helper_files'] = array
(
    'app',
    'admin',
    'eventos',
    'traffic',

);
```

No reinicializar arrays existentes cuando se pretenda ampliar o modificar su
contenido.

### Recorrido de arrays

Recorrer los arrays directamente con `foreach`. Evitar `array_keys()` únicamente
para recorrer sus claves, ya que crea un array adicional innecesario.

Si solo se necesita la clave, utilizar igualmente `foreach ($array as $key => $value)`,
aunque no se use el valor dentro del bucle.

```php
// Evitar:
foreach (array_keys($languages) as $code)
{
    ...
}

// Preferir:
foreach ($languages as $code => $name)
{
    ...
}
```

## Importación de clases

- Importar las clases al inicio del archivo mediante `use`, después de la
  declaración de `namespace` si existe.
- Evitar nombres de clase completamente cualificados dentro del código, como
  `\DateTimeImmutable`; utilizar `DateTimeImmutable` tras importarla con
  `use DateTimeImmutable;` en archivos con `namespace`.
- Si hay conflictos de nombres, utilizar un alias con `use ... as ...`.

## Versión de PHP

Utilizar PHP 8.4 como versión de referencia salvo que `APP.md` indique otra.

Aprovechar las funcionalidades disponibles en la versión de PHP definida por el
proyecto, incluidas las novedades de PHP 8.4 cuando esta sea la versión aplicable,
si:

- reduzcan código;
- mejoren el tipado;
- aumenten la seguridad;
- hagan el código más expresivo.

Respetar la versión indicada en `APP.md` o, en su ausencia, PHP 8.4. No utilizar
funcionalidades incompatibles con esa versión ni incorporarlas únicamente por
ser nuevas.

## Dependencias y Composer

No introducir Composer salvo necesidad justificada y autorización del usuario.

Si el proyecto ya utiliza Composer, seguir sus procedimientos existentes. Esta
regla no autoriza a eliminarlo, sustituirlo ni reorganizar sus dependencias.

Si una librería parece una buena opción, **consultarlo antes de incorporarla**.

## Comentarios

No añadir comentarios dentro del cuerpo salvo que expliquen una decisión que no
resulte evidente en el código.

Cada función con nombre y cada método declarado en el código propio deben incluir
una cabecera con este formato. No se exige esta cabecera para funciones anónimas
ni funciones flecha.

```php
################################################
#
#  Nombre de la función o método
#
#  Explicación breve de qué hace la función
#
################################################
```

La explicación debe centrarse en el propósito de la función, sin repetir línea
por línea cómo lo hace.
