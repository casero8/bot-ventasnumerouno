#!/usr/bin/env bash
# Analiza un canal de YouTube: ordena sus vídeos por múltiplo sobre la mediana
# del propio canal, que es la métrica que aísla el efecto del gancho del
# efecto del tamaño del canal.
#
#   ./analizar-canal.sh @handle [n_videos]
#   ./analizar-canal.sh UCxxxxxxxxxxxxxxxxxxxxxx [n_videos]
#
# Requiere: yt-dlp, python3.  Solo lee metadatos públicos (no transcripciones).
set -uo pipefail

CH="${1:?Uso: $0 <@handle|channel_id> [n_videos]}"
N="${2:-120}"

command -v yt-dlp  >/dev/null || { echo "Falta yt-dlp: pip3 install yt-dlp" >&2; exit 1; }
command -v python3 >/dev/null || { echo "Falta python3" >&2; exit 1; }

case "$CH" in
  UC*) BASE="https://www.youtube.com/channel/${CH}" ;;
  @*)  BASE="https://www.youtube.com/${CH}" ;;
  *)   BASE="https://www.youtube.com/@${CH}" ;;
esac

TMP="$(mktemp -d)"
trap 'rm -rf "$TMP"' EXIT

echo "Extrayendo hasta ${N} vídeos de ${BASE} ..." >&2
for tab in videos shorts; do
  timeout 300 yt-dlp --flat-playlist --no-warnings --ignore-errors \
    --playlist-end "$N" \
    --print "${tab}|%(view_count)s|%(id)s|%(duration)s|%(title)s" \
    "${BASE}/${tab}" 2>/dev/null >> "$TMP/raw.txt" || true
done

if [ ! -s "$TMP/raw.txt" ]; then
  echo "Sin resultados. Comprueba el handle, o YouTube está limitando esta IP (429)." >&2
  exit 1
fi

python3 - "$TMP/raw.txt" <<'PY'
import sys, statistics as st
rows=[]
for ln in open(sys.argv[1], encoding="utf-8"):
    p = ln.rstrip("\n").split("|", 4)
    if len(p) < 5: continue
    tab, vc, vid, dur, title = p
    try: vc = int(vc)
    except: continue
    try: dur = int(float(dur))
    except: dur = 0
    rows.append(dict(tab=tab, vc=vc, vid=vid, dur=dur, title=title))

if not rows:
    print("Sin filas utilizables."); sys.exit(0)

for tab in ("videos", "shorts"):
    sub = [r for r in rows if r["tab"] == tab]
    if len(sub) < 5: continue
    med = st.median([r["vc"] for r in sub]) or 1
    for r in sub: r["m"] = r["vc"] / med
    sub.sort(key=lambda r: -r["m"])
    label = "LARGOS" if tab == "videos" else "SHORTS"
    print(f"\n{'='*78}")
    print(f"{label}  n={len(sub)}  mediana={int(med):,}  "
          f"max={sub[0]['vc']:,} (x{sub[0]['m']:.1f})  min={sub[-1]['vc']:,}")
    print(f"{'='*78}")
    k = max(3, min(10, len(sub)//8))
    print(f"\n  GANADORES (qué repetir)")
    for r in sub[:k]:
        print(f"   x{r['m']:6.1f} {r['vc']:>10,} {r['dur']//60:>4}min  {r['title'][:70]}")
    print(f"\n  FRACASOS (qué evitar)")
    for r in sub[-k:]:
        print(f"   x{r['m']:6.2f} {r['vc']:>10,} {r['dur']//60:>4}min  {r['title'][:70]}")
    top_d = st.median([r["dur"] for r in sub[:k]]) / 60
    bot_d = st.median([r["dur"] for r in sub[-k:]]) / 60
    print(f"\n  Duración mediana — ganadores: {top_d:.0f}min | fracasos: {bot_d:.0f}min")

print("\nLeer el resultado: compara los títulos de arriba con los de abajo y busca")
print("qué cambia en la ESTRUCTURA (sujeto, vehículo, promesa), no en el tema.")
print("Ver references/hallazgos-dataset.md para las cuatro leyes medidas.")
PY
