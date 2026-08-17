# -*- coding: utf-8 -*-
"""Genera vista-previa.html a partir de portada.css y de las ilustraciones.
   Se ejecuta con: python3 generar-vista-previa.py"""
import dibujos as D

css = open('portada.css', encoding='utf-8').read()

ICONOS = {
 'flecha':'<path d="M4 10h12M11 5l5 5-5 5"/>',
 'carrito':'<path d="M2 3h3l2 10h9l2-7H6"/><circle cx="9" cy="17" r="1.4"/><circle cx="16" cy="17" r="1.4"/>',
 'camion':'<path d="M2 5h10v9H2zM12 8h4l3 3v3h-7z"/><circle cx="6" cy="16" r="1.6"/><circle cx="15" cy="16" r="1.6"/>',
 'escudo':'<path d="M10 2l6 3v5c0 4-2.6 6.9-6 8-3.4-1.1-6-4-6-8V5z"/><path d="M7.5 10l1.8 1.8L13 8"/>',
 'llave':'<path d="M12.5 3a4 4 0 00-3.6 5.7L3 14.6V17h2.4l5.9-5.9A4 4 0 1012.5 3z"/>',
 'tarjeta':'<path d="M2 5h16v10H2z"/><path d="M2 8h16"/><path d="M5 12h3"/>',
}
def ico(n):
    return ('<svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7" '
            'stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">'
            + ICONOS[n] + '</svg>')

def promo(cls, foto, etiq, tit, txt, enl):
    e = f'<span class="eg-promo-etiq"><span class="eg-pill eg-pill-nuevo">{etiq}</span></span>' if etiq else ''
    return (f'<a class="eg-promo {cls}" href="#">{e}<span class="eg-promo-foto">{foto}</span>'
            f'<span class="eg-promo-txt"><b>{tit}</b><span>{txt}</span>'
            f'<i>{enl} {ico("flecha")}</i></span></a>')

PROMOS = "".join([
 promo("eg-promo-naranja eg-promo-xl", D.exo(), "Novedad", "Hypershell: camina con ayuda",
       "El exoesqueleto que te quita peso de las piernas en cuestas y caminatas largas.", "Descubrir HyperShell"),
 promo("eg-promo-azul eg-promo-xl", D.estacion(), "", "Que un apagón no te pare",
       "Estaciones EcoFlow DELTA para mantener el frigorífico, el router y las luces.", "Ver estaciones DELTA"),
 promo("eg-promo-verde", D.panel(), "", "Placas solares", "Portátiles y para balcón", "Ver placas"),
 promo("eg-promo-verde", D.casa(), "", "Kits de balcón", "Produce sin obra", "Ver kits"),
 promo("eg-promo-azul", D.estacion("#12629f","#062a52"), "", "Camping y furgo", "Baterías RIVER, ligeras", "Ver RIVER"),
 promo("eg-promo-naranja", D.cable(), "", "Arrancadores de coche", "Lokithor: arranque, compresor y linterna", "Ver arrancadores"),
])

def card(marca, nombre, precio, stock, foto, etiq=""):
    return (f'<article class="eg-prod">{etiq}'
            f'<a class="eg-prod-foto" href="#" tabindex="-1" aria-hidden="true">{foto}</a>'
            f'<div class="eg-prod-cuerpo"><p class="eg-prod-marca">{marca}</p>'
            f'<a class="eg-prod-nombre" href="#">{nombre}</a>'
            f'<div class="eg-prod-precio">{precio}</div>'
            f'<p class="eg-prod-stock">{stock}</p>'
            f'<a class="eg-prod-btn" href="#">{ico("carrito")}Añadir al carrito</a></div></article>')

NUEVO = '<span class="eg-prod-etiq"><span class="eg-pill eg-pill-nuevo">Nuevo</span></span>'
TOP   = '<span class="eg-prod-etiq"><span class="eg-pill eg-pill-top">Top ventas</span></span>'
def OFF(p): return f'<span class="eg-prod-etiq"><span class="eg-pill eg-pill-oferta">-{p}%</span></span>'

