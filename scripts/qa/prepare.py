#!/usr/bin/env python3
"""Prepare disposable runner-only WordPress. Never accepts a remote site or credentials."""
from pathlib import Path
import os, shutil, urllib.request, zipfile, io, json
repo = Path(__file__).resolve().parents[2]
runner = Path(os.environ['RUNNER_TEMP']).resolve()
root = runner / 'ad-isolated-qa'
if root.exists():
    raise SystemExit('Refusing to reuse an existing test installation')
root.mkdir()
packages = [
    ('wordpress', 'https://wordpress.org/wordpress-7.1.3.zip'),
    ('woocommerce', 'https://downloads.wordpress.org/plugin/woocommerce.11.2.1.zip'),
    ('sqlite-database-integration', 'https://downloads.wordpress.org/plugin/sqlite-database-integration.3.1.0.zip'),
]
for name, url in packages:
    with urllib.request.urlopen(url, timeout=90) as response:
        data = response.read()
    with zipfile.ZipFile(io.BytesIO(data)) as archive:
        for entry in archive.namelist():
            if not (root / entry).resolve().is_relative_to(root):
                raise ValueError('Unsafe archive member')
        archive.extractall(root)
wp = root / 'wordpress'
for name in ['woocommerce', 'sqlite-database-integration']:
    shutil.move(str(root / name), str(wp / 'wp-content/plugins' / name))
shutil.copy(wp / 'wp-content/plugins/sqlite-database-integration/db.copy', wp / 'wp-content/db.php')
shutil.copytree(repo / 'wordpress/aliments-distinction', wp / 'wp-content/themes/aliments-distinction')
(wp / 'wp-content/mu-plugins').mkdir(exist_ok=True)
(wp / 'wp-content/mu-plugins/qa-safety.php').write_text("<?php add_filter('pre_wp_mail','__return_false'); add_filter('pre_http_request', function(){return new WP_Error('qa_network_blocked','External WordPress requests disabled in isolated QA');});")
(wp / 'wp-config.php').write_text("""<?php
define('DB_NAME','qa'); define('DB_USER',''); define('DB_PASSWORD',''); define('DB_HOST','localhost');
define('DB_ENGINE','sqlite'); define('WP_HOME','http://127.0.0.1:8787'); define('WP_SITEURL','http://127.0.0.1:8787');
define('WP_ENVIRONMENT_TYPE','local'); define('WP_DEBUG',true); define('WP_DEBUG_LOG',true); define('WP_DEBUG_DISPLAY',false);
define('WP_HTTP_BLOCK_EXTERNAL',true); define('DISABLE_WP_CRON',true); $table_prefix='adqa_';
if(!defined('ABSPATH')) define('ABSPATH',__DIR__.'/'); require_once ABSPATH.'wp-settings.php';
""")
reference = wp / 'reference'
reference.mkdir()
for name in ['index.html','logo.png','filet.webp','seafood.jpg','volaille.jpg','tartare.webp','desserts.webp','certified-angus.svg']:
    shutil.copy(repo / name, reference / name)
(root / 'router.php').write_text("<?php $path=parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH); if ($path!=='/' && is_file(__DIR__.'/wordpress'.$path)) return false; require __DIR__.'/wordpress/index.php';")
print('Prepared disposable WordPress 7.1.3 / WooCommerce 11.2.1; no GoDaddy connection')
