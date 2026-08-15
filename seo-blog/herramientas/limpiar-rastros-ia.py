import re, json, html

BASURA_CLASE = re.compile(
 r'\b(?:hover:entity-accent|entity-underline|whitespace-normal|whitespace-pre-wrap|'
 r'cursor-pointer|align-baseline|text-token-[\w-]+|dark:[\w:\[\]-]+|'
 r'agent-turn|group/conversation-turn|markdown|prose)\b')

# Clases genéricas tipo utilidad. Solo se quitan si el MISMO elemento lleva
# además un marcador de ChatGPT, que es lo que prueba de dónde vienen.
# El tema define .flex y .sr-only, así que borrarlas a ciegas descoloca cosas.
UTILIDADES = re.compile(r'\b(?:flex|w-full|flex-col|min-h-\d+|gap-\d+|relative)\b')
MARCADOR = re.compile(r'data-(?:message-id|message-model-slug|turn-start-message|message-author-role)')


ATRIB_BASURA = re.compile(
 r'\s(?:data-start|data-end|data-message-id|data-message-model-slug|'
 r'data-turn-start-message|data-message-author-role|data-testid|'
 r'data-is-last-node|data-is-only-node|data-scroll-anchor)="[^"]*"')

CITAS = re.compile(r'\s*:contentReference\[oaicite:\d+\]\{index=\d+\}')

def limpia_clases(m):
    tag, pre, val, post = m.group(1), m.group(2), m.group(3), m.group(4)
    nuevo = BASURA_CLASE.sub('', val)
    if MARCADOR.search(pre) or MARCADOR.search(post):
        nuevo = UTILIDADES.sub('', nuevo)
    nuevo = re.sub(r'\s+', ' ', nuevo).strip()
    return f'<{tag}{pre}class="{nuevo}"{post}>' if nuevo else f'<{tag}{pre.rstrip()}{post}>'

def es_css_volcado(txt):
    """Un <p> que en realidad es una hoja de estilos pegada como texto."""
    reglas = len(re.findall(r'\{\s*(?:<br\s*/?>)?\s*[a-z-]+\s*:', txt, re.I))
    return reglas >= 3 and '<br' in txt

def limpiar(h):
    notas = []
    o = h

    n = len(ATRIB_BASURA.findall(h))
    if n: h = ATRIB_BASURA.sub('', h); notas.append(f'{n} atributos de ChatGPT')

    n = len(CITAS.findall(h))
    if n: h = CITAS.sub('', h); notas.append(f'{n} citas :contentReference')

    antes = h
    h = re.sub(r'<(\w+)((?:\s+[\w:-]+="[^"]*")*?\s*)class="([^"]*)"((?:\s+[\w:-]+="[^"]*")*\s*)>',
               limpia_clases, h)
    if h != antes: notas.append('clases de interfaz de IA')

    # hojas de estilo pegadas como texto visible
    quitados = 0
    def _p(m):
        nonlocal quitados
        if es_css_volcado(m.group(1)):
            quitados += 1
            return ''
        return m.group(0)
    h = re.sub(r'<p[^>]*>(.*?)</p>', _p, h, flags=re.S)
    if quitados: notas.append(f'{quitados} bloque(s) de CSS volcado como texto')

    # spans que se han quedado sin ningún atributo: sobran
    antes = h
    h = re.sub(r'<span>\s*(.*?)\s*</span>', r'\1', h, flags=re.S)
    if h != antes: notas.append('spans vacíos desenvueltos')

    # Etiquetas vacías. OJO: solo las que NO llevan atributos.
    #
    # La primera versión de esto borraba cualquier etiqueta vacía y se cargó
    # <div class="eg-fbt-aviso"></div> en la DELTA Max Ultra y la STREAM Ultra X.
    # Ese div está vacío en el HTML A PROPÓSITO: lo rellena el JavaScript del
    # bloque "Comprado conjuntamente". Una etiqueta vacía CON clase o CON id casi
    # siempre es un punto de anclaje para JS, no basura. No se tocan.
    tot = 0
    for _ in range(4):
        h2 = re.sub(r'<(p|span|div|strong|em)>(\s|&nbsp;|<br\s*/?>)*</\1>', '', h)
        if h2 == h: break
        tot += 1; h = h2
    if tot: notas.append('etiquetas vacías sin atributos')

    # cadenas de <br>
    n = len(re.findall(r'(?:<br\s*/?>\s*){3,}', h))
    if n: h = re.sub(r'(?:<br\s*/?>\s*){3,}', '<br />', h); notas.append(f'{n} cadenas de <br>')

    h = re.sub(r'\n{3,}', '\n\n', h)
    return h, notas, len(o) - len(h)
