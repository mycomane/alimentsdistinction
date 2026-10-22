<?php
/** Targeted WPLANG operations; protected commercial state is always compared. */
if (PHP_SAPI !== 'cli' || !defined('ABSPATH')) { exit(1); }
function ad_locale_identity(bool $isolated=false):void {
 global $wp_version;
 $origin=$isolated?'http://127.0.0.1:8787':'https://1302636.us22.myftpupload.com';
 if(get_option('siteurl')!==$origin||get_option('home')!==$origin||($isolated&&wp_get_environment_type()!=='local'))throw new RuntimeException('Exact authorized identity required');
 if($wp_version!=='7.1.3'||version_compare(PHP_VERSION,'8.1','<')||!defined('WC_VERSION')||WC_VERSION!=='11.2.1'||get_stylesheet()!=='aliments-distinction'||get_template()!=='aliments-distinction'||wp_get_theme()->get('Version')!=='0.1.2')throw new RuntimeException('Locale environment/version drift');
 require_once ABSPATH.'wp-admin/includes/plugin.php';if(!is_plugin_active('woocommerce/woocommerce.php'))throw new RuntimeException('WooCommerce must already be active');
 if(defined('WPLANG')&&WPLANG!=='')throw new RuntimeException('Configuration constant controls locale; stop for review');
 if(WP_LANG_DIR!==ABSPATH.'wp-content/languages')throw new RuntimeException('Custom language root; review required');
}
function ad_locale_raw():?array {
 global $wpdb;return $wpdb->get_row("SELECT option_value, autoload FROM {$wpdb->options} WHERE option_name='WPLANG'",ARRAY_A);
}
function ad_locale_protected(array $pageIDs=['shop'=>6,'cart'=>7,'checkout'=>8,'myaccount'=>9]):array {
 global $wpdb;
$state = ['options' => [], 'pages' => [], 'counts' => [], 'tables' => []];
// Hash protected options directly from SQL; values never leave the private host file.
$rows = $wpdb->get_results("SELECT option_name, option_value, autoload FROM {$wpdb->options} ORDER BY option_name", ARRAY_A);
foreach ($rows as $row) {
    $name = $row['option_name'];
    if (preg_match('/^(woocommerce_|theme_mods_|sidebars_widgets$|widget_|active_plugins$|stylesheet$|template$|siteurl$|home$|blog_public$|show_on_front$|page_on_front$|page_for_posts$|permalink_structure$)/', $name)
        || preg_match('/coming.?soon|maintenance/i', $name)) {
        $state['options'][$name] = hash('sha256', serialize([$row['option_value'], $row['autoload']]));
    }
}
foreach ($pageIDs as $key => $expectedID) {
    $id = (int) get_option('woocommerce_' . $key . '_page_id');
    $page = get_post($id, ARRAY_A);
    if ($id !== $expectedID || !$page || $page['post_status'] !== 'publish' || $page['post_type'] !== 'page') { throw new RuntimeException('Technical page drift; refusing update'); }
    $state['pages'][$key] = hash('sha256', serialize([$page, get_post_meta($id)]));
}
foreach (['product', 'product_variation', 'shop_order', 'page'] as $type) {
    $state['counts'][$type] = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type=%s", $type));
}
$state['counts']['users'] = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->users}");
foreach (['wc_orders', 'woocommerce_shipping_zones', 'woocommerce_shipping_zone_locations', 'woocommerce_shipping_zone_methods', 'woocommerce_tax_rates', 'woocommerce_tax_rate_locations'] as $suffix) {
    $table = $wpdb->prefix . $suffix;
    if ($wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($table))) !== $table) { $state['tables'][$suffix] = null; continue; }
    $safe = '`' . str_replace('`', '``', $table) . '`';
    if ($suffix === 'wc_orders') { $state['counts']['hpos'] = (int) $wpdb->get_var("SELECT COUNT(*) FROM $safe"); continue; }
    $hashes = array_map(static fn($row) => hash('sha256', serialize($row)), $wpdb->get_results("SELECT * FROM $safe", ARRAY_A));
    sort($hashes); $state['tables'][$suffix] = hash('sha256', serialize($hashes));
}
foreach (WC()->payment_gateways()->payment_gateways() as $gateway) {
    if ($gateway->enabled === 'yes') { throw new RuntimeException('Enabled gateway detected; review required'); }
}
return $state;
}
function ad_locale_capture(array $pageIDs=['shop'=>6,'cart'=>7,'checkout'=>8,'myaccount'=>9]):array {
 $raw=ad_locale_raw();if(get_locale()!=='en_US'||($raw!==null&&!in_array($raw['option_value'],['','en_US'],true)))throw new RuntimeException('Expected English baseline only');
 return ['locale_before'=>'en_US','WPLANG'=>$raw,'protected'=>ad_locale_protected($pageIDs)];
}
function ad_locale_activate(array $baseline,array $pageIDs=['shop'=>6,'cart'=>7,'checkout'=>8,'myaccount'=>9]):void {
 if(ad_locale_raw()!==$baseline['WPLANG']||ad_locale_protected($pageIDs)!==$baseline['protected'])throw new RuntimeException('Pre-locale protected state drift');
 $old=$baseline['WPLANG'];$autoload=$old===null||in_array($old['autoload'],['yes','on','auto','auto-on'],true);
 if(!update_option('WPLANG','fr_CA',$autoload)||ad_locale_raw()['option_value']!=='fr_CA')throw new RuntimeException('WPLANG activation failed');
 if(ad_locale_protected($pageIDs)!==$baseline['protected'])throw new RuntimeException('Unexpected protected change after language update');
}
function ad_locale_restore(array $baseline):void {
 global $wpdb;$now=ad_locale_raw();
 if($now===$baseline['WPLANG'])return;
 if($now===null||$now['option_value']!=='fr_CA')throw new RuntimeException('Unknown WPLANG change; no blind rollback');
 if($baseline['WPLANG']===null){if(!delete_option('WPLANG'))throw new RuntimeException('Cannot remove newly created WPLANG');}
 else{
  $old=$baseline['WPLANG'];update_option('WPLANG',$old['option_value']);
  if($wpdb->update($wpdb->options,['autoload'=>$old['autoload']],['option_name'=>'WPLANG'],['%s'],['%s'])===false)throw new RuntimeException('Cannot restore original WPLANG autoload');
  // Exact per-site option cache keys required for the targeted option restoration.
  wp_cache_delete('WPLANG','options');wp_cache_delete('alloptions','options');wp_cache_delete('notoptions','options');
 }
 if(ad_locale_raw()!==$baseline['WPLANG'])throw new RuntimeException('WPLANG original row not restored exactly');
}
function ad_locale_verify(array $baseline,string $phase,array $pageIDs=['shop'=>6,'cart'=>7,'checkout'=>8,'myaccount'=>9]):void {
 if(!in_array($phase,['old','new'],true)||ad_locale_protected($pageIDs)!==$baseline['protected'])throw new RuntimeException('Locale verification/protected state mismatch');
 if($phase==='old'){
  if(ad_locale_raw()!==$baseline['WPLANG']||get_locale()!=='en_US')throw new RuntimeException('Old locale not restored');
 }elseif((ad_locale_raw()['option_value']??null)!=='fr_CA'||get_locale()!=='fr_CA'||__('Add to cart','woocommerce')!=='Ajouter au panier'||__('Choose an option','woocommerce')!=='Choisir une option')throw new RuntimeException('Effective French locale/labels not verified');
}
function ad_locale_audit():array {
 $packs=[];foreach(['fr_CA','fr_FR'] as $locale){foreach(['core'=>WP_LANG_DIR.'/'.$locale,'woocommerce'=>WP_LANG_DIR.'/plugins/woocommerce-'.$locale] as $domain=>$stem){$packs[$locale][$domain]=['mo'=>is_file($stem.'.mo'),'l10n_php'=>is_file($stem.'.l10n.php')];}}
 return ['locale'=>get_locale(),'packs_by_explicit_locale'=>$packs,'add_to_cart'=>__('Add to cart','woocommerce'),'choose_option'=>__('Choose an option','woocommerce'),'stored_checkout_privacy_has_english_marker'=>stripos((string)get_option('woocommerce_checkout_privacy_policy_text',''),'Your personal data')!==false];
}
