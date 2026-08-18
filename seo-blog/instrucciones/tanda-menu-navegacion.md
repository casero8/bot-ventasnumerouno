# Instrucciones para Claude en Chrome — tanda 8 · el menú

**Fecha:** 18 de agosto de 2026 · **Sitio:** ecogadgetoficial.com

Dos encargos del dueño, en este orden:

1. **Al entrar en una categoría, las letras del menú se hacen más pequeñas.** Hay que
   averiguar por qué y dejarlas iguales en toda la web.
2. **Reorganizar el menú al estilo MediaMarkt**, porque hoy no se navega bien y está todo
   muy junto, tanto en ordenador como en móvil.

Reglas que no se rompen:

- **No instales ningún plugin.** Ni de mega menú, ni de CSS, ni de nada. El tema Minimog ya
  trae mega menú, y el CSS va en `Apariencia → Personalizar → CSS adicional`.
- **No toques slugs, URLs ni el padre de ninguna categoría.** Toda la reorganización se
  hace **en el menú**, no en la taxonomía. Cambiar el padre de una categoría cambia su URL
  y tira el posicionamiento por la borda. Esto no es negociable.
- **No toques el logo ni la cabecera del tema.**
- No borres el menú actual. Se duplica y se trabaja sobre la copia. Ver el paso 2.1.
- **Para en los puntos de control** y espera confirmación.

---

## Tarea 1 — Las letras que encogen

### 1.1 · Diagnóstico, antes de tocar nada

No apliques CSS a ciegas: si tapas el síntoma con un `!important` sin saber qué regla está
ganando, el problema reaparece en la siguiente página que no habías mirado.

Abre las herramientas de desarrollador y haz esto **en las dos páginas**:

- **Página A:** la portada, `https://ecogadgetoficial.com/`
- **Página B:** una categoría, `https://ecogadgetoficial.com/product-category/serie-delta/`

En cada una, con el inspector sobre **el mismo enlace del menú principal** (por ejemplo
«Estaciones DELTA»), anota:

| Dato | Portada | Categoría |
|---|---|---|
| `font-size` calculado (px) | | |
| La regla CSS que gana y **de qué archivo viene** | | |
| Clases del `<body>` | | |
| Clases del contenedor de la cabecera | | |
| ¿Es el mismo elemento `<header>`? ¿Mismo `id`? | | |

Repite la comparación **en móvil** (vista responsive, 390 px de ancho), porque puede que
solo pase en uno de los dos.

### 1.2 · La sospecha más probable

La portada la construyó Elementor. Las páginas de categoría, no: usan la cabecera nativa
del tema. **Si son dos cabeceras distintas, no es que las letras encojan: es que son dos
menús diferentes con dos hojas de estilo diferentes**, y entonces el arreglo no es CSS, es
hacer que la categoría use la misma cabecera que la portada.

Compruébalo mirando si el `<header>` tiene clases de Elementor (`elementor-location-header`
o parecidas) en una página y no en la otra. **Si es esto, dilo y para**: es una decisión de
plantilla, no un retoque.

Otras dos causas frecuentes, por orden:

- La **cabecera pegajosa** (sticky): al bajar encoge el tipo, y en las categorías se activa
  antes porque la página es más larga. Se ve porque el `font-size` cambia **al hacer
  scroll**, no al cambiar de página. Compruébalo.
- La barra lateral de filtros de WooCommerce, que en categorías mete su propia lista de
  categorías con tipo más pequeño. Si lo que encoge es **esa** lista y no el menú de
  arriba, no es el mismo problema: dilo, porque se arregla en otro sitio.

### 1.3 · El arreglo

Solo cuando sepas qué regla gana. Va en `Apariencia → Personalizar → CSS adicional`, con
los selectores **reales** que hayas encontrado, no con estos de ejemplo:

```css
/* Menu principal: mismo tamano en toda la web */
SELECTOR_REAL_DEL_ENLACE_DE_MENU {
  font-size: 16px;
  line-height: 1.4;
}
```

Tres condiciones:

- **16 px es el mínimo** en el menú principal. Por debajo de eso se lee mal y en móvil el
  navegador hace zoom solo.
- Usa `!important` **solo si no hay otra manera**, y si lo usas, di por qué.
- Después, recorre **cinco tipos de página** y comprueba que el menú se ve igual en todas:
  portada, una categoría, una subcategoría anidada, una ficha de producto y el carrito.

