<?php
/** Same visibility check in the two observed official locale wordings. */
function locale_public_check(string $html):void {
 $html=preg_replace('~<(script|style)\b[^>]*>.*?</\1>~is','',$html);
 $text=strtolower(html_entity_decode(strip_tags($html)));
 if(!str_contains($text,'coming soon')&&!str_contains($text,'prochainement'))throw new RuntimeException('Anonymous Coming Soon not confirmed in expected locale');
 preg_match_all('~\bclass=["\']([^"\']*)["\']~i',$html,$classes);
 foreach($classes[1] as $value){if(in_array('hero',preg_split('/\s+/',trim(html_entity_decode($value))),true))throw new RuntimeException('Public theme hero detected; stop');}
}
if(realpath($_SERVER['SCRIPT_FILENAME'])===__FILE__){try{locale_public_check(file_get_contents($argv[1]));echo "PASS: anonymous Coming Soon verified in English/French wording.\n";}catch(Throwable $e){fwrite(STDERR,$e->getMessage()."\n");exit(1);}}
