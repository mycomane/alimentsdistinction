<?php
/** Runner-only state for two newsletter branches; never accepts a hosted path. */
$root=getenv('RUNNER_TEMP').'/ad-isolated-qa/wordpress';
$_SERVER['HTTP_HOST']='127.0.0.1:8787';$_SERVER['REQUEST_METHOD']='GET';
require $root.'/wp-load.php';
if(wp_get_environment_type()!=='local'||WP_HOME!=='http://127.0.0.1:8787'||get_option('home')!==WP_HOME||get_option('siteurl')!==WP_HOME)throw new RuntimeException('Isolated fixture identity required');
$mode=$argv[1]??'';
if($mode==='locale'){
 if(!in_array('fr_CA',get_available_languages(),true)||!update_option('WPLANG','fr_CA'))throw new RuntimeException('Isolated fr_CA activation failed');
}elseif($mode==='unavailable'){remove_theme_mod('ad_newsletter_url');}
elseif($mode==='configured'){set_theme_mod('ad_newsletter_url','http://127.0.0.1:8787/newsletter-preview');}
else throw new RuntimeException('Unknown isolated fixture mode');
echo "PASS: local newsletter fixture mode.\n";