### Punto de control 1

Enseña la tabla del diagnóstico y el CSS que propones **antes** de guardarlo.

---

## Tarea 2 — El menú al estilo MediaMarkt

### 2.1 · Lo primero: una copia de seguridad del menú actual

En `Apariencia → Menús`, crea un menú nuevo llamado **«Copia menú anterior 18-08»** y
reproduce en él, entrada por entrada, el menú que hay ahora. No lo asignes a ninguna
ubicación: se queda guardado y no se ve.

Solo cuando la copia esté hecha y comprobada, empieza a tocar el menú de verdad.

### 2.2 · Qué está mal hoy

Tres cosas, y las tres se arreglan con la misma reorganización:

- **El menú mezcla dos estructuras**: enlaza a 5 categorías y a 8 páginas. Quien navega no
  puede saber cuál es el sitio «de verdad», y Google tampoco.
- **Un mismo tema está repartido en cinco categorías.** «Batería adicional» son cinco
  categorías distintas para seis productos, y entre dos de ellas suman **3.715
  impresiones**: es lo que más se busca de toda la tienda. En el menú tienen que salir
  juntas, bajo un solo epígrafe.
- **Hypershell → Serie X → Max S son tres niveles para llegar a un producto.** En el menú
  eso se aplana: nadie hace tres clics.

Insisto en lo de antes, porque es la parte peligrosa: **agrupar en el menú no es reordenar
la taxonomía**. Un mismo epígrafe del menú puede reunir cinco categorías que en WordPress
siguen colgando donde estaban. Las URLs no se tocan.

### 2.3 · La estructura que hay que montar

Nueve departamentos arriba, y cada uno abre un panel con columnas, como MediaMarkt. Los
nombres entre comillas son lo que ve el usuario; entre paréntesis, la categoría real a la
que apunta.

**1 · Estaciones de energía** → categoría `serie-delta`

| Columna «Serie DELTA 3» | Columna «Serie DELTA 2» | Columna «DELTA Pro» | Columna «Serie RIVER» |
|---|---|---|---|
| DELTA 3 | DELTA 2 | DELTA Pro | RIVER 2 |
| DELTA 3 Plus | DELTA 2 Max | DELTA Pro 3 | RIVER 2 Max |
| DELTA 3 Max | DELTA Max Ultra | DELTA Pro Ultra | RIVER 2 Pro |
| DELTA 3 Max Plus | | | RIVER 3 |

**2 · Placas solares** → categoría `paneles-solares`

Portátiles · Rígidas · *(enlace)* Cuántas placas necesito para mi casa

**3 · Baterías adicionales** → categoría `baterias-adicionales`

Para DELTA Pro · Para DELTA 3 · Para DELTA 2 Max · Para DELTA 3 Max Plus

Este es el departamento que más se busca y hoy está escondido. Que esté arriba y visible.

**4 · Casa y balcón** → categoría `stream-series`

STREAM para balcón · Power Kits · Accesorios Power Kits

**5 · Powerbanks y carga rápida** → categoría `serie-rapid`

RAPID Pro · RAPID Mag · Accesorios RAPID

**6 · Exoesqueletos HyperShell** → categoría `hypershell` · **con la etiqueta «Novedad»**

X Ultra S · X Max S · X Pro S · Accesorios HyperShell

Aplanado a un solo nivel. Es la novedad de la tienda y tiene que verse desde el primer
vistazo, no a tres clics.

**7 · Coche y arranque** → categoría `arrancador-de-coche`

Arrancadores Lokithor · PowerFlash · Automoción

**8 · Camping y nevera** → *(enlace a la categoría con más stock de las tres)*

EcoFlow WAVE · WAVE 3 · GLACIER Classic

Aviso: las tres están **sin stock**. Si al montarlo siguen a cero, **no pongas este
departamento arriba**: mételo como una columna más dentro de «Estaciones de energía», y
dilo. Un departamento entero que no vende nada estorba más que ayuda.

**9 · Accesorios** → categoría `accesorios`

| Columna «Por gama» | Columna «Por tipo» |
|---|---|
| Accesorios DELTA 3 | Cables |
| Accesorios DELTA 2 Max | Soportes |
| Accesorios DELTA 2 | Fundas y transporte |
| Accesorios DELTA Pro | |
| Accesorios RIVER 2 | |