NOVEDADES = "".join([
 card("HYPERSHELL","HyperShell exoesqueleto — modelo de entrada","—","Disponible",D.exo(),NUEVO),
 card("LOKITHOR","Lokithor arrancador con compresor","—","Disponible",D.cable(),NUEVO),
 card("ECOFLOW","EcoFlow STREAM microinversor","—","Disponible",D.stream(),NUEVO),
 card("ECOFLOW","Kit solar de balcón EcoFlow","—","Disponible",D.casa(),NUEVO),
 card("ECOFLOW","EcoFlow DELTA 3 Plus","849,00&nbsp;€","Disponible",D.estacion(),NUEVO),
])
TOPVENTAS = "".join([
 card("ECOFLOW","EcoFlow DELTA 3 Classic (1024Wh)","599,00&nbsp;€","7 disponibles",D.estacion(),TOP),
 card("ECOFLOW","EcoFlow RIVER 2 Max","<del>399,00&nbsp;€</del><ins>329,00&nbsp;€</ins>","Disponible",D.estacion("#12629f","#062a52"),OFF(18)),
 card("ECOFLOW","Panel solar 220 W bifacial","399,00&nbsp;€","4 disponibles",D.panel(),TOP),
 card("ECOFLOW","Generador solar DELTA + panel","—","2 disponibles",D.generador(),TOP),
 card("ECOFLOW","Cable Solar A XT60i","25,00&nbsp;€ – 39,00&nbsp;€","Disponible",D.cable(),TOP),
])

CIRCULOS = "".join(f'<a class="eg-circulo" href="#"><span class="eg-circulo-foto">{f}</span><b>{t}</b></a>'
  for t, f in [("Estaciones DELTA",D.estacion()),("Baterías RIVER",D.estacion("#12629f","#062a52")),
               ("Placas solares",D.panel()),("Powerbanks",D.powerbank()),("HyperShell",D.exo()),
               ("Kits hogar",D.casa()),("Arrancadores",D.cable()),("Generadores",D.generador())])

MARCAS = "".join(f'<a class="eg-marca" href="#"><span>{m}</span></a>'
                 for m in ["EcoFlow", "HyperShell", "Lokithor"])

AVALES = "".join(f'<div class="eg-aval">{ico(i)}<div><b>{t}</b><span>{d}</span></div></div>'
  for i, t, d in [("camion","Envío en 24-48 h","En los productos con stock confirmado."),
                  ("escudo","Garantía oficial","Distribuidor autorizado de EcoFlow, HyperShell y Lokithor."),
                  ("llave","Servicio técnico propio","La incidencia la gestionamos nosotros."),
                  ("tarjeta","Pago a plazos","Financiación con SeQura al finalizar.")])

FAQ_L = [
 ("¿Sois distribuidor oficial?","Sí. Somos distribuidor autorizado de EcoFlow, HyperShell y Lokithor, con servicio técnico propio. El producto sale con la garantía del fabricante."),
 ("¿Qué es un exoesqueleto HyperShell?","Es un soporte que se lleva en la cintura y las piernas y que, con un pequeño motor, te acompaña al andar y al subir. Se nota sobre todo en caminatas largas, en cuestas y si pasas el día de pie."),
 ("¿Para qué sirve un arrancador Lokithor?","Para arrancar el coche cuando la batería se queda sin carga, sin necesidad de otro vehículo. Los modelos con compresor además inflan ruedas, y muchos hacen de powerbank y de linterna."),
 ("¿Cuánto tarda el envío?","Los pedidos con stock confirmado salen en 24-48 horas laborables. En la ficha de cada producto ves si está disponible en ese momento."),
 ("¿Qué pasa si el equipo falla?","Abres la incidencia con nosotros, no con el fabricante. Nuestro servicio técnico la gestiona de principio a fin."),
 ("¿Puedo pagar a plazos?","Sí. Al finalizar la compra puedes elegir el pago fraccionado con SeQura."),
]
FAQ = "".join(f'<details{" open" if i==0 else ""}><summary>{q}</summary>'
              f'<div class="eg-faq-cuerpo"><p>{a}</p></div></details>' for i,(q,a) in enumerate(FAQ_L))

