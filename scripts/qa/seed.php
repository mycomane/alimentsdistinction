<?php
/** Only the fresh runner database is seeded. No remote credentials are used. */
$root = realpath(getenv('RUNNER_TEMP') . '/ad-isolated-qa/wordpress');
if (!$root || !is_file($root.'/wp-content/db.php')) { throw new RuntimeException('Missing disposable test root'); }
$_SERVER['HTTP_HOST']='127.0.0.1:8787'; $_SERVER['REQUEST_METHOD']='GET';
define('WP_INSTALLING',true);
require $root.'/wp-load.php';
if (wp_get_environment_type()!=='local' || (!defined('WP_HOME') || WP_HOME!=='http://127.0.0.1:8787')) { throw new RuntimeException('Invalid test identity'); }
require_once ABSPATH.'wp-admin/includes/upgrade.php';
wp_install('Aliments Distinction — QA ISOLÉE','isolated-review','nobody@example.test',false,'',bin2hex(random_bytes(24)));
require_once ABSPATH.'wp-admin/includes/plugin.php';
activate_plugin('woocommerce/woocommerce.php');
echo "Installed runner-only WordPress and activated runner-only WooCommerce; emails and external WP requests blocked.\n";
