<?php
/** WPLANG-only change in the disposable runner. No stored content rewrite. */
$_SERVER['HTTP_HOST']='127.0.0.1:8787';$_SERVER['REQUEST_METHOD']='GET';
require getenv('RUNNER_TEMP').'/ad-isolated-qa/wordpress/wp-load.php';
if(wp_get_environment_type()!=='local'||get_option('siteurl')!=='http://127.0.0.1:8787')throw new RuntimeException('Isolated loopback required');
update_option('WPLANG','fr_CA');
echo "PASS: isolated WPLANG fr_CA selected; no business/contents change.\n";
