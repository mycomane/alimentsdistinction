<?php
/** Translation-only inventory/install/rollback. No WordPress or DB access. */
require_once __DIR__.'/../ops/p0-files.php';
function lf_payload(array $m):array {
 if(($m['locale']??'')!=='fr_CA'||($m['wordpress']??'')!=='7.1.3'||($m['woocommerce']??'')!=='11.2.1'||!is_array($m['files']??null)||!count($m['files'])||count($m['files'])>1000)throw new RuntimeException('Invalid pinned locale manifest');
 foreach($m['files'] as $p=>$h){
  if(!preg_match('~\A(?:(?:(?:admin-|admin-network-|continents-cities-)?fr_CA)|(?:plugins/woocommerce-fr_CA))(?:-[a-f0-9]{32})?\.(?:mo|po|l10n\.php|json)\z~D',$p)||!is_string($h)||!preg_match('/^[a-f0-9]{64}$/D',$h))throw new RuntimeException('Non-fr_CA payload member');
 }ksort($m['files']);return $m;
}
function lf_inventory(string $root):array {
 if(!file_exists($root)&&!is_link($root)){
  if(realpath(dirname($root))!==dirname($root))throw new RuntimeException('Noncanonical language parent');return [];
 }return p0_inventory($root);
}
function lf_snapshot(string $root,array $payload):array {
 $m=lf_payload($payload);$old=lf_inventory($root);$new=array_replace($old,$m['files']);ksort($new);
 return ['locale'=>'fr_CA','root_existed'=>is_dir($root),'plugins_existed'=>is_dir($root.'/plugins'),'payload'=>$m,'old'=>$old,'new'=>$new];
}
function lf_state(array $s):array {
 if(($s['locale']??'')!=='fr_CA'||!is_bool($s['root_existed']??null)||!is_bool($s['plugins_existed']??null)||!is_array($s['old']??null)||!is_array($s['new']??null))throw new RuntimeException('Invalid language snapshot');
 $m=lf_payload($s['payload']);$expected=array_replace($s['old'],$m['files']);ksort($expected);ksort($s['old']);ksort($s['new']);
 if($expected!==$s['new'])throw new RuntimeException('Language snapshot scope mismatch');return $s;
}
function lf_write(string $file,array $value):void {
 if(is_link($file)||realpath(dirname($file))!==dirname($file))throw new RuntimeException('Unsafe private journal path');
 $tmp=$file.'.'.bin2hex(random_bytes(8));$h=fopen($tmp,'x');if(!$h)throw new RuntimeException('Private journal unavailable');
 try{$bytes=json_encode($value,JSON_THROW_ON_ERROR)."\n";if(!chmod($tmp,0600)||fwrite($h,$bytes)!==strlen($bytes)||!fflush($h)||(function_exists('fsync')&&!fsync($h)))throw new RuntimeException('Journal write failed');}finally{fclose($h);}
 if(!rename($tmp,$file))throw new RuntimeException('Journal commit failed');
}
function lf_check(string $root,array $snapshot,string $phase):void {
 $s=lf_state($snapshot);if(!in_array($phase,['old','new'],true)||lf_inventory($root)!==$s[$phase])throw new RuntimeException('Language inventory drift');
}
function lf_apply(string $source,string $root,array $snapshot,string $journal,?callable $after=null):void {
 $s=lf_state($snapshot);lf_check($root,$s,'old');
 if(file_exists($journal)||is_link($journal))throw new RuntimeException('Existing locale journal; no blind retry');
 $owner=lstat(is_dir($root)?$root:dirname($root));
 foreach($s['payload']['files'] as $p=>$hash){
  $src=$source.'/'.$p;
  if(!is_file($src)||is_link($src)||realpath($src)!==$src||hash_file('sha256',$src)!==$hash)throw new RuntimeException('Locale candidate mismatch');
  $stat=lstat($src);if($stat['nlink']!==1||$stat['uid']!==$owner['uid']||$stat['dev']!==$owner['dev'])throw new RuntimeException('Locale filesystem/owner precondition failed');
 }
 $j=['phase'=>'prepared','intent'=>null,'changed'=>[]];lf_write($journal,$j);
 foreach([$root,$root.'/plugins'] as $dir){if(!is_dir($dir)){if(!mkdir($dir,0755)||!chmod($dir,0755))throw new RuntimeException('Language directory unavailable');}if(realpath($dir)!==$dir||!is_writable($dir))throw new RuntimeException('Unsafe language directory');}
 foreach($s['payload']['files'] as $p=>$hash){
  if(($s['old'][$p]??null)===$hash)continue;
  $src=$source.'/'.$p;$target=$root.'/'.$p;$j['intent']=$p;lf_write($journal,$j);
  if(isset($s['old'][$p]))p0_replace($src,$target,$s['old'][$p],$hash);
  else{if(file_exists($target)||is_link($target)||!chmod($src,0644)||!rename($src,$target))throw new RuntimeException('New translation file refused');if(hash_file('sha256',$target)!==$hash)throw new RuntimeException('New translation hash mismatch');}
  $j['changed'][]=$p;$j['intent']=null;lf_write($journal,$j);if($after)$after(count($j['changed']));
 }
 lf_check($root,$s,'new');$j['phase']='files-verified';lf_write($journal,$j);
}
function lf_rollback(string $source,string $root,array $snapshot,string $journal):void {
 $s=lf_state($snapshot);$now=lf_inventory($root);
 // Entire language inventory must be a known old/new combination; no unrelated overwrite.
 foreach($now as $p=>$hash){if(!isset($s['new'][$p])||($hash!==($s['old'][$p]??null)&&$hash!==$s['new'][$p]))throw new RuntimeException('Unknown translation drift; recovery refused');}
 foreach($s['old'] as $p=>$hash){if(!isset($now[$p]))throw new RuntimeException('Original translation disappeared');}
 foreach($s['payload']['files'] as $p=>$hash){if(isset($s['old'][$p])){ $f=$source.'/'.$p;if(!is_file($f)||is_link($f)||realpath($f)!==$f||hash_file('sha256',$f)!==$s['old'][$p])throw new RuntimeException('Original translation recovery copy unavailable');}}
 $j=['phase'=>'rollback-prepared','intent'=>null,'restored'=>[]];lf_write($journal,$j);
 foreach(array_reverse(array_keys($s['payload']['files'])) as $p){
  if(($now[$p]??null)===($s['old'][$p]??null))continue;
  $j['intent']=$p;lf_write($journal,$j);$target=$root.'/'.$p;
  if(isset($s['old'][$p]))p0_replace($source.'/'.$p,$target,$s['new'][$p],$s['old'][$p]);
  else{clearstatcache();if(is_link($target)||hash_file('sha256',$target)!==$s['new'][$p]||!unlink($target))throw new RuntimeException('Unknown new translation; deletion refused');}
  $j['restored'][]=$p;$j['intent']=null;lf_write($journal,$j);
 }
 lf_check($root,$s,'old');
 if(!$s['plugins_existed']&&is_dir($root.'/plugins')&&!rmdir($root.'/plugins'))throw new RuntimeException('Cannot remove new empty plugin language directory');
 if(!$s['root_existed']&&is_dir($root)&&!rmdir($root))throw new RuntimeException('Cannot remove new empty language directory');
 $j['phase']='rollback-files-verified';lf_write($journal,$j);
}
if(realpath($_SERVER['SCRIPT_FILENAME'])===__FILE__){
 try{
  $mode=$argv[1]??'';
  if($mode==='capture'){
   if(file_exists($argv[4])||is_link($argv[4]))throw new RuntimeException('Existing language baseline');
   lf_write($argv[4],lf_snapshot($argv[2],json_decode(file_get_contents($argv[3]),true,512,JSON_THROW_ON_ERROR)));
  }elseif($mode==='check')lf_check($argv[2],json_decode(file_get_contents($argv[3]),true,512,JSON_THROW_ON_ERROR),$argv[4]);
  elseif($mode==='apply'||$mode==='rollback'){
   $s=json_decode(file_get_contents($argv[4]),true,512,JSON_THROW_ON_ERROR);
   if($mode==='apply')lf_apply($argv[2],$argv[3],$s,$argv[5]);else lf_rollback($argv[2],$argv[3],$s,$argv[5]);
  }else throw new RuntimeException('Unknown language file operation');
  echo "PASS: scoped fr_CA file operation verified.\n";
 }catch(Throwable $e){fwrite(STDERR,$e->getMessage()."\n");exit(1);}
}