**Y al final, separado y en color distinto: «Ofertas»** → `/ofertas/`. Es lo que hacen
MediaMarkt, Amazon y PcComponentes: la entrada de ofertas nunca va del mismo color que las
demás. Hoy `/ofertas/` tiene 19 productos y todos con stock, así que ya se puede enseñar.

### 2.4 · Reglas de la reorganización

- **Toda entrada del menú apunta a una categoría, no a una página.** Excepción: los enlaces
  de guía que van marcados como *(enlace)*, y `/man/` si decides incluirlo.
- Si una entrada apunta hoy a una página y su categoría **todavía no tiene texto**, déjala
  como está y anótala. Repuntar el menú a una categoría vacía es peor que dejarlo. Las que
  ya se pueden repuntar porque su categoría está escrita: `serie-delta`, `serie-river`,
  `paneles-solares`, `accesorios`, `serie-rapid`, `stream-series`.
- **Máximo dos niveles** en el panel. Si algo necesita tres, es que el epígrafe está mal
  elegido.
- **Ninguna entrada sin destino.** El título de una columna también se enlaza a su
  categoría: en MediaMarkt el encabezado de columna es un enlace, no un adorno.
- Comprueba **todos** los enlaces uno a uno antes de dar por bueno el menú. En una tanda
  anterior se dieron por buenas dos URLs inventadas que devolvían 404.

### 2.5 · Cómo se monta

Mira primero si Minimog trae mega menú propio: en `Apariencia → Menús`, despliega una
entrada de primer nivel y busca una opción tipo **Mega Menu / Menú ancho / columnas**. El
tema lo trae. **Úsalo.** Si lo montas a mano con CSS sobre un submenú normal, se romperá en
la siguiente actualización del tema.

Si no aparece esa opción, míralo también en `Opciones De Tema → Cabecera`, que es donde
Minimog guarda la configuración del menú.

Solo si no existe en ninguno de los dos sitios, dilo y para. **No lo suplas con un plugin.**

### 2.6 · «Está todo muy junto»: los números

Es la queja concreta del dueño, así que va con medidas, no con adjetivos.

**En ordenador:**

- Enlaces de primer nivel: `font-size` 16 px, separación horizontal entre ellos **24 px
  como mínimo**.
- Dentro del panel: `font-size` 15 px, `line-height` 1.6, **10 px de separación vertical**
  entre enlaces.
- Títulos de columna: 14 px, en negrita, en mayúsculas, con **16 px de aire por debajo**.
- El panel, `padding` de 32 px, y una línea fina de separación entre columnas.
- Máximo **8 enlaces por columna**. Si sobran, se abre otra columna.

**En móvil, que es donde peor está:**

- Cada fila del menú, **48 px de alto como mínimo**. Es el tamaño de un dedo; por debajo se
  falla el toque.
- `font-size` 16 px. Nunca menos: por debajo de 16 px el navegador hace zoom solo al tocar.
- `padding` de 14 px arriba y abajo, 16 px a los lados.
- Una línea de separación de 1 px entre filas.
- **Un nivel cada vez**: se toca un departamento y el panel entra desde la derecha con una
  flecha de «volver» arriba. Nada de acordeones anidados que dejan al usuario perdido en
  una lista de sesenta líneas.
- La flecha de desplegar tiene que ser **tocable en toda la fila**, no solo en el icono.

### 2.7 · Comprobación final

Antes de dar por terminado:

1. Abre **cada uno de los nueve departamentos** y todas sus columnas, y confirma que ningún
   enlace da 404.
2. Comprueba que desde la portada se llega a **cualquier categoría en dos toques**.
3. Repite el recorrido en móvil real, a 390 px.
4. Comprueba que el menú se sigue viendo igual en portada, categoría, subcategoría, ficha y
   carrito — es la Tarea 1, y hay que volver a mirarla porque el menú nuevo puede traer sus
   propios estilos.
5. Confirma que la caché se ha vaciado. El menú se cachea: si no lo vacías, el dueño verá
   el viejo y pensará que no has hecho nada.

### Punto de control 2

**Antes de montar nada**, enseña: el menú actual entrada por entrada, la confirmación de
que la copia de seguridad existe, si Minimog tiene mega menú o no, y qué categorías de la
estructura propuesta **no existen** con ese nombre. Espera el visto bueno.

Luego monta **un solo departamento**, el de «Baterías adicionales», que es el más pequeño y
el que más se busca, y enséñalo funcionando en ordenador y en móvil. Con el visto bueno,
monta los ocho restantes.
