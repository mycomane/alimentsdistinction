<?php
/** Fault-injection tests for both-file and partial-update recovery; no remote access. */
require __DIR__.'/../scripts/ops/p1-files.php';
$dir=sys_get_temp_dir().'/ad-p1-'.bin2hex(random_bytes(8));mkdir($dir,0700);
$count=0;
function p1_ok(bool $b):void { global $count;if(!$b)throw new RuntimeException('P1 assertion failed');$count++; }
function p1_refused(callable $f):void { try{$f();}catch(RuntimeException $e){p1_ok(true);return;}throw new RuntimeException('Unsafe transition accepted'); }
function setup_case(string $base):array {
    mkdir($base);foreach(['live','candidate','old'] as $dir){mkdir($base.'/'.$dir);mkdir($base.'/'.$dir.'/assets');mkdir($base.'/'.$dir.'/assets/css');}
    $m=['from_version'=>'0.1.1','to_version'=>'0.1.2','changed'=>['assets/css/commerce.css','style.css'],'old'=>[],'new'=>[]];
    $paths=array_merge($m['changed'],array_map(fn($i)=>'unchanged-'.$i,range(1,21)));
    foreach($paths as $p){$a='old-'.$p;$b=in_array($p,$m['changed'],true)?'new-'.$p:$a;
        file_put_contents($base.'/live/'.$p,$a);file_put_contents($base.'/old/'.$p,$a);file_put_contents($base.'/candidate/'.$p,$b);
        $m['old'][$p]=hash('sha256',$a);$m['new'][$p]=hash('sha256',$b);
    }ksort($m['old']);ksort($m['new']);return $m;
}
try {
    foreach([0,1,2] as $fault){
        $base=$dir.'/case-'.$fault;$m=setup_case($base);
        try{p1_apply($base.'/candidate',$base.'/live',$m,$base.'/journal.json',function($n)use($fault){if($n===$fault)throw new RuntimeException('Injected interruption');});p1_ok($fault===0);}
        catch(RuntimeException $e){p1_ok($fault!==0&&$e->getMessage()==='Injected interruption');}
        $live=p0_inventory($base.'/live');
        p1_ok($fault!==1 || ($live['assets/css/commerce.css']===$m['new']['assets/css/commerce.css']&&$live['style.css']===$m['old']['style.css']));
        p1_rollback($base.'/old',$base.'/live',$m,$base.'/rollback.json');p0_check($base.'/live',$m['old']);p1_ok(true);
        p1_ok(json_decode(file_get_contents($base.'/rollback.json'),true)['phase']==='rollback-files-verified');
    }
    $base=$dir.'/guards';$m=setup_case($base);
    $bad=$m;$bad['changed'][]='functions.php';p1_refused(fn()=>p1_manifest($bad));
    $bad=$m;$bad['new']['unchanged-1']=hash('sha256','drift');p1_refused(fn()=>p1_manifest($bad));
    $bad=$m;unset($bad['old']['style.css']);p1_refused(fn()=>p1_manifest($bad));
    file_put_contents($base.'/candidate/style.css','tampered');p1_refused(fn()=>p1_apply($base.'/candidate',$base.'/live',$m,$base.'/journal.json'));
    p0_check($base.'/live',$m['old']);p1_ok(true);
    file_put_contents($base.'/candidate/style.css','new-style.css');
    file_put_contents($base.'/live/unchanged-1','drift');p1_refused(fn()=>p1_apply($base.'/candidate',$base.'/live',$m,$base.'/journal.json'));
    p1_refused(fn()=>p1_rollback($base.'/old',$base.'/live',$m,$base.'/rollback.json'));
    file_put_contents($base.'/live/unchanged-1','old-unchanged-1');
    file_put_contents($base.'/live/style.css','unknown-pair-content');p1_refused(fn()=>p1_rollback($base.'/old',$base.'/live',$m,$base.'/rollback.json'));
    file_put_contents($base.'/live/style.css','old-style.css');
    file_put_contents($base.'/journal.json','existing');p1_refused(fn()=>p1_apply($base.'/candidate',$base.'/live',$m,$base.'/journal.json'));unlink($base.'/journal.json');
    unlink($base.'/candidate/style.css');symlink($base.'/old/style.css',$base.'/candidate/style.css');p1_refused(fn()=>p1_apply($base.'/candidate',$base.'/live',$m,$base.'/journal.json'));unlink($base.'/candidate/style.css');
    p0_check($base.'/live',$m['old']);p1_ok(true);
    echo "PASS: $count P1 exact-pair/interruption/rollback assertions.\n";
} finally {
    $it=new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir,FilesystemIterator::SKIP_DOTS),RecursiveIteratorIterator::CHILD_FIRST);
    foreach($it as $f){if($f->isDir()&&!$f->isLink())rmdir($f->getPathname());else unlink($f->getPathname());}rmdir($dir);
}
