<?php
/** Approved disposable subscriber only. stdout of create is SECRET and must be redirected privately. */
if (PHP_SAPI !== 'cli' || !defined('ABSPATH')) { exit(1); }
$expected = 'https://1302636.us22.myftpupload.com';
$local = defined('AD_REVIEW_ISOLATED_TEST') && AD_REVIEW_ISOLATED_TEST && wp_get_environment_type() === 'local' && get_option('siteurl') === 'http://127.0.0.1:8787';
if (!$local && get_option('siteurl') !== $expected) { throw new RuntimeException('Target identity refused'); }
if (get_stylesheet() !== 'aliments-distinction' || !class_exists('WooCommerce')) { throw new RuntimeException('Expected theme and WooCommerce required'); }
$mode = $args[0] ?? ''; $run = $args[1] ?? '';
if (!preg_match('/^[0-9]+$/D', $run)) { throw new RuntimeException('Numeric run required'); }
$home = realpath($local && defined('AD_REVIEW_TEST_HOME') ? AD_REVIEW_TEST_HOME : getenv('HOME'));
if (!$home || str_starts_with($home . '/', realpath(ABSPATH) . '/')) { throw new RuntimeException('Private home required'); }
$dir = $home . '/.ad-private-qa';
if (is_link($dir)) { throw new RuntimeException('Private directory symlink refused'); }
if (!is_dir($dir) && !mkdir($dir, 0700)) { throw new RuntimeException('Private directory unavailable'); }
chmod($dir, 0700);
$file = $dir . '/review-' . $run . '.json';
if (is_link($file)) { throw new RuntimeException('State symlink refused'); }
require_once ABSPATH . 'wp-admin/includes/user.php';
// No notification or outbound WordPress HTTP request during account provisioning/removal.
add_filter('pre_wp_mail', '__return_true', PHP_INT_MAX);
add_filter('pre_http_request', static function () { return new WP_Error('ad_review_no_http', 'Provisioning HTTP disabled'); }, PHP_INT_MAX);
function ad_review_baseline() {
    $state = ['theme' => get_stylesheet(), 'counts' => [], 'options' => []];
    foreach (['product', 'product_variation', 'shop_order'] as $type) { $state['counts'][$type] = hash('sha256', serialize(wp_count_posts($type))); }
    foreach (['blog_public', 'woocommerce_currency', 'woocommerce_default_country', 'woocommerce_calc_taxes', 'woocommerce_prices_include_tax', 'woocommerce_tax_based_on', 'woocommerce_tax_display_shop', 'woocommerce_tax_display_cart', 'woocommerce_shop_page_id', 'woocommerce_cart_page_id', 'woocommerce_checkout_page_id', 'woocommerce_myaccount_page_id'] as $key) {
        $state['options'][$key] = hash('sha256', serialize(get_option($key, null)));
    }
    global $wpdb;
    $table = $wpdb->prefix . 'wc_orders';
    if ($wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($table))) === $table) {
        $state['hpos_count'] = (int) $wpdb->get_var("SELECT COUNT(*) FROM `" . str_replace('`', '``', $table) . "`");
    }
    return $state;
}
function ad_review_write($file, $state) {
    if (file_put_contents($file, json_encode($state, JSON_THROW_ON_ERROR), LOCK_EX) === false) { throw new RuntimeException('State write failed'); }
    chmod($file, 0600);
}
if ($mode === 'create') {
    if ((!$local && get_option('home') !== $expected) || wp_get_theme()->get('Version') !== '0.1.1' || !defined('WC_VERSION') || WC_VERSION !== '11.2.1') { throw new RuntimeException('Expected deployed P0 version required before provisioning'); }
    if (file_exists($file)) { throw new RuntimeException('Run already initialized; no blind retry'); }
    $role = get_role('subscriber');
    if (!$role || empty($role->capabilities['read'])) { throw new RuntimeException('Subscriber role unavailable'); }
    foreach ($role->capabilities as $cap => $allowed) { if ($allowed && !in_array($cap, ['read', 'level_0'], true)) { throw new RuntimeException('Subscriber has unexpected permissions'); } }
    $login = 'ad-review-' . $run;
    if (username_exists($login) || email_exists($login . '@example.invalid')) { throw new RuntimeException('Review identity already exists'); }
    $state = ['run' => $run, 'login' => $login, 'user_id' => 0, 'expires' => time() + 1800, 'baseline' => ad_review_baseline(), 'cleaned' => false];
    ad_review_write($file, $state);
    $id = wp_insert_user(['user_login' => $login, 'user_pass' => bin2hex(random_bytes(48)), 'user_email' => $login . '@example.invalid', 'display_name' => 'Recette temporaire AD', 'role' => 'subscriber', 'meta_input' => ['_ad_review_run' => $run]]);
    if (is_wp_error($id)) { throw new RuntimeException('Review user creation failed'); }
    $state['user_id'] = (int) $id; ad_review_write($file, $state);
    $user = get_user_by('id', $id);
    if ($user->roles !== ['subscriber'] || user_can($id, 'manage_options') || user_can($id, 'edit_posts') || user_can($id, 'manage_woocommerce')) { throw new RuntimeException('Unexpected review access'); }
    // Native WP session, only for this new subscriber; no existing user's authentication is reused.
    $token = WP_Session_Tokens::get_instance($id)->create($state['expires']);
    $cookie = wp_generate_auth_cookie($id, $state['expires'], 'logged_in', $token);
    $pages = [];
    foreach (['shop', 'cart', 'checkout', 'myaccount'] as $key) {
        $page = (int) get_option('woocommerce_' . $key . '_page_id');
        if (!$page || get_post_status($page) !== 'publish') { throw new RuntimeException('Required technical page missing'); }
        $url = get_permalink($page);
        if (!$local && (wp_parse_url($url, PHP_URL_HOST) !== wp_parse_url($expected, PHP_URL_HOST) || wp_parse_url($url, PHP_URL_SCHEME) !== 'https')) { throw new RuntimeException('Unexpected technical page origin'); }
        $pages[$key] = $url;
    }
    // SECRET output: the workflow redirects this stream to a private runner file, never logs it.
    echo json_encode(['expires' => $state['expires'], 'cookie' => ['name' => LOGGED_IN_COOKIE, 'value' => $cookie], 'pages' => $pages], JSON_THROW_ON_ERROR);
} elseif ($mode === 'cleanup') {
    if (!file_exists($file)) { echo "PASS: no account state created for this run.\n"; return; }
    $state = json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
    if (($state['run'] ?? '') !== $run || ($state['login'] ?? '') !== 'ad-review-' . $run) { throw new RuntimeException('State identity refused'); }
    $user = get_user_by('login', $state['login']);
    if ($user) {
        if (get_user_meta($user->ID, '_ad_review_run', true) !== $run) { throw new RuntimeException('Account ownership marker missing; deletion refused'); }
        if (!empty($state['user_id']) && (int) $user->ID !== (int) $state['user_id']) { throw new RuntimeException('Account id mismatch'); }
        if ($user->roles !== ['subscriber']) { WP_Session_Tokens::get_instance($user->ID)->destroy_all(); throw new RuntimeException('Role changed; sessions revoked, deletion refused'); }
        WP_Session_Tokens::get_instance($user->ID)->destroy_all();
        wp_set_password(bin2hex(random_bytes(48)), $user->ID);
        global $wpdb;
        if ((int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_author=%d", $user->ID)) !== 0) { throw new RuntimeException('Unexpected authored content; access revoked, deletion refused'); }
        if (!wp_delete_user($user->ID)) { throw new RuntimeException('Review user removal failed'); }
    }
    if (username_exists($state['login'])) { throw new RuntimeException('Account still present'); }
    $unchanged = ad_review_baseline() === $state['baseline'];
    $state['cleaned'] = true; $state['cleanup_time'] = time(); ad_review_write($file, $state);
    echo "PASS: temporary subscriber removed and sessions revoked.\n";
    if (!$unchanged) { throw new RuntimeException('Protected baseline changed; investigate concurrent writes'); }
    echo "PASS: protected settings and product/order counts unchanged.\n";
} else { throw new RuntimeException('Unknown review operation'); }
