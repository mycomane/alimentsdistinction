<?php
/** Only switch the disposable site after the existing English fixture suite. */
$_SERVER['HTTP_HOST'] = '127.0.0.1:8787';
$_SERVER['REQUEST_METHOD'] = 'GET';
require getenv('RUNNER_TEMP').'/ad-isolated-qa/wordpress/wp-load.php';
if (wp_get_environment_type() !== 'local' || get_option('siteurl') !== 'http://127.0.0.1:8787') {
    throw new RuntimeException('Disposable loopback site required');
}
update_option('WPLANG', 'fr_FR');
echo "French locale selected in isolated database only.\n";
