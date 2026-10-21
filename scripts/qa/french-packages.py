#!/usr/bin/env python3
"""Official French packages in the disposable runner only; no hosted credentials."""
import io, os, zipfile, urllib.request
from pathlib import Path
root = Path(os.environ['RUNNER_TEMP']) / 'ad-isolated-qa/wordpress'
if not (root / 'wp-config.php').is_file():
    raise SystemExit('Disposable installation required')
for target, url in [
    (root / 'wp-content/languages', 'https://downloads.wordpress.org/translation/core/7.1.3/fr_FR.zip'),
    (root / 'wp-content/languages/plugins', 'https://downloads.wordpress.org/translation/plugin/woocommerce/11.2.1/fr_FR.zip'),
]:
    target.mkdir(parents=True, exist_ok=True)
    with urllib.request.urlopen(url, timeout=90) as response:
        data = response.read()
    with zipfile.ZipFile(io.BytesIO(data)) as archive:
        for name in archive.namelist():
            if not (target / name).resolve().is_relative_to(target.resolve()):
                raise ValueError('Unsafe translation archive')
        archive.extractall(target)
print('Official fr_FR core and WooCommerce packages installed locally only')
