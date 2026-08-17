#!/bin/sh
# Mete portada.css dentro del snippet y deja el fichero listo para pegar en
# Code Snippets. Se edita portada.css, se ejecuta esto, y se pega el resultado.
set -e
cd "$(dirname "$0")"

# El nowdoc <<<'CSS' se rompe si alguna linea del CSS empieza por CSS;
if grep -q '^CSS;' portada.css; then
  echo "ERROR: portada.css tiene una linea que empieza por CSS; y romperia el nowdoc"
  exit 1
fi

python3 - <<'PY'
php = open('snippet-portada.php', encoding='utf-8').read()
css = open('portada.css', encoding='utf-8').read().rstrip('\n')
marca = '/*EG_CSS_AQUI*/'
if marca not in php:
    raise SystemExit('ERROR: no encuentro la marca ' + marca)
open('snippet-portada-listo.php', 'w', encoding='utf-8').write(php.replace(marca, css))
print('escrito snippet-portada-listo.php')
PY

php -l snippet-portada-listo.php
printf 'tamano: %s bytes\n' "$(wc -c < snippet-portada-listo.php)"