HTML = f"""<title>Portada EcoGadget</title>
<style>
html, body {{ background: #fff; color: #4a5568; margin: 0;
  font-family: "Poppins", "Outfit", "Segoe UI", system-ui, -apple-system, sans-serif; }}
.pv-barra {{ background: #101827; color: #c9d2de; padding: 12px 16px; font-size: 12.5px; line-height: 1.5; }}
.pv-barra .pv-in {{ max-width: 1340px; margin: 0 auto; display: flex; flex-wrap: wrap; gap: 5px 22px; align-items: baseline; }}
.pv-barra b {{ color: #fff; font-size: 13px; }}
.pv-barra em {{ font-style: normal; color: #ff8a45; font-weight: 800; }}
.pv-hueco {{ background: #fff; border-bottom: 1px solid #e8ebf0; }}
.pv-hueco .pv-in {{ max-width: 1340px; margin: 0 auto; padding: 18px 16px; text-align: center;
  color: #9aa4b2; font-size: 12px; letter-spacing: .07em; text-transform: uppercase; font-weight: 800; }}
.pv-pie {{ max-width: 1340px; margin: 0 auto; padding: 24px 16px 46px; color: #7b8794; font-size: 13.5px; line-height: 1.7; }}
.pv-pie b {{ color: #101827; }}
{css}
</style>

<div class="pv-barra"><div class="pv-in">
  <b>Vista previa · portada nueva</b>
  <span>Sin Elementor · animaciones en CSS · <em>~23 KB de CSS</em> · <em>0 KB de JavaScript</em></span>
  <span>Las ilustraciones sustituyen a tus fotos; precios y stock salen de WooCommerce.</span>
</div></div>

<div class="pv-hueco"><div class="pv-in">Aquí va tu cabecera y tu logo · no se tocan</div></div>

<div class="eg-home">
<a class="eg-saltar" href="#eg-comprar">Saltar a los productos</a>

<div class="eg-hero"><div class="eg-hero-in">
  <div class="eg-hero-txt">
    <span class="eg-pill eg-pill-nuevo">Distribuidor oficial</span>
    <h1>Energía portátil, solar y movilidad</h1>
    <p>EcoFlow, HyperShell y Lokithor. Con servicio técnico propio en España y asesoramiento antes de comprar.</p>
    <div class="eg-hero-botones">
      <a class="eg-btn eg-btn-principal" href="#eg-comprar">Comprar ahora{ico('flecha')}</a>
      <a class="eg-btn eg-btn-suave" href="#">Ver HyperShell{ico('flecha')}</a>
    </div>
  </div>
  <div class="eg-hero-foto">{D.exo()}</div>
</div></div>

<div class="eg-ancho">

<section class="eg-seccion"><div class="eg-promos">{PROMOS}</div></section>
<span id="eg-comprar"></span>

<section class="eg-seccion" aria-labelledby="t1">
  <div class="eg-seccion-cab"><div><h2 id="t1">Novedades</h2><p>Lo último que ha entrado en la tienda.</p></div>
  <a class="eg-vertodo" href="#">Ver todos &rarr;</a></div>
  <div class="eg-fila">{NOVEDADES}</div>
</section>

<section class="eg-seccion" aria-labelledby="t2">
  <div class="eg-seccion-cab"><div><h2 id="t2">Los más vendidos</h2><p>Lo que más sale, con stock confirmado hoy.</p></div>
  <a class="eg-vertodo" href="#">Ver todos &rarr;</a></div>
  <div class="eg-fila">{TOPVENTAS}</div>
</section>

<section class="eg-seccion" aria-labelledby="t3">
  <div class="eg-seccion-cab"><div><h2 id="t3">Compra por categoría</h2></div>
  <a class="eg-vertodo" href="#">Ver toda la tienda &rarr;</a></div>
  <div class="eg-circulos">{CIRCULOS}</div>
</section>

<section class="eg-seccion"><div class="eg-banda">
  <div class="eg-banda-txt">
    <span class="eg-pill eg-pill-nuevo">Novedad</span>
    <h2>HyperShell: el exoesqueleto que te quita peso de las piernas</h2>
    <p>Un motor te acompaña al andar y al subir. Para caminatas largas, montaña y para quien pasa el día de pie.</p>
    <ul class="eg-banda-lista"><li>Se pone y se quita en segundos</li><li>Batería intercambiable</li><li>Distribuidor oficial en España</li></ul>
    <a class="eg-btn eg-btn-linea" href="#">Ver los modelos{ico('flecha')}</a>
  </div>
  <div class="eg-banda-foto">{D.exo()}</div>
</div></section>

<section class="eg-seccion"><div class="eg-banda eg-banda-clara">
  <div class="eg-banda-txt">
    <span class="eg-pill eg-pill-nuevo">Para casa</span>
    <h2>Kits solares de balcón: produce tu propia luz sin obra</h2>
    <p>Se instalan en un balcón o una terraza y empiezan a producir desde el primer día. Te decimos qué potencia encaja en tu caso.</p>
    <ul class="eg-banda-lista"><li>Sin obra y sin permisos de comunidad en la mayoría de casos</li><li>Se amplían después con más paneles o batería</li><li>Te calculamos el ahorro con tu factura delante</li></ul>
    <a class="eg-btn eg-btn-principal" href="#">Ver los kits{ico('flecha')}</a>
  </div>
  <div class="eg-banda-foto">{D.casa()}</div>
</div></section>

<section class="eg-seccion" aria-labelledby="t4">
  <div class="eg-seccion-cab"><div><h2 id="t4">Nuestras marcas</h2>
  <p>Somos distribuidor autorizado de las marcas que vendemos.</p></div></div>
  <div class="eg-marcas">{MARCAS}</div>
</section>

<section class="eg-seccion"><div class="eg-avales">{AVALES}</div></section>

<section class="eg-seccion"><details class="eg-texto">
  <h2>Una tienda especializada, no un marketplace</h2>
  <p>Trabajamos con EcoFlow, HyperShell y Lokithor, y somos distribuidor autorizado de las tres. El equipo que compras aquí llega con la garantía del fabricante y con alguien detrás a quien puedes llamar.</p>
  <p>Esa es la diferencia que más nos preguntan. Cuando compras en un marketplace y el equipo falla, empieza un ir y venir de correos entre el vendedor, la plataforma y el fabricante. Aquí la incidencia la abre y la sigue nuestro servicio técnico.</p>
  <summary>Leer más sobre lo que vendemos</summary>
  <h3>¿Qué necesitas?</h3>
  <p>Si buscas energía, depende de cuánto consume lo que quieres enchufar y de cuánto tiempo quieres que aguante. Un móvil y un portátil se resuelven con un <a href="#">powerbank</a>. Una nevera de camping o unas luces para el fin de semana entran en la <a href="#">serie RIVER</a>. Para aguantar un apagón en casa con el frigorífico y el router encendidos ya hablamos de la <a href="#">serie DELTA</a>.</p>
  <p>Si lo que quieres es gastar menos luz cada mes, y no solo tener respaldo para una emergencia, lo tuyo son <a href="#">placas solares</a> o un <a href="#">kit para balcón</a>: producen electricidad todos los días en lugar de guardarla.</p>
  <p>Para el coche está <a href="#">Lokithor</a>: arrancadores que te sacan de un apuro sin depender de que pase otro conductor, muchos con compresor para las ruedas y linterna incorporada.</p>
  <p>Y si lo que buscas es moverte mejor, ahí está <a href="#">HyperShell</a>, la novedad de la tienda: un exoesqueleto que te ayuda al caminar y al subir.</p>
</details></section>

<section class="eg-seccion" aria-labelledby="t5">
  <div class="eg-seccion-cab"><div><h2 id="t5">Preguntas frecuentes</h2></div></div>
  <div class="eg-faq">{FAQ}</div>
</section>

<section class="eg-seccion"><div class="eg-cierre">
  <div><h2>¿No lo tienes claro?</h2><p>Cuéntanos qué necesitas y te decimos qué equipo encaja. Sin compromiso.</p></div>
  <a class="eg-btn eg-btn-linea" href="#">Escríbenos{ico('flecha')}</a>
</div></section>

</div></div>

<div class="pv-pie">
  <b>Qué estás viendo.</b> Solo el contenido de la portada. Tu cabecera, tu logo, el menú y el pie no se tocan.
  <br><b>Las ilustraciones</b> están dibujadas para esta vista previa porque no puede cargar ficheros de tu servidor.
  En tu web van tus fotos reales y llenan el bloque igual que aquí.
  <br><b>Las marcas</b> salen solas de WooCommerce: EcoFlow, HyperShell y Lokithor, con su miniatura y enlazando a su página.
  <br><b>El «-18%»</b> se calcula del precio real. Si un producto no está rebajado, no sale etiqueta.
</div>
"""
open('vista-previa.html', 'w', encoding='utf-8').write(HTML)
print('vista-previa.html:', len(HTML), 'bytes')
