# -*- coding: utf-8 -*-
"""Ilustraciones a sangre para la vista previa. En la web real van las fotos.
   Composicion pensada para object-fit: cover, con el sujeto grande y centrado."""

_n = [0]
def _u():
    _n[0] += 1
    return _n[0]

def _svg(inner, defs=""):
    return (f'<svg viewBox="0 0 400 320" preserveAspectRatio="xMidYMid slice" '
            f'role="img" aria-hidden="true" style="width:100%;height:100%">{defs}{inner}</svg>')

def _fondo(c1, c2, uid, ang=("0","0","1","1")):
    x1,y1,x2,y2 = ang
    return (f'<defs><linearGradient id="f{uid}" x1="{x1}" y1="{y1}" x2="{x2}" y2="{y2}">'
            f'<stop offset="0" stop-color="{c1}"/><stop offset="1" stop-color="{c2}"/></linearGradient></defs>'
            f'<rect width="400" height="320" fill="url(#f{uid})"/>')

def estacion(c1="#1b4f88", c2="#0a2murky"):
    u = _u()
    d = _fondo(c1, "#071f3c", u)
    i = (d +
      '<circle cx="330" cy="70" r="120" fill="#ffffff" opacity=".05"/>'
      '<rect x="96" y="98" width="208" height="150" rx="18" fill="#f4f7fb"/>'
      '<rect x="96" y="98" width="208" height="150" rx="18" fill="none" stroke="#ccd8e6" stroke-width="3"/>'
      '<rect x="152" y="76" width="96" height="24" rx="11" fill="#1d2a38"/>'
      '<rect x="118" y="120" width="96" height="52" rx="8" fill="#0d1a26"/>'
      '<rect x="130" y="134" width="46" height="9" rx="4" fill="#37e08a"/>'
      '<rect x="130" y="150" width="26" height="8" rx="4" fill="#5d6f80"/>'
      '<circle cx="256" cy="146" r="25" fill="#e7edf4" stroke="#b7c6d6" stroke-width="3"/>'
      '<rect x="248" y="136" width="5" height="12" rx="2.5" fill="#465768"/>'
      '<rect x="260" y="136" width="5" height="12" rx="2.5" fill="#465768"/>'
      '<rect x="118" y="192" width="160" height="12" rx="6" fill="#dbe4ee"/>'
      '<rect x="118" y="214" width="98" height="12" rx="6" fill="#dbe4ee"/>')
    return _svg(i)

def panel(c1="#0f7bd6", c2="#053b70"):
    u = _u()
    i = (_fondo(c1, c2, u) +
      '<circle cx="322" cy="66" r="42" fill="#ffc233"/>'
      '<g stroke="#ffc233" stroke-width="6" stroke-linecap="round" opacity=".85">'
      '<line x1="322" y1="4" x2="322" y2="16"/><line x1="380" y1="66" x2="392" y2="66"/>'
      '<line x1="366" y1="24" x2="374" y2="16"/></g>'
      '<g transform="rotate(-9 190 180)">'
      '<rect x="42" y="112" width="296" height="152" rx="10" fill="#0b2c50"/>'
      '<rect x="52" y="122" width="276" height="132" rx="5" fill="#1663ad"/>'
      '<g stroke="#8cc6f5" stroke-width="3" opacity=".75">'
      '<line x1="144" y1="122" x2="144" y2="254"/><line x1="236" y1="122" x2="236" y2="254"/>'
      '<line x1="52" y1="166" x2="328" y2="166"/><line x1="52" y1="210" x2="328" y2="210"/></g>'
      '</g>')
    return _svg(i)

def powerbank(c1="#5b3df0", c2="#25166e"):
    u = _u()
    i = (_fondo(c1, c2, u) +
      '<circle cx="86" cy="252" r="118" fill="#ffffff" opacity=".06"/>'
      '<rect x="146" y="52" width="112" height="216" rx="26" fill="#20293a"/>'
      '<rect x="156" y="62" width="92" height="196" rx="20" fill="#323e52"/>'
      '<rect x="178" y="90" width="48" height="64" rx="10" fill="#101823"/>'
      '<rect x="188" y="104" width="28" height="8" rx="4" fill="#37e08a"/>'
      '<g fill="#37e08a"><circle cx="182" cy="190" r="6"/><circle cx="202" cy="190" r="6"/>'
      '<circle cx="222" cy="190" r="6"/></g>'
      '<rect x="184" y="222" width="36" height="11" rx="5" fill="#5f6d80"/>')
    return _svg(i)

