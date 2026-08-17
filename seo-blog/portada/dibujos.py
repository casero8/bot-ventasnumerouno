# -*- coding: utf-8 -*-
"""Ilustraciones planas para la vista previa. En la web real van las fotos."""

def _wrap(inner, c1, c2, uid):
    return (f'<svg viewBox="0 0 300 300" role="img" aria-hidden="true" style="width:100%;height:100%">'
            f'<defs><linearGradient id="g{uid}" x1="0" y1="0" x2="1" y2="1">'
            f'<stop offset="0" stop-color="{c1}"/><stop offset="1" stop-color="{c2}"/>'
            f'</linearGradient></defs>'
            f'<rect width="300" height="300" fill="url(#g{uid})"/>{inner}</svg>')

_n = [0]
def _uid():
    _n[0] += 1
    return _n[0]

def estacion(c1="#eaf1f9", c2="#cfdeee"):
    i = ('<rect x="72" y="92" width="156" height="116" rx="14" fill="#f7f9fc"/>'
         '<rect x="72" y="92" width="156" height="116" rx="14" fill="none" stroke="#c3d1e0" stroke-width="3"/>'
         '<rect x="112" y="74" width="76" height="20" rx="9" fill="#22303f"/>'
         '<rect x="90" y="108" width="72" height="40" rx="6" fill="#12222f"/>'
         '<rect x="99" y="118" width="34" height="7" rx="3" fill="#3ddc84"/>'
         '<rect x="99" y="131" width="20" height="6" rx="3" fill="#5b6b7a"/>'
         '<circle cx="192" cy="128" r="19" fill="#e6ecf3" stroke="#b9c8d8" stroke-width="3"/>'
         '<rect x="186" y="120" width="4" height="9" rx="2" fill="#4a5b6b"/>'
         '<rect x="195" y="120" width="4" height="9" rx="2" fill="#4a5b6b"/>'
         '<rect x="90" y="164" width="120" height="10" rx="5" fill="#dde5ee"/>'
         '<rect x="90" y="182" width="76" height="10" rx="5" fill="#dde5ee"/>')
    return _wrap(i, c1, c2, _uid())

def panel(c1="#e8f0fb", c2="#c8dcf3"):
    i = ('<g transform="rotate(-8 150 150)">'
         '<rect x="58" y="96" width="184" height="112" rx="8" fill="#123a63"/>'
         '<rect x="66" y="104" width="168" height="96" rx="4" fill="#1c5b96"/>'
         '<g stroke="#8fc2ee" stroke-width="2.5" opacity=".8">'
         '<line x1="122" y1="104" x2="122" y2="200"/><line x1="178" y1="104" x2="178" y2="200"/>'
         '<line x1="66" y1="136" x2="234" y2="136"/><line x1="66" y1="168" x2="234" y2="168"/></g>'
         '</g>'
         '<circle cx="228" cy="72" r="24" fill="#ffb020"/>'
         '<g stroke="#ffb020" stroke-width="5" stroke-linecap="round">'
         '<line x1="228" y1="30" x2="228" y2="40"/><line x1="264" y1="72" x2="274" y2="72"/>'
         '<line x1="256" y1="44" x2="263" y2="37"/></g>')
    return _wrap(i, c1, c2, _uid())

def powerbank(c1="#f0ecfb", c2="#dcd3f3"):
    i = ('<rect x="106" y="66" width="88" height="168" rx="20" fill="#2b3442"/>'
         '<rect x="114" y="74" width="72" height="152" rx="15" fill="#3b4756"/>'
         '<rect x="130" y="96" width="40" height="52" rx="8" fill="#161d26"/>'
         '<g fill="#3ddc84"><circle cx="140" cy="170" r="5"/><circle cx="154" cy="170" r="5"/>'
         '<circle cx="168" cy="170" r="5"/></g>'
         '<rect x="134" y="196" width="32" height="9" rx="4" fill="#5f6c7c"/>')
    return _wrap(i, c1, c2, _uid())

