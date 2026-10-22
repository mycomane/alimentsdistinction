<?php
require __DIR__.'/../scripts/locale/files.php';
$base=sys_get_temp_dir().'/ad-locale-'.bin2hex(random_bytes(8));mkdir($base,0700);$checks=0;
function loc_ok($value){global $checks;if(!$value)throw new RuntimeException('Locale file assertion failed');$checks++;}
function loc_no($call){try{$call();}catch(RuntimeException $e){loc_ok(true);return;}throw new RuntimeException('Unsafe language operation accepted');}
try{
 foreach([false,true] as $existing)foreach([0,1,2] as $fault){
  $dir=$base.'/'.(int)$existing.'-'.$fault;mkdir($dir);$root=$dir.'/languages';$candidate=$dir.'/candidate';$backup=$dir.'/backup';mkdir($candidate);mkdir($candidate.'/plugins');mkdir($backup);mkdir($backup.'/plugins');
  $m=['locale'=>'fr_CA','wordpress'=>'7.1.3','woocommerce'=>'11.2.1','files'=>['fr_CA.mo'=>hash('sha256','new-core'),'plugins/woocommerce-fr_CA.mo'=>hash('sha256','new-woo')]];
  file_put_contents($candidate.'/fr_CA.mo','new-core');file_put_contents($candidate.'/plugins/woocommerce-fr_CA.mo','new-woo');
  if($existing){mkdir($root);mkdir($root.'/plugins');file_put_contents($root.'/fr_CA.mo','custom-old-core');file_put_contents($backup.'/fr_CA.mo','custom-old-core');file_put_contents($root.'/plugins/other-plugin-fr_FR.mo','preserve');}
  $s=lf_snapshot($root,$m);
  try{lf_apply($candidate,$root,$s,$dir.'/journal',function($n)use($fault){if($n===$fault)throw new RuntimeException('Injected interruption');});loc_ok($fault===0);}
  catch(RuntimeException $e){loc_ok($fault>0&&$e->getMessage()==='Injected interruption');}
  lf_rollback($backup,$root,$s,$dir.'/rollback');lf_check($root,$s,'old');loc_ok(true);loc_ok(is_dir($root)===$existing);
  if($existing)loc_ok(file_get_contents($root.'/plugins/other-plugin-fr_FR.mo')==='preserve');
 }
 $dir=$base.'/guards';mkdir($dir);mkdir($dir.'/languages');mkdir($dir.'/candidate');mkdir($dir.'/candidate/plugins');mkdir($dir.'/backup');
 file_put_contents($dir.'/candidate/fr_CA.mo','new-core');file_put_contents($dir.'/candidate/plugins/woocommerce-fr_CA.mo','new-woo');$s=lf_snapshot($dir.'/languages',$m);
 $bad=$m;$bad['files']['../wp-config.php']=hash('sha256','bad');loc_no(fn()=>lf_payload($bad));
 $bad=$m;$bad['files']['plugins/other-plugin-fr_CA.mo']=hash('sha256','bad');loc_no(fn()=>lf_payload($bad));
 file_put_contents($dir.'/candidate/fr_CA.mo','tamper');loc_no(fn()=>lf_apply($dir.'/candidate',$dir.'/languages',$s,$dir.'/journal'));loc_ok(lf_inventory($dir.'/languages')===[]);
 file_put_contents($dir.'/candidate/fr_CA.mo','new-core');file_put_contents($dir.'/languages/foreign.mo','unrelated');loc_no(fn()=>lf_apply($dir.'/candidate',$dir.'/languages',$s,$dir.'/journal'));loc_no(fn()=>lf_rollback($dir.'/backup',$dir.'/languages',$s,$dir.'/rollback'));unlink($dir.'/languages/foreign.mo');
 lf_apply($dir.'/candidate',$dir.'/languages',$s,$dir.'/journal');file_put_contents($dir.'/languages/fr_CA.mo','unknown');loc_no(fn()=>lf_rollback($dir.'/backup',$dir.'/languages',$s,$dir.'/rollback'));
 echo "PASS: $checks locale path/inventory/interruption/recovery assertions.\n";
}finally{$it=new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base,FilesystemIterator::SKIP_DOTS),RecursiveIteratorIterator::CHILD_FIRST);foreach($it as $f){if($f->isDir()&&!$f->isLink())rmdir($f->getPathname());else unlink($f->getPathname());}rmdir($base);}
