<?php
/** Read-only filesystem evidence. Output is restricted to named booleans. */
if (PHP_SAPI !== 'cli') { exit(1); }

function lfa_inventory(string $root): array {
    if (!file_exists($root) && !is_link($root)) {
        if (realpath(dirname($root)) !== dirname($root)) throw new RuntimeException('unsafe');
        return [];
    }
    if (is_link($root) || !is_dir($root) || realpath($root) !== $root) throw new RuntimeException('unsafe');
    $result = [];
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
    foreach ($it as $file) {
        if ($file->isLink() || !$file->isFile() || lstat($file->getPathname())['nlink'] !== 1) throw new RuntimeException('unsafe');
        $result[substr($file->getPathname(), strlen($root) + 1)] = hash_file('sha256', $file->getPathname());
    }
    ksort($result);
    return $result;
}

function lfa_report(string $source, string $root, array $snapshot): array {
    clearstatcache();
    $r = array_fill_keys([
        'source_canonical', 'target_canonical', 'candidate_hashes_match',
        'candidate_single_link', 'candidate_owner_matches_directory',
        'candidate_device_matches_directory', 'target_effectively_writable',
        'target_write_bits_present', 'target_execute_bits_present',
        'existing_targets_owner_matches_candidate', 'existing_targets_device_matches_candidate',
        'existing_targets_single_link', 'inventory_matches_snapshot', 'inventory_has_no_links',
    ], true);
    $r['source_canonical'] = !is_link($source) && is_dir($source) && realpath($source) === $source;
    $base = is_dir($root) ? $root : dirname($root);
    $r['target_canonical'] = !is_link($root) && (!file_exists($root) || is_dir($root)) && realpath($base) === $base;
    if (!$r['source_canonical'] || !$r['target_canonical']) return array_fill_keys(array_keys($r), false);
    $owner = lstat($base);
    foreach ([$base, $root . '/plugins'] as $dir) {
        if (!file_exists($dir) && !is_link($dir)) continue;
        if (is_link($dir) || realpath($dir) !== $dir || !is_dir($dir)) {
            $r['target_canonical'] = false;
            continue;
        }
        $stat = lstat($dir);
        $r['target_effectively_writable'] &= is_writable($dir);
        $r['target_write_bits_present'] &= ($stat['mode'] & 0222) !== 0;
        $r['target_execute_bits_present'] &= ($stat['mode'] & 0111) !== 0;
    }
    foreach ($snapshot['payload']['files'] as $p => $hash) {
        if (!preg_match('~\A(?:(?:(?:admin-|admin-network-|continents-cities-)?fr_CA)|(?:plugins/woocommerce-fr_CA))(?:-[a-f0-9]{32})?\.(?:mo|po|l10n\.php|json)\z~D', $p)
            || !is_string($hash) || !preg_match('/\A[a-f0-9]{64}\z/D', $hash)) throw new RuntimeException('unsafe');
        $src = $source . '/' . $p;
        if (is_link($src) || !is_file($src) || realpath($src) !== $src) {
            $r['source_canonical'] = false;
            $r['candidate_hashes_match'] = false;
            continue;
        }
        $s = lstat($src);
        $r['candidate_hashes_match'] &= hash_file('sha256', $src) === $hash;
        $r['candidate_single_link'] &= $s['nlink'] === 1;
        $r['candidate_owner_matches_directory'] &= $s['uid'] === $owner['uid'];
        $r['candidate_device_matches_directory'] &= $s['dev'] === $owner['dev'];
        $target = $root . '/' . $p;
        if (is_link($target) || (file_exists($target) && (!is_file($target) || realpath($target) !== $target))) {
            $r['target_canonical'] = false;
        } elseif (is_file($target)) {
            $t = lstat($target);
            $r['existing_targets_owner_matches_candidate'] &= $t['uid'] === $s['uid'];
            $r['existing_targets_device_matches_candidate'] &= $t['dev'] === $s['dev'];
            $r['existing_targets_single_link'] &= $t['nlink'] === 1;
        }
    }
    try {
        $inventory = lfa_inventory($root);
        $old = $snapshot['old']; ksort($old);
        $r['inventory_matches_snapshot'] = $inventory === $old;
    } catch (Throwable $e) {
        $r['inventory_matches_snapshot'] = false;
        $r['inventory_has_no_links'] = false;
    }
    return array_map(fn($v) => (bool)$v, $r);
}

// Executed over SSH stdin, without copying a script or making any server file.
if (($argv[1] ?? '') === '--existing-review') {
    set_error_handler(static function () { throw new RuntimeException('unsafe'); });
    try {
        $root = getcwd();
        $home = realpath(getenv('HOME'));
        if (!$home || !$root || realpath($root) !== $root || !is_file($root . '/wp-load.php')) throw new RuntimeException('unsafe');
        $backup = $home . '/.ad-private-backups/locale-fr-ca-39817398299';
        if (realpath($backup) !== $backup || is_link($backup)) throw new RuntimeException('unsafe');
        $sums = $backup . '/SHA256SUMS';
        if (is_link($sums) || realpath($sums) !== $sums || !is_file($sums) || filesize($sums) > 4096) throw new RuntimeException('unsafe');
        $expected = ['theme-0.1.2.tar.gz', 'languages-before.tar.gz', 'database.sql.gz', 'wp-baseline.json', 'language-snapshot.json'];
        $seen = [];
        foreach (explode("\n", rtrim(file_get_contents($sums), "\n")) as $line) {
            if (!preg_match('/\A([a-f0-9]{64})  ([a-zA-Z0-9.-]+)\z/D', $line, $m)
                || !in_array($m[2], $expected, true) || isset($seen[$m[2]])) throw new RuntimeException('unsafe');
            $member = $backup . '/' . $m[2];
            if (!is_file($member) || is_link($member) || realpath($member) !== $member || lstat($member)['nlink'] !== 1
                || hash_file('sha256', $member) !== $m[1]) throw new RuntimeException('unsafe');
            $seen[$m[2]] = true;
        }
        if (count($seen) !== count($expected)) throw new RuntimeException('unsafe');
        $file = $backup . '/language-snapshot.json';
        if (is_link($file) || !is_file($file) || realpath($file) !== $file || filesize($file) > 4194304) throw new RuntimeException('unsafe');
        $snapshot = json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
        if (($snapshot['locale'] ?? '') !== 'fr_CA' || count($snapshot['payload']['files'] ?? []) !== 311
            || ($snapshot['payload']['wordpress'] ?? '') !== '7.1.3' || ($snapshot['payload']['woocommerce'] ?? '') !== '11.2.1'
            || !is_array($snapshot['old'] ?? null)) throw new RuntimeException('unsafe');
        echo 'LOCALE_FS_REVIEW ' . json_encode(lfa_report($backup . '/candidate-ready', $root . '/wp-content/languages', $snapshot), JSON_THROW_ON_ERROR) . "\n";
    } catch (Throwable $e) {
        fwrite(STDERR, "LOCALE_FS_REVIEW_UNAVAILABLE\n"); exit(1);
    }
    restore_error_handler();
}