def exo(c1="#fff0e2", c2="#ffd8b8"):
    i = ('<circle cx="150" cy="66" r="20" fill="#2b3442"/>'
         '<rect x="126" y="92" width="48" height="54" rx="14" fill="#33404f"/>'
         '<rect x="102" y="140" width="96" height="24" rx="12" fill="#ff6a13"/>'
         '<circle cx="116" cy="152" r="13" fill="#22303f"/><circle cx="184" cy="152" r="13" fill="#22303f"/>'
         '<rect x="112" y="164" width="18" height="72" rx="9" fill="#33404f"/>'
         '<rect x="170" y="164" width="18" height="72" rx="9" fill="#33404f"/>'
         '<rect x="108" y="196" width="26" height="12" rx="6" fill="#ff6a13"/>'
         '<rect x="166" y="196" width="26" height="12" rx="6" fill="#ff6a13"/>')
    return _wrap(i, c1, c2, _uid())

def casa(c1="#e6f5ec", c2="#c6e7d5"):
    i = ('<path d="M62 148 L150 84 L238 148 Z" fill="#25405c"/>'
         '<rect x="86" y="148" width="128" height="86" fill="#f6f9fc"/>'
         '<rect x="86" y="148" width="128" height="86" fill="none" stroke="#c3d1e0" stroke-width="3"/>'
         '<rect x="108" y="172" width="34" height="30" fill="#8fc2ee"/>'
         '<rect x="162" y="172" width="34" height="62" fill="#25405c"/>'
         '<g transform="rotate(-26 176 116)"><rect x="146" y="98" width="70" height="40" rx="4" fill="#1c5b96"/>'
         '<g stroke="#8fc2ee" stroke-width="2"><line x1="169" y1="98" x2="169" y2="138"/>'
         '<line x1="192" y1="98" x2="192" y2="138"/><line x1="146" y1="118" x2="216" y2="118"/></g></g>')
    return _wrap(i, c1, c2, _uid())

def cable(c1="#eef1f5", c2="#d7dde5"):
    i = ('<path d="M74 96 C150 96 150 204 226 204" fill="none" stroke="#2b3442" stroke-width="16" stroke-linecap="round"/>'
         '<rect x="52" y="80" width="34" height="32" rx="7" fill="#ff6a13"/>'
         '<rect x="214" y="188" width="34" height="32" rx="7" fill="#25405c"/>')
    return _wrap(i, c1, c2, _uid())

def stream(c1="#e9f1fc", c2="#cadcf2"):
    i = ('<rect x="78" y="102" width="144" height="96" rx="12" fill="#f7f9fc" stroke="#c3d1e0" stroke-width="3"/>'
         '<path d="M156 116 L124 158 L146 158 L138 190 L176 144 L152 144 Z" fill="#ff6a13"/>'
         '<rect x="96" y="212" width="108" height="10" rx="5" fill="#dde5ee"/>')
    return _wrap(i, c1, c2, _uid())

def generador(c1="#eaf1f9", c2="#d0e2f2"):
    i = ('<rect x="52" y="132" width="112" height="82" rx="12" fill="#f7f9fc" stroke="#c3d1e0" stroke-width="3"/>'
         '<rect x="68" y="150" width="48" height="30" rx="5" fill="#12222f"/>'
         '<rect x="74" y="158" width="24" height="6" rx="3" fill="#3ddc84"/>'
         '<g transform="rotate(-14 210 130)"><rect x="160" y="92" width="100" height="70" rx="6" fill="#123a63"/>'
         '<rect x="166" y="98" width="88" height="58" rx="3" fill="#1c5b96"/>'
         '<g stroke="#8fc2ee" stroke-width="2"><line x1="196" y1="98" x2="196" y2="156"/>'
         '<line x1="224" y1="98" x2="224" y2="156"/><line x1="166" y1="127" x2="254" y2="127"/></g></g>')
    return _wrap(i, c1, c2, _uid())

def acceso(fn):
    return fn()
