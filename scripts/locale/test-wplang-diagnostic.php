<?php
/** Real WordPress/SQLite fault cases; fresh local fixture only, never hosted. */
try {
$_SERVER['HTTP_HOST']='127.0.0.1:8787';$_SERVER['REQUEST_METHOD']='GET';
$dir=getenv('RUNNER_TEMP').'/ad-isolated-qa';require $dir.'/wordpress/wp-load.php';
require __DIR__.'/wp-functions.php';ad_locale_identity(true);
$ids=json_decode(file_get_contents($dir.'/fixtures.json'),true,512,JSON_THROW_ON_ERROR);
$pageIDs=array_intersect_key($ids,array_flip(['shop','cart','checkout','myaccount']));
$checks=[];
function wd_ok(bool $v,string $label):void {global $checks;if(!$v)throw new RuntimeException('DIAGNOSTIC_ASSERTION_FAILED');$checks[]=['case'=>$label,'passed'=>true];}
function wd_no(callable $call,string $code,string $label):void {
 try{$call();}catch(RuntimeException $e){wd_ok($e->getMessage()===$code,$label);return;}
 throw new RuntimeException('DIAGNOSTIC_EXPECTED_REFUSAL_MISSING');
}
global $wpdb;
delete_option('WPLANG');$baseline=ad_locale_capture($pageIDs);
$coreKey=md5(WP_LANG_DIR.'/');$pluginKey=md5(WP_LANG_DIR.'/plugins/');
wp_cache_delete($coreKey,'translation_files');
wd_ok(!is_file(WP_LANG_DIR.'/fr_CA.mo')&&!is_file(WP_LANG_DIR.'/fr_CA.l10n.php'),'fresh fixture has no core fr_CA files');
wd_ok(!in_array('fr_CA',get_available_languages(),true),'no files means fr_CA is unavailable');
wd_ok(sanitize_option('WPLANG','fr_CA')!=='fr_CA','native sanitization rejects unavailable language');
wd_ok(update_option('WPLANG','fr_CA')===false,'native API returns false when sanitization retains old value');
wd_ok(ad_locale_raw()===$baseline['WPLANG'],'rejected native update leaves baseline intact');
wd_no(fn()=>ad_locale_activate($baseline,$pageIDs),'FR_CA_NOT_AVAILABLE','new guard rejects missing files before writing');

// Install only pinned bytes in this fresh fixture, outside WordPress upgrader,
// as the hosted shell does. Preserve the already-primed discovery cache.
$payload=getenv('RUNNER_TEMP').'/ad-fr-ca-payload';
$manifest=json_decode(file_get_contents($payload.'/manifest.json'),true,512,JSON_THROW_ON_ERROR);
require __DIR__.'/files.php';$manifest=lf_payload($manifest);
wd_ok(count($manifest['files'])===311,'all pinned package members are used');
foreach($manifest['files'] as $p=>$hash){
 $src=$payload.'/languages/'.$p;$target=WP_LANG_DIR.'/'.$p;
 if(hash_file('sha256',$src)!==$hash)throw new RuntimeException('DIAGNOSTIC_PAYLOAD_MISMATCH');
 if(!is_dir(dirname($target)))mkdir(dirname($target),0755,true);
 if(!copy($src,$target))throw new RuntimeException('DIAGNOSTIC_FIXTURE_INSTALL_FAILED');
}
wd_ok(is_readable(WP_LANG_DIR.'/fr_CA.mo'),'pinned core catalog exists after fixture install');
wd_ok(!in_array('fr_CA',get_available_languages(),true),'stale empty discovery cache hides newly installed language');
wd_ok(sanitize_option('WPLANG','fr_CA')!=='fr_CA','stale cache reproduces sanitizer refusal');
wd_ok(update_option('WPLANG','fr_CA')===false&&ad_locale_raw()===$baseline['WPLANG'],'stale cache reproduces original false API branch');

wp_cache_set('wplang-diag-unrelated','keep','translation_files');
$themeKey=md5(WP_LANG_DIR.'/themes/');wp_cache_set($themeKey,['fixture-theme'],'translation_files');
wp_cache_set($pluginKey,[],'translation_files');
ad_locale_activate($baseline,$pageIDs);
wd_ok(ad_locale_raw()['option_value']==='fr_CA','targeted native invalidation permits official option API');
wd_ok(in_array('fr_CA',get_available_languages(),true),'native discovery recognizes installed core catalog after refresh');
wd_ok(sanitize_option('WPLANG','fr_CA')==='fr_CA','native sanitization remains active and accepts recognized language');
wd_ok(wp_cache_get('wplang-diag-unrelated','translation_files')==='keep'&&wp_cache_get($themeKey,'translation_files')===['fixture-theme'],'refresh preserves unrelated and theme cache entries');
wd_ok(wp_cache_get($pluginKey,'translation_files')===false,'plugin discovery entry is narrowly invalidated');
ad_locale_restore($baseline);wd_ok(ad_locale_raw()===$baseline['WPLANG'],'successful isolated activation rolls back exactly');

$hidden=static fn($languages)=>array_values(array_diff($languages,['fr_CA']));
add_filter('get_available_languages',$hidden);
wd_no(fn()=>ad_locale_activate($baseline,$pageIDs),'FR_CA_NOT_AVAILABLE','availability filter is respected, never bypassed');
remove_filter('get_available_languages',$hidden);

$blocked=static fn($value,$old)=>$old;add_filter('pre_update_option_WPLANG',$blocked,10,2);
$rawReads=0;$trace=static function($query)use(&$rawReads){if(str_starts_with($query,'SELECT option_value, autoload FROM '))$rawReads++;return $query;};add_filter('query',$trace);
wd_no(fn()=>ad_locale_activate($baseline,$pageIDs),'WPLANG_API_FALSE','pre-update filter returns distinct API false code');
remove_filter('query',$trace);wd_ok($rawReads===2,'raw row is independently re-read even when the API returns false');
remove_filter('pre_update_option_WPLANG',$blocked,10);
wd_ok(ad_locale_raw()===$baseline['WPLANG'],'blocked update preserves exact baseline');
$sanitize=static fn($value)=>get_option('WPLANG');add_filter('sanitize_option_WPLANG',$sanitize);
wd_no(fn()=>ad_locale_activate($baseline,$pageIDs),'WPLANG_API_FALSE','sanitization filter refusal is preserved');
remove_filter('sanitize_option_WPLANG',$sanitize);
$throws=static function(){throw new RuntimeException('fixture-sensitive-internal-detail');};add_filter('pre_update_option_WPLANG',$throws);
wd_no(fn()=>ad_locale_activate($baseline,$pageIDs),'WPLANG_API_EXCEPTION','unexpected API exception becomes a fixed non-sensitive code');
remove_filter('pre_update_option_WPLANG',$throws);
foreach(['FR_CA_NOT_AVAILABLE','WPLANG_API_FALSE','WPLANG_RAW_MISMATCH','WPLANG_QUERY_FAILED','WPLANG_WRITE_FAILED','WPLANG_API_EXCEPTION'] as $code)wd_ok(ad_locale_public_error(new RuntimeException($code))===$code,'public diagnostic permits fixed code '.$code);
wd_ok(ad_locale_public_error(new RuntimeException('fixture-sensitive-internal-detail'))==='LOCALE_STATE_REFUSED','unallowlisted error details are not published');

// Force the API down its UPDATE path using an existing empty row, fixture only.
update_option('WPLANG','');$baseline=ad_locale_capture($pageIDs);
$writeFault=static function($query)use($wpdb){
 if(preg_match('/^UPDATE\s/i',$query)&&str_contains($query,"'WPLANG'"))return "UPDATE {$wpdb->options} SET wplang_fixture_missing_column=1 WHERE option_name='WPLANG'";
 return $query;
};
add_filter('query',$writeFault);
wd_no(fn()=>ad_locale_activate($baseline,$pageIDs),'WPLANG_WRITE_FAILED','real fixture DB write error has a fixed code');
remove_filter('query',$writeFault);
wd_ok(ad_locale_raw()===$baseline['WPLANG'],'failed DB write preserves original row');
$readFault=static function($query)use($wpdb){
 if(str_starts_with($query,'SELECT option_value, autoload FROM ')&&str_contains($query,"'WPLANG'"))return "SELECT wplang_fixture_missing_column FROM {$wpdb->options}";
 return $query;
};
add_filter('query',$readFault);
wd_no(fn()=>ad_locale_raw(),'WPLANG_QUERY_FAILED','real fixture raw-read error has a fixed code');
remove_filter('query',$readFault);

$missing=static function($option)use($wpdb){if($option==='WPLANG')$wpdb->delete($wpdb->options,['option_name'=>'WPLANG']);};
add_action('updated_option',$missing,10,1);
wd_no(fn()=>ad_locale_activate($baseline,$pageIDs),'WPLANG_RAW_MISMATCH','API success with absent raw row is refused');
remove_action('updated_option',$missing,10);
wd_no(fn()=>ad_locale_restore($baseline),'Unknown WPLANG change; no blind rollback','missing unknown row is not blindly overwritten by rollback');
// Fixture repair through the official API, then exact tested rollback.
wp_cache_delete('alloptions','options');wp_cache_delete('WPLANG','options');wp_cache_delete('notoptions','options');
update_option('WPLANG','fr_CA');ad_locale_restore($baseline);
wd_ok(ad_locale_raw()===$baseline['WPLANG'],'exact rollback after fixture-only missing-row repair');

$divergent=static function($option)use($wpdb){if($option==='WPLANG')$wpdb->update($wpdb->options,['option_value'=>'fixture-unexpected'],['option_name'=>'WPLANG']);};
add_action('updated_option',$divergent,10,1);
wd_no(fn()=>ad_locale_activate($baseline,$pageIDs),'WPLANG_RAW_MISMATCH','API success with divergent raw row is refused');
remove_action('updated_option',$divergent,10);
wd_no(fn()=>ad_locale_restore($baseline),'Unknown WPLANG change; no blind rollback','divergent raw row is not overwritten by rollback');
wp_cache_delete('alloptions','options');wp_cache_delete('WPLANG','options');wp_cache_delete('notoptions','options');
update_option('WPLANG','fr_CA');ad_locale_restore($baseline);
wd_ok(ad_locale_raw()===$baseline['WPLANG'],'exact rollback after fixture-only divergent-row repair');
wd_ok(ad_locale_protected($pageIDs)===$baseline['protected'],'all protected commercial and page state unchanged');

// A file rollback also needs narrow discovery invalidation: otherwise a cached
// catalog list can advertise a file that was just removed. Fixture-owned files only.
$stash=$dir.'/wplang-core-stash';mkdir($stash,0700);
foreach(['fr_CA.mo','fr_CA.l10n.php'] as $p)if(is_file(WP_LANG_DIR.'/'.$p))rename(WP_LANG_DIR.'/'.$p,$stash.'/'.$p);
wp_cache_set($coreKey,[WP_LANG_DIR.'/fr_CA.mo'],'translation_files');
wd_ok(in_array('fr_CA',get_available_languages(),true),'stale discovery can also survive a file rollback');
ad_locale_invalidate_language_discovery();
wd_ok(!in_array('fr_CA',get_available_languages(),true),'targeted invalidation removes phantom language after file rollback');
foreach(['fr_CA.mo','fr_CA.l10n.php'] as $p)if(is_file($stash.'/'.$p))rename($stash.'/'.$p,WP_LANG_DIR.'/'.$p);
rmdir($stash);ad_locale_invalidate_language_discovery();
wd_ok(in_array('fr_CA',get_available_languages(),true),'fixture catalogs restored for existing fresh-process tests');

$evidence=['scope'=>'isolated WordPress SQLite; no hosting connection','checks'=>$checks,'count'=>count($checks),'failures'=>0,'hosting_root_cause_confirmed'=>false,'correction_deployed'=>false];
if(!is_dir($dir.'/evidence'))mkdir($dir.'/evidence',0755);
file_put_contents($dir.'/evidence/wplang-diagnostic.json',json_encode($evidence,JSON_PRETTY_PRINT|JSON_THROW_ON_ERROR)."\n");
echo 'PASS: '.count($checks)." real isolated WPLANG diagnostic assertions; stale discovery reproduced, no hosted cause inferred.\n";
}catch(Throwable $e){
 $safe=['FR_CA_NOT_AVAILABLE','WPLANG_API_FALSE','WPLANG_RAW_MISMATCH','WPLANG_QUERY_FAILED','WPLANG_WRITE_FAILED','DIAGNOSTIC_ASSERTION_FAILED','DIAGNOSTIC_EXPECTED_REFUSAL_MISSING'];
 fwrite(STDERR,'ISOLATED_WPLANG_DIAGNOSTIC_FAILED '.(in_array($e->getMessage(),$safe,true)?$e->getMessage():'FIXTURE_OR_UNEXPECTED_FAILURE')."\n");exit(1);
}
