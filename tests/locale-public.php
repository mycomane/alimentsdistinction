<?php
require __DIR__.'/../scripts/locale/public-check.php';
$count=0;
foreach(['<h1>Coming Soon</h1>','<h1>Prochainement</h1>','<div class="coming-soon-hero">Prochainement</div>'] as $html){locale_public_check($html);$count++;}
foreach(['<h1>Shop open</h1>','<script>"coming soon"</script>Open','<style>.prochainement{}</style>Open','<h1>Prochainement</h1><section class="hero">Full theme</section>'] as $html){$rejected=false;try{locale_public_check($html);}catch(RuntimeException $e){$rejected=true;}if(!$rejected)throw new RuntimeException('Visibility guard accepted public shop/hidden marker');$count++;}
echo "PASS: $count English/French Coming Soon visibility assertions.\n";
