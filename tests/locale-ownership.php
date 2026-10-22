<?php
/** Real disposable files, ownership, device and link guards. Run under sudo in CI. */
require __DIR__ . '/../scripts/locale/files.php';
require __DIR__ . '/../scripts/locale/audit-fs.php';
$oa_base = sys_get_temp_dir() . '/ad-owner-test-' . bin2hex(random_bytes(8));
mkdir($oa_base, 0755); chmod($oa_base, 0755);
$oa_roots = [$oa_base]; $oa_checks = 0;
$oa_partial = in_array('--local-no-privilege-changes', $argv, true);
function oa_ok($v): void { global $oa_checks; if (!$v) throw new RuntimeException('Ownership test assertion failed'); $oa_checks++; }
function oa_no(callable $call, ?string $message = null): void {
    try { $call(); } catch (RuntimeException $e) { oa_ok($message === null || $e->getMessage() === $message); return; }
    throw new RuntimeException('Unsafe ownership case accepted');
}
function oa_fixture(string $name): array {
    global $oa_base;
    $d = $oa_base . '/' . $name; mkdir($d, 0755); chmod($d,0755);
    foreach (['source', 'target', 'backup'] as $p) { mkdir($d . '/' . $p, 0755); mkdir($d . '/' . $p . '/plugins', 0755); chmod($d.'/'.$p,0755); chmod($d.'/'.$p.'/plugins',0755); }
    $m = ['locale'=>'fr_CA', 'wordpress'=>'7.1.3', 'woocommerce'=>'11.2.1', 'files'=>[]];
    foreach (['fr_CA.mo', 'plugins/woocommerce-fr_CA.mo'] as $p) {
        file_put_contents($d . '/source/' . $p, 'new-' . $p);
        file_put_contents($d . '/target/' . $p, 'old-' . $p);
        file_put_contents($d . '/backup/' . $p, 'old-' . $p);
        $m['files'][$p] = hash('sha256', 'new-' . $p);
    }
    return [$d, lf_snapshot($d . '/target', $m)];
}
function oa_apply(string $d, array $s): void { lf_apply($d . '/source', $d . '/target', $s, $d . '/journal'); }
function oa_report(string $d, array $s): array { return lfa_report($d . '/source', $d . '/target', $s); }
function oa_remove(string $p): void {
    if (is_link($p) || !is_dir($p)) { unlink($p); return; }
    chmod($p, 0755);
    foreach (new DirectoryIterator($p) as $f) if (!$f->isDot()) oa_remove($f->getPathname());
    rmdir($p);
}
try {
    [$d,$s] = oa_fixture('normal'); $before = lf_inventory($d . '/target'); $report = oa_report($d,$s);
    oa_ok(count(array_filter($report)) === count($report));
    foreach ($report as $v) oa_ok(is_bool($v));
    oa_ok(lf_inventory($d . '/target') === $before && !file_exists($d . '/journal'));
    oa_apply($d,$s); lf_rollback($d . '/backup',$d . '/target',$s,$d . '/rollback'); oa_ok(lf_inventory($d . '/target') === $before);

    if (!$oa_partial) {
    [$d,$s] = oa_fixture('candidate-owner'); $src=$d.'/source/fr_CA.mo'; $old=lstat($src)['uid'];
    oa_ok(chown($src, $old === 2 ? 3 : 2)); clearstatcache();
    oa_ok(!oa_report($d,$s)['candidate_owner_matches_directory']);
    oa_no(fn()=>oa_apply($d,$s), 'Locale precondition refused: candidate_owner_matches_directory');
    oa_ok(!file_exists($d.'/journal') && lf_inventory($d.'/target') === $s['old']); chown($src,$old);

    [$d,$s] = oa_fixture('directory-owner'); $old=lstat($d.'/target')['uid'];
    oa_ok(chown($d.'/target',$old === 2 ? 3 : 2)); $r=oa_report($d,$s);
    oa_ok(!$r['candidate_owner_matches_directory'] && $r['existing_targets_owner_matches_candidate']);
    oa_no(fn()=>oa_apply($d,$s), 'Locale precondition refused: candidate_owner_matches_directory'); chown($d.'/target',$old);
    }

    [$d,$s] = oa_fixture('hardlink'); oa_ok(link($d.'/source/fr_CA.mo',$d.'/alias'));
    oa_ok(!oa_report($d,$s)['candidate_single_link']);
    oa_no(fn()=>oa_apply($d,$s), 'Locale precondition refused: candidate_single_link');
    oa_ok(!file_exists($d.'/journal')); unlink($d.'/alias');

    [$d,$s] = oa_fixture('device');
    $other='/dev/shm/ad-owner-test-'.bin2hex(random_bytes(8)); mkdir($other,0755); $oa_roots[]=$other; mkdir($other.'/plugins',0755);
    oa_ok(lstat($other)['dev'] !== lstat($d.'/target')['dev']);
    foreach ($s['payload']['files'] as $p=>$h) copy($d.'/source/'.$p,$other.'/'.$p);
    oa_ok(!lfa_report($other,$d.'/target',$s)['candidate_device_matches_directory']);
    oa_no(fn()=>lf_apply($other,$d.'/target',$s,$d.'/journal'), 'Locale precondition refused: candidate_device_matches_directory');
    oa_ok(!file_exists($d.'/journal'));

    [$d,$s] = oa_fixture('symlink'); unlink($d.'/source/fr_CA.mo'); symlink($d.'/backup/fr_CA.mo',$d.'/source/fr_CA.mo');
    oa_ok(!oa_report($d,$s)['source_canonical']); oa_no(fn()=>oa_apply($d,$s),'Locale candidate mismatch');
    [$d,$s] = oa_fixture('ancestor-link'); rename($d.'/source/plugins',$d.'/elsewhere'); symlink($d.'/elsewhere',$d.'/source/plugins');
    oa_ok(!oa_report($d,$s)['source_canonical']); oa_no(fn()=>oa_apply($d,$s),'Locale candidate mismatch');
    [$d,$s] = oa_fixture('target-link'); rename($d.'/target',$d.'/real-target'); symlink($d.'/real-target',$d.'/target');
    oa_ok(!oa_report($d,$s)['target_canonical']); oa_no(fn()=>oa_apply($d,$s));
    [$d,$s] = oa_fixture('target-hardlink'); link($d.'/target/fr_CA.mo',$d.'/alias');
    oa_ok(!oa_report($d,$s)['existing_targets_single_link']); oa_no(fn()=>oa_apply($d,$s)); unlink($d.'/alias');

    [$d,$s] = oa_fixture('traversal'); $bad=$s; $bad['payload']['files']['../wp-config.php']=hash('sha256','outside');
    oa_no(fn()=>lf_apply($d.'/source',$d.'/target',$bad,$d.'/journal'));
    oa_no(fn()=>oa_report($d,$bad)); oa_ok(!file_exists($d.'/journal'));

    [$d,$s] = oa_fixture('permissions'); chmod($d.'/target',0555);
    oa_ok(!oa_report($d,$s)['target_write_bits_present']);
    // Check effective permissions under an unprivileged process, not root's bypass.
    if (!$oa_partial) {
    copy(__DIR__.'/../scripts/locale/audit-fs.php',$d.'/audit-fs.php'); chmod($d.'/audit-fs.php',0644);
    foreach ($s['payload']['files'] as $p=>$h) chmod($d.'/source/'.$p,0644);
    $code='require '.var_export($d.'/audit-fs.php',true).';$s=json_decode($argv[3],true);echo json_encode(lfa_report($argv[1],$argv[2],$s));';
    $cmd=['runuser','-u','nobody','--',PHP_BINARY,'-r',$code,$d.'/source',$d.'/target',json_encode($s)];
    $proc=proc_open($cmd,[0=>['pipe','r'],1=>['pipe','w'],2=>['pipe','w']],$pipes);
    fclose($pipes[0]); $out=stream_get_contents($pipes[1]); $err=stream_get_contents($pipes[2]); fclose($pipes[1]); fclose($pipes[2]);
    oa_ok(proc_close($proc)===0 && $err===''); $r=json_decode($out,true,512,JSON_THROW_ON_ERROR);
    oa_ok($r['target_effectively_writable']===false);
    }
    chmod($d.'/target',0755);

    if (!$oa_partial) {
    [$d,$s] = oa_fixture('owner-drift'); $target=$d.'/target/plugins/woocommerce-fr_CA.mo'; $old=lstat($target)['uid'];
    oa_no(function()use($d,$s,$target,$old){lf_apply($d.'/source',$d.'/target',$s,$d.'/journal',function($n)use($target,$old){if($n===1)chown($target,$old===2?3:2);});}, 'Atomic filesystem/owner precondition failed');
    oa_ok(hash_file('sha256',$d.'/target/fr_CA.mo')===$s['new']['fr_CA.mo']);
    chown($target,$old); lf_rollback($d.'/backup',$d.'/target',$s,$d.'/rollback'); oa_ok(lf_inventory($d.'/target')===$s['old']);
    }

    echo "PASS: $oa_checks isolated filesystem assertions; ".($oa_partial ? 'PARTIAL local run: owner changes and unprivileged effective-permission checks NOT executed' : 'FULL owner/device/link/permissions/partial-recovery run')."; sanitized booleans only.\n";
} finally {
    foreach (array_reverse($oa_roots) as $p) {
        if (!str_starts_with(basename($p),'ad-owner-test-')) throw new RuntimeException('Unsafe fixture cleanup refused');
        oa_remove($p);
    }
}
