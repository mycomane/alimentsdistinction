#!/usr/bin/env python3
"""Check preserved design/assets, template structure and PHP/JS syntax."""
from pathlib import Path
import re, shutil, subprocess, os
root = Path(__file__).resolve().parents[1]
theme = root / 'wordpress/aliments-distinction'
source = (root / 'index.html').read_text()
assert re.search(r'<style>(.*?)</style>', source, re.S)[1] == (theme / 'assets/css/design.css').read_text(), 'Official design CSS changed'
for path in (theme / 'assets/images').iterdir():
    assert path.read_bytes() == (root / path.name).read_bytes(), f'Asset differs: {path.name}'
for name in ['style.css', 'index.php', 'functions.php', 'header.php', 'footer.php', 'front-page.php', 'page.php', 'woocommerce.php', 'page-templates/cart.php', 'page-templates/checkout.php']:
    assert (theme / name).is_file(), f'Missing {name}'
for path in theme.rglob('*.php'):
    content = path.read_text()
    assert 'ABSPATH' in content, f'Missing direct-access guard: {path}'
    assert 'href="#"' not in content, f'Placeholder link: {path}'
php = os.environ.get('PHP_BIN') or shutil.which('php')
if not php:
    raise SystemExit('PHP required. Set PHP_BIN to a PHP 8.1+ binary.')
for path in theme.rglob('*.php'):
    subprocess.run([php, '-l', str(path)], check=True, capture_output=True)
subprocess.run(['node', '--check', str(theme / 'assets/js/theme.js')], check=True)
print('PASS: design CSS, 7 original assets, template structure, PHP syntax, JS syntax')