def exo(c1="#ff7a3d", c2="#c23a00"):
    u = _u()
    i = (_fondo(c1, c2, u) +
      '<circle cx="322" cy="252" r="130" fill="#ffffff" opacity=".08"/>'
      '<circle cx="200" cy="58" r="26" fill="#1b2430"/>'
      '<rect x="168" y="90" width="64" height="66" rx="18" fill="#232f3e"/>'
      '<rect x="134" y="152" width="132" height="30" rx="15" fill="#0f1620"/>'
      '<circle cx="152" cy="167" r="16" fill="#ffb547"/><circle cx="248" cy="167" r="16" fill="#ffb547"/>'
      '<rect x="150" y="182" width="24" height="94" rx="12" fill="#232f3e"/>'
      '<rect x="226" y="182" width="24" height="94" rx="12" fill="#232f3e"/>'
      '<rect x="144" y="224" width="36" height="16" rx="8" fill="#ffb547"/>'
      '<rect x="220" y="224" width="36" height="16" rx="8" fill="#ffb547"/>'
      '<rect x="150" y="276" width="24" height="14" rx="6" fill="#0f1620"/>'
      '<rect x="226" y="276" width="24" height="14" rx="6" fill="#0f1620"/>')
    return _svg(i)

def casa(c1="#12a75f", c2="#04603a"):
    u = _u()
    i = (_fondo(c1, c2, u) +
      '<circle cx="60" cy="52" r="90" fill="#ffffff" opacity=".07"/>'
      '<path d="M70 168 L200 76 L330 168 Z" fill="#12304f"/>'
      '<rect x="104" y="168" width="192" height="120" fill="#f5f8fc"/>'
      '<rect x="104" y="168" width="192" height="120" fill="none" stroke="#c6d3e1" stroke-width="3"/>'
      '<rect x="134" y="200" width="48" height="42" fill="#7fbdf0"/>'
      '<rect x="214" y="200" width="50" height="88" fill="#12304f"/>'
      '<circle cx="254" cy="248" r="4" fill="#ffc233"/>'
      '<g transform="rotate(-27 236 122)">'
      '<rect x="192" y="94" width="104" height="60" rx="5" fill="#0b2c50"/>'
      '<rect x="198" y="100" width="92" height="48" rx="3" fill="#1663ad"/>'
      '<g stroke="#8cc6f5" stroke-width="2.5"><line x1="229" y1="100" x2="229" y2="148"/>'
      '<line x1="259" y1="100" x2="259" y2="148"/><line x1="198" y1="124" x2="290" y2="124"/></g></g>')
    return _svg(i)

def cable(c1="#37475a", c2="#141d29"):
    u = _u()
    i = (_fondo(c1, c2, u) +
      '<path d="M40 88 C170 88 170 232 330 232" fill="none" stroke="#0c1219" stroke-width="26" stroke-linecap="round"/>'
      '<path d="M40 88 C170 88 170 232 330 232" fill="none" stroke="#22303f" stroke-width="16" stroke-linecap="round"/>'
      '<rect x="14" y="66" width="46" height="44" rx="10" fill="#ff5a1f"/>'
      '<rect x="316" y="210" width="46" height="44" rx="10" fill="#8cc6f5"/>')
    return _svg(i)

def stream(c1="#0a6ed1", c2="#062a52"):
    u = _u()
    i = (_fondo(c1, c2, u) +
      '<circle cx="340" cy="270" r="110" fill="#ffffff" opacity=".06"/>'
      '<rect x="100" y="96" width="200" height="140" rx="16" fill="#f5f8fc" stroke="#c6d3e1" stroke-width="3"/>'
      '<path d="M212 116 L162 182 L196 182 L184 226 L242 156 L204 156 Z" fill="#ff5a1f"/>'
      '<rect x="128" y="252" width="144" height="13" rx="6" fill="#ffffff" opacity=".55"/>')
    return _svg(i)

def generador(c1="#1b4f88", c2="#071f3c"):
    u = _u()
    i = (_fondo(c1, c2, u) +
      '<g transform="rotate(-13 268 130)">'
      '<rect x="196" y="76" width="150" height="106" rx="8" fill="#0b2c50"/>'
      '<rect x="204" y="84" width="134" height="90" rx="4" fill="#1663ad"/>'
      '<g stroke="#8cc6f5" stroke-width="2.5"><line x1="249" y1="84" x2="249" y2="174"/>'
      '<line x1="293" y1="84" x2="293" y2="174"/><line x1="204" y1="129" x2="338" y2="129"/></g></g>'
      '<rect x="42" y="152" width="168" height="118" rx="16" fill="#f4f7fb" stroke="#ccd8e6" stroke-width="3"/>'
      '<rect x="64" y="178" width="72" height="44" rx="7" fill="#0d1a26"/>'
      '<rect x="74" y="192" width="34" height="8" rx="4" fill="#37e08a"/>'
      '<circle cx="172" cy="200" r="20" fill="#e7edf4" stroke="#b7c6d6" stroke-width="3"/>')
    return _svg(i)
