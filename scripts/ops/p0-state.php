<?php
/** Read-only WP state fingerprint and scoped theme header cache invalidation. */
if (PHP_SAPI !== 'cli' || !defined('ABSPATH')) { exit(1); }
if (get_option('siteurl') !== 'https://1302636.us22.myftpupload.com' || get_option('home') !== 'https://1302636.us22.myftpupload.com') { throw new RuntimeException('Exact temporary origin required'); }
if (get_stylesheet() !== 'aliments-distinction' || get_template() !== 'aliments-distinction' || !function_exists('ad_asset') || !defined('WC_VERSION') || WC_VERSION !== '11.2.1') { throw new RuntimeException('Theme/WooCommerce precondition failed'); }
require_once ABSPATH . 'wp-admin/includes/plugin.php';
if (!is_plugin_active('woocommerce/woocommerce.php')) { throw new RuntimeException('WooCommerce must already be active'); }
global $wpdb, $wp_version;
if ($wp_version !== '7.1.3' || version_compare(PHP_VERSION, '8.1', '<')) { throw new RuntimeException('WordPress/PHP version drift; review required'); }
$mode = $args[0] ?? ''; $file = $args[1] ?? ''; $version = $args[2] ?? '';
if (!in_array($version, ['0.1.0', '0.1.1'], true)) { throw new RuntimeException('Expected version required'); }
if ($mode === 'cache') {
    // Only this site's exact theme-header object-cache key. No global cache flush,
    // transient deletion, wp_clean_themes_cache or database option write.
    $key = 'theme-' . md5(get_theme_root('aliments-distinction') . '/aliments-distinction');
    wp_cache_delete($key, 'themes');
    echo "PASS: only the active theme header cache key invalidated.\n";
    return;
}
$home = realpath(getenv('HOME'));
if (!$home || !str_starts_with(realpath(dirname($file)) . '/', $home . '/.ad-private-backups/') || is_link($file)) { throw new RuntimeException('Private baseline path required'); }
if (wp_get_theme()->get('Version') !== $version) { throw new RuntimeException('Theme version/cache mismatch'); }
$state = ['options' => [], 'pages' => [], 'counts' => [], 'tables' => []];
// Hash protected options directly from SQL; values never leave the private host file.
$rows = $wpdb->get_results("SELECT option_name, option_value FROM {$wpdb->options} ORDER BY option_name", ARRAY_A);
foreach ($rows as $row) {
    $name = $row['option_name'];
    if (preg_match('/^(woocommerce_|theme_mods_|sidebars_widgets$|widget_|active_plugins$|stylesheet$|template$|siteurl$|home$|blog_public$|show_on_front$|page_on_front$|page_for_posts$|permalink_structure$|WPLANG$)/', $name)
        || preg_match('/coming.?soon|maintenance/i', $name)) {
        $state['options'][$name] = hash('sha256', $row['option_value']);
    }
}
foreach (['shop' => 6, 'cart' => 7, 'checkout' => 8, 'myaccount' => 9] as $key => $expectedID) {
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
if ($mode === 'capture') {
    if (file_exists($file) || file_put_contents($file, json_encode($state, JSON_THROW_ON_ERROR), LOCK_EX) === false) { throw new RuntimeException('Baseline already exists or unavailable'); }
    chmod($file, 0600);
} elseif ($mode === 'verify') {
    if (json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR) !== $state) { throw new RuntimeException('Protected state changed; no database restoration authorized'); }
} else { throw new RuntimeException('Unknown state operation'); }
echo 'PASS: WP ' . $wp_version . ' / PHP CLI ' . PHP_VERSION . ' / WooCommerce ' . WC_VERSION . ' / theme ' . $version . "; protected state verified.\n";
