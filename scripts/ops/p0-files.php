<?php
/** Standalone file guards; no WordPress/bootstrap/database access. */
if (PHP_SAPI !== 'cli') { exit(1); }
function p0_inventory(string $dir): array {
    if (is_link($dir) || !is_dir($dir) || realpath($dir) !== $dir) { throw new RuntimeException('Noncanonical theme directory'); }
    $files = [];
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST);
    foreach ($it as $file) {
        if ($file->isLink()) { throw new RuntimeException('Theme symlink refused'); }
        if ($file->isDir()) { continue; }
        if (!$file->isFile()) { throw new RuntimeException('Nonregular file refused'); }
        $stat = lstat($file->getPathname());
        if ($stat['nlink'] !== 1) { throw new RuntimeException('Hardlink refused'); }
        $files[substr($file->getPathname(), strlen($dir) + 1)] = hash_file('sha256', $file->getPathname());
    }
    ksort($files); return $files;
}
function p0_check(string $dir, array $expected): void {
    ksort($expected);
    if (p0_inventory($dir) !== $expected) { throw new RuntimeException('Theme inventory/checksum mismatch; no overwrite permitted'); }
}
function p0_replace(string $source, string $target, string $from, string $to): void {
    clearstatcache();
    if (!is_file($source) || !is_file($target) || is_link($source) || is_link($target)) { throw new RuntimeException('Unsafe atomic replacement path'); }
    $a = lstat($source); $b = lstat($target);
    if ($a['dev'] !== $b['dev'] || $a['nlink'] !== 1 || $b['nlink'] !== 1 || $a['uid'] !== $b['uid']) { throw new RuntimeException('Atomic filesystem/owner precondition failed'); }
    if (hash_file('sha256', $source) !== $to || hash_file('sha256', $target) !== $from) { throw new RuntimeException('Concurrent or unexpected content; replacement refused'); }
    if (!chmod($source, $b['mode'] & 0777) || !rename($source, $target)) { throw new RuntimeException('Atomic replacement failed'); }
    clearstatcache();
    if (hash_file('sha256', $target) !== $to) { throw new RuntimeException('Post-rename checksum failed'); }
}
if (realpath($_SERVER['SCRIPT_FILENAME']) === __FILE__) {
    try {
        $mode = $argv[1] ?? '';
        if ($mode === 'check') {
            $manifest = json_decode(file_get_contents($argv[3]), true, 512, JSON_THROW_ON_ERROR);
            p0_check($argv[2], $manifest[$argv[4]]);
        } elseif ($mode === 'replace') {
            p0_replace($argv[2], $argv[3], $argv[4], $argv[5]);
        } else { throw new RuntimeException('Unknown file operation'); }
        echo "PASS: scoped file operation verified.\n";
    } catch (Throwable $e) { fwrite(STDERR, $e->getMessage() . "\n"); exit(1); }
}
