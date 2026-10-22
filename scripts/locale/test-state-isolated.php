<?php
/** Actual WordPress/SQLite tests of WPLANG state changes; never a hosted entrypoint. */
$_SERVER['HTTP_HOST']='127.0.0.1:8787';$_SERVER['REQUEST_METHOD']='GET';
$dir=getenv('RUNNER_TEMP').'/ad-isolated-qa';require $dir.'/wordpress/wp-load.php';
require __DIR__.'/wp-functions.php';ad_locale_identity(true);
$ids=json_decode(file_get_contents($dir.'/fixtures.json'),true);$pageIDs=array_intersect_key($ids,array_flip(['shop','cart','checkout','myaccount']));
$mode=$argv[1]??'test';$file=$dir.'/locale-state-baseline.json';
if($mode==='verify'){
 $baseline=json_decode(file_get_contents($file),true);ad_locale_verify($baseline,'new',$pageIDs);
 echo 'PASS: fresh-process effective fr_CA, two native labels and protected state verified.'."\n";return;
}
$checks=0;function ls_ok($ok){global $checks;if(!$ok)throw new RuntimeException('Locale state assertion failed');$checks++;}
function ls_no($call){try{$call();}catch(RuntimeException $e){ls_ok(true);return;}throw new RuntimeException('Unsafe locale state change accepted');}
$original=ad_locale_raw();
foreach([null,['option_value'=>'en_US','autoload'=>'yes'],['option_value'=>'','autoload'=>'auto']] as $row){
 global $wpdb;delete_option('WPLANG');if($row!==null){$wpdb->insert($wpdb->options,['option_name'=>'WPLANG','option_value'=>$row['option_value'],'autoload'=>$row['autoload']]);wp_cache_delete('alloptions','options');wp_cache_delete('WPLANG','options');}
 $before=ad_locale_capture($pageIDs);ls_ok($before['WPLANG']===$row);
 $country=get_option('woocommerce_default_country');update_option('woocommerce_default_country','QA-ISOLATED-SENTINEL');
 ls_no(fn()=>ad_locale_activate($before,$pageIDs));ls_ok(ad_locale_raw()===$row);update_option('woocommerce_default_country',$country);
 ad_locale_activate($before,$pageIDs);ls_ok(ad_locale_raw()['option_value']==='fr_CA');ls_ok(ad_locale_protected($pageIDs)===$before['protected']);
 ad_locale_restore($before);ls_ok(ad_locale_raw()===$row);ls_ok(ad_locale_protected($pageIDs)===$before['protected']);
 update_option('WPLANG','de_DE');ls_no(fn()=>ad_locale_restore($before));update_option('WPLANG','fr_CA');ad_locale_restore($before);
}
// Finish with an English baseline, activate fr_CA, then verify in a fresh PHP process.
delete_option('WPLANG');if($original!==null){update_option('WPLANG',$original['option_value']);$wpdb->update($wpdb->options,['autoload'=>$original['autoload']],['option_name'=>'WPLANG']);wp_cache_delete('alloptions','options');wp_cache_delete('WPLANG','options');}
$baseline=ad_locale_capture($pageIDs);file_put_contents($file,json_encode($baseline,JSON_THROW_ON_ERROR));ad_locale_activate($baseline,$pageIDs);
echo "PASS: $checks real isolated WPLANG/protected-setting/exact-row rollback assertions.\n";
