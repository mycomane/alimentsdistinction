<?php
/** Protected hosted entrypoint. No isolation switch accepted through arguments. */
require __DIR__.'/wp-functions.php';
ad_locale_identity();
$mode=$args[0]??'';
if($mode==='audit'){echo 'LOCALE_AUDIT '.json_encode(ad_locale_audit(),JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR)."\n";return;}
$file=$args[1]??'';$private=realpath(getenv('HOME')).'/.ad-private-backups/';
if(is_link($file)||!str_starts_with(realpath(dirname($file)).'/',$private))throw new RuntimeException('Private locale baseline required');
if($mode==='capture'){
 if(file_exists($file))throw new RuntimeException('Existing locale baseline');
 $bytes=json_encode(ad_locale_capture(),JSON_THROW_ON_ERROR);if(file_put_contents($file,$bytes,LOCK_EX)!==strlen($bytes)||!chmod($file,0600))throw new RuntimeException('Cannot save private locale baseline');
}else{
 $baseline=json_decode(file_get_contents($file),true,512,JSON_THROW_ON_ERROR);
 if($mode==='activate')ad_locale_activate($baseline);
 elseif($mode==='rollback')ad_locale_restore($baseline);
 elseif($mode==='verify')ad_locale_verify($baseline,$args[2]??'');
 else throw new RuntimeException('Unknown locale state mode');
}
echo 'PASS: locale '.$mode."; protected state verified.\n";
