<?php
/** Real filesystem tests of the deployed helper, entirely under system temp. */
require __DIR__ . '/../scripts/ops/p0-files.php';
$base = sys_get_temp_dir() . '/ad-p0-test-' . bin2hex(random_bytes(8));
mkdir($base, 0700); mkdir($base . '/theme');
$live = $base . '/theme/style.css'; $new = $base . '/new.css'; $old = $base . '/old.css';
$a = hash('sha256', 'before'); $b = hash('sha256', 'after'); $count = 0;
function ok($value) { global $count; if (!$value) { throw new RuntimeException('Assertion failed'); } ++$count; }
function refused($call) { try { $call(); } catch (RuntimeException $e) { ok(true); return; } throw new RuntimeException('Unsafe operation was accepted'); }
try {
    p0_coming_soon('<h1>Coming Soon</h1>'); ok(true);
    refused(fn() => p0_coming_soon('<script>var s="coming soon";</script><h1>Open shop</h1>'));
    refused(fn() => p0_coming_soon('<style>.coming soon {}</style><h1>Open shop</h1>'));
    file_put_contents($live, 'before'); chmod($live, 0644);
    file_put_contents($new, 'after'); file_put_contents($old, 'before');
    p0_check($base . '/theme', ['style.css' => $a]); ok(true);
    refused(fn() => p0_replace($new, $live, $b, $b));
    ok(file_get_contents($live) === 'before' && file_exists($new));
    refused(fn() => p0_replace($new, $live, $a, $a));
    p0_replace($new, $live, $a, $b);
    ok(!file_exists($new) && file_get_contents($live) === 'after');
    ok((fileperms($live) & 0777) === 0644);
    p0_check($base . '/theme', ['style.css' => $b]); ok(true);
    // Simulate failed post-update checks: restore the prior bytes atomically.
    p0_replace($old, $live, $b, $a);
    p0_check($base . '/theme', ['style.css' => $a]); ok(true);
    file_put_contents($base . '/theme/extra.txt', 'unexpected');
    refused(fn() => p0_check($base . '/theme', ['style.css' => $a])); unlink($base . '/theme/extra.txt');
    symlink($live, $base . '/theme/link');
    refused(fn() => p0_check($base . '/theme', ['style.css' => $a])); unlink($base . '/theme/link');
    link($live, $base . '/hardlink');
    refused(fn() => p0_check($base . '/theme', ['style.css' => $a])); unlink($base . '/hardlink');
    symlink($base . '/theme', $base . '/linked-theme');
    refused(fn() => p0_check($base . '/linked-theme', ['style.css' => $a])); unlink($base . '/linked-theme');
    file_put_contents($new, 'after'); symlink($live, $base . '/link.css');
    refused(fn() => p0_replace($new, $base . '/link.css', $a, $b)); unlink($base . '/link.css');
    p0_check($base . '/theme', ['style.css' => $a]); ok(true);
    echo "PASS: $count real filesystem guards/update/rollback assertions; no WordPress or remote access.\n";
} finally {
    foreach (glob($base . '/theme/*') as $file) { unlink($file); }
    rmdir($base . '/theme'); foreach (glob($base . '/*') as $file) { unlink($file); } rmdir($base);
}
