<?php
/** P1 four-file set journal/update/recovery; standalone, no WordPress/DB access. */
require_once __DIR__ . '/p0-files.php';
function p1_manifest(array $m): array {
    $files = ['footer.php', 'front-page.php', 'style.css', 'template-parts/navigation.php'];
    if (($m['changed'] ?? null) !== $files || ($m['from_version'] ?? '') !== '0.1.2' || ($m['to_version'] ?? '') !== '0.1.3') {
        throw new RuntimeException('Unexpected P1 scope/versions');
    }
    foreach (['old', 'new'] as $phase) {
        if (!is_array($m[$phase] ?? null) || count($m[$phase]) !== 23) { throw new RuntimeException('Expected full 23-file manifest'); }
        foreach ($m[$phase] as $p => $digest) {
            if (!is_string($p) || str_contains($p, '..') || str_starts_with($p, '/') || !is_string($digest) || !preg_match('/^[a-f0-9]{64}$/D', $digest)) { throw new RuntimeException('Invalid manifest member'); }
        }
        ksort($m[$phase]);
    }
    if (array_keys($m['old']) !== array_keys($m['new'])) { throw new RuntimeException('Manifest inventory differs'); }
    $changed = array_keys(array_filter($m['old'], static fn($v,$k) => $v !== $m['new'][$k], ARRAY_FILTER_USE_BOTH));
    if ($changed !== $files) { throw new RuntimeException('Manifest transition must change exact four-file set'); }
    return $m;
}
function p1_journal(string $file, array $state): void {
    if (is_link($file) || is_link(dirname($file)) || !is_dir(dirname($file))) { throw new RuntimeException('Unsafe journal path'); }
    $tmp = $file . '.' . bin2hex(random_bytes(8));
    $h = fopen($tmp, 'x');
    if (!$h) { throw new RuntimeException('Cannot create private journal'); }
    try {
        $bytes=json_encode($state,JSON_THROW_ON_ERROR)."\n";
        if (!chmod($tmp,0600) || fwrite($h,$bytes) !== strlen($bytes) || !fflush($h)) { throw new RuntimeException('Cannot persist journal'); }
        if (function_exists('fsync') && !fsync($h)) { throw new RuntimeException('Cannot sync journal'); }
    } finally { fclose($h); }
    if (!rename($tmp,$file)) { throw new RuntimeException('Cannot commit journal'); }
}
function p1_apply(string $source, string $target, array $manifest, string $journal, ?callable $after = null): void {
    $m = p1_manifest($manifest);
    if (file_exists($journal) || is_link($journal)) { throw new RuntimeException('Existing journal; no blind retry'); }
    p0_check($target,$m['old']);
    // Validate both candidates and filesystem preconditions before the first rename.
    foreach ($m['changed'] as $p) {
        $a=$source.'/'.$p; $b=$target.'/'.$p;
        if (!is_file($a) || is_link($a) || realpath($a)!==$a || hash_file('sha256',$a)!==$m['new'][$p]) { throw new RuntimeException('Unsafe or changed staged four-file set'); }
        $sa=lstat($a);$sb=lstat($b);
        if ($sa['dev']!==$sb['dev'] || $sa['uid']!==$sb['uid'] || $sa['nlink']!==1 || $sb['nlink']!==1 || !is_writable(dirname($b))) { throw new RuntimeException('Set filesystem/owner precondition failed'); }
    }
    $state=['phase'=>'prepared','intent'=>null,'replaced'=>[]];p1_journal($journal,$state);
    foreach ($m['changed'] as $p) {
        $state['intent']=$p;p1_journal($journal,$state);
        p0_replace($source.'/'.$p,$target.'/'.$p,$m['old'][$p],$m['new'][$p]);
        $state['replaced'][]=$p;$state['intent']=null;p1_journal($journal,$state);
        if ($after) { $after(count($state['replaced'])); }
    }
    p0_check($target,$m['new']);$state['phase']='files-verified';p1_journal($journal,$state);
}
function p1_rollback(string $oldSource, string $target, array $manifest, string $journal): void {
    $m=p1_manifest($manifest);$inventory=p0_inventory($target);
    if (array_keys($inventory)!==array_keys($m['old'])) { throw new RuntimeException('Rollback inventory drift'); }
    // Accept only a known old/new mixture of the two approved files, never arbitrary drift.
    foreach ($inventory as $p=>$hash) {
        if ($hash!==$m['old'][$p] && (!in_array($p,$m['changed'],true) || $hash!==$m['new'][$p])) { throw new RuntimeException('Rollback refuses unrelated/unknown edits'); }
    }
    foreach ($m['changed'] as $p) {
        $source=$oldSource.'/'.$p;
        if (!is_file($source) || is_link($source) || realpath($source)!==$source || hash_file('sha256',$source)!==$m['old'][$p]) { throw new RuntimeException('Rollback candidate is not exact baseline'); }
    }
    $state=['phase'=>'rollback-prepared','intent'=>null,'restored'=>[]];p1_journal($journal,$state);
    foreach (array_reverse($m['changed']) as $p) {
        if ($inventory[$p]===$m['new'][$p]) {
            $state['intent']=$p;p1_journal($journal,$state);
            p0_replace($oldSource.'/'.$p,$target.'/'.$p,$m['new'][$p],$m['old'][$p]);
            $state['restored'][]=$p;$state['intent']=null;p1_journal($journal,$state);
        }
    }
    p0_check($target,$m['old']);$state['phase']='rollback-files-verified';p1_journal($journal,$state);
}
if (realpath($_SERVER['SCRIPT_FILENAME'])===__FILE__) {
    try {
        $mode=$argv[1]??'';
        $m=p1_manifest(json_decode(file_get_contents($argv[2]),true,512,JSON_THROW_ON_ERROR));
        if ($mode==='apply') { p1_apply($argv[3],$argv[4],$m,$argv[5]); }
        elseif ($mode==='rollback') { p1_rollback($argv[3],$argv[4],$m,$argv[5]); }
        elseif ($mode==='digest') {
            if (!in_array($argv[3],['old','new'],true) || !in_array($argv[4],$m['changed'],true)) { throw new RuntimeException('Invalid digest scope'); }
            echo $m[$argv[3]][$argv[4]];exit;
        } else { throw new RuntimeException('Unknown P1 operation'); }
        echo "PASS: exact P1 four-file set operation verified.\n";
    } catch (Throwable $e) { fwrite(STDERR,$e->getMessage()."\n");exit(1); }
}
