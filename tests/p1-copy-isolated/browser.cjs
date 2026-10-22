/* Actual local WordPress; only approved footer/copy zones, no order or login. */
const fs=require('node:fs'),path=require('node:path'),{execFileSync}=require('node:child_process');
const {chromium}=require('playwright'),postcss=require('postcss');
const repo=path.resolve(__dirname,'../..'),scope=require('./scope.json');
const root=path.join(process.env.RUNNER_TEMP,'ad-isolated-qa'),theme=path.join(root,'wordpress/wp-content/themes/aliments-distinction');
const out=path.join(root,'evidence');fs.mkdirSync(out,{recursive:true});
const origin='http://127.0.0.1:8787',prefix='wordpress/aliments-distinction/';
const checks=[],observations=[],errors=[],baselineGeometry={};
const ok=(value,label)=>{checks.push({label,passed:!!value});if(!value)throw new Error('P1_TARGETED_ASSERTION_FAILED: '+label);};
const luminance=rgb=>rgb.match(/[\d.]+/g).slice(0,3).map(Number).map(v=>{v/=255;return v<=.04045?v/12.92:((v+.055)/1.055)**2.4;}).reduce((s,v,i)=>s+v*[.2126,.7152,.0722][i],0);
const contrast=(a,b)=>{a=luminance(a);b=luminance(b);return(Math.max(a,b)+.05)/(Math.min(a,b)+.05);};
function install(phase){for(const file of Object.keys(scope.files)){
 const target=path.join(theme,file.slice(prefix.length));
 fs.writeFileSync(target,phase==='before'?execFileSync('git',['show',scope.baseline+':'+file],{cwd:repo}):fs.readFileSync(path.join(repo,file)));
}}
(async()=>{
 const browser=await chromium.launch({headless:true});let failure;
 try{
  const css=fs.readFileSync(path.join(repo,prefix,'style.css'),'utf8');postcss.parse(css);
  ok(true,'candidate theme CSS parses');
  const page=await browser.newPage();await page.emulateMedia({reducedMotion:'reduce'});page.on('pageerror',()=>errors.push('PAGE_ERROR'));
  await page.route(/^https?:/,route=>{
   const url=new URL(route.request().url());
   if(url.origin!==origin)return route.abort();
   if(url.pathname==='/newsletter-preview')return route.fulfill({status:200,contentType:'text/html',body:'<!doctype html><html lang="fr-CA"><h1>Destination d’essai isolée</h1></html>'});
   return route.continue();
  });
  for(const phase of ['before','after']){
   install(phase);
   for(const mode of ['unavailable','configured']){
    execFileSync('php',[path.join(__dirname,'fixture.php'),mode]);
    for(const width of [390,1440]){
     await page.setViewportSize({width,height:1000});await page.goto(origin+'/?p1_copy='+phase+'-'+mode+'-'+width,{waitUntil:'domcontentloaded'});
     await page.evaluate(()=>document.fonts.ready);await page.locator('.newsletter').scrollIntoViewIfNeeded();
     const state=await page.evaluate(()=>{
      const panel=document.querySelector('.ad-newsletter-panel'),text=document.querySelector('.newsletter .t-body'),link=document.querySelector('.newsletter a');
      const el=text||link,style=getComputedStyle(el);
      return{lang:document.documentElement.lang,overflow:document.documentElement.scrollWidth>innerWidth,
       panelCount:document.querySelectorAll('.ad-newsletter-panel').length,text:text?.textContent.trim(),
       foreground:style.color,background:getComputedStyle(panel||document.querySelector('.newsletter')).backgroundColor,
       contact:[...document.querySelectorAll('.footer a')].filter(a=>a.textContent.trim()==='Nous joindre').map(a=>a.href),
       phone:document.querySelector('.footer').textContent.includes('Commander par téléphone'),
       geometry:['.hero','.hero__left','.hero__right','.hero__title','.hero__trust'].map(selector=>{const r=document.querySelector(selector).getBoundingClientRect();return{x:r.x,y:r.y+scrollY,width:r.width,height:r.height};}),
       copy:document.querySelector('.hero__eyebrow').textContent.trim(),alt:document.querySelector('.hero__right img').alt,
      };
     });
     const label=phase+' '+mode+' '+width;
     ok(state.lang.toLowerCase()==='fr-ca',label+' actual French WordPress');
     ok(!state.overflow,label+' no horizontal overflow');
     ok(state.contact.length===1&&state.contact[0]===origin+'/#contact',label+' useful existing contact fallback');
     if(phase==='before')baselineGeometry[mode+'-'+width]=state.geometry;
     else{
      ok(state.panelCount===1,label+' single approved cream panel');
      ok(!state.phone,label+' no misleading phone entry');
      ok(state.copy==='Produits locaux · Québec'&&state.alt==='Sélection de produits Aliments Distinction',label+' approved French copy');
      ok(state.geometry.every((r,i)=>Object.keys(r).every(k=>Math.abs(r[k]-baselineGeometry[mode+'-'+width][i][k])<.5)),label+' hero geometry unchanged');
      ok(contrast(state.foreground,state.background)>=4.5,label+' text contrast on actual panel');
      if(mode==='unavailable')ok(state.text==='L’inscription à l’infolettre n’est pas encore disponible.',label+' neutral availability text');
      else{
       const link=page.locator('.newsletter a');await page.keyboard.press('Tab');await link.focus();
       const focus=await link.evaluate(e=>({active:e===document.activeElement,outline:getComputedStyle(e).outlineStyle,width:parseFloat(getComputedStyle(e).outlineWidth),color:getComputedStyle(e).outlineColor}));
       ok(focus.active&&focus.outline==='solid'&&focus.width>=3&&contrast(focus.color,state.background)>=3,label+' keyboard focus on panel');
       await link.hover();const hover=await link.evaluate(e=>({color:getComputedStyle(e).color,background:getComputedStyle(e).backgroundColor}));
       ok(contrast(hover.color,hover.background)>=4.5,label+' hover contrast');
      }
     }
     observations.push({phase,mode,width,textContrast:+contrast(state.foreground,state.background).toFixed(4),heroGeometry:state.geometry});
     await page.mouse.move(0,0);await page.locator('.newsletter').screenshot({path:path.join(out,'newsletter-'+phase+'-'+mode+'-'+width+'.png')});
     if(phase==='after'){
      await page.locator('.footer').screenshot({path:path.join(out,'footer-after-'+mode+'-'+width+'.png')});
      if(mode==='configured'){
       await page.locator('.newsletter a').click();await page.waitForURL(origin+'/newsletter-preview');
       ok(await page.locator('h1').textContent()==='Destination d’essai isolée',label+' link opens local simulated destination');
      }else{
       await page.getByRole('link',{name:'Nous joindre',exact:true}).click();
       ok(new URL(page.url()).hash==='#contact',label+' contact reaches local section');
      }
     }
    }
   }
  }
  ok(errors.length===0,'no page JavaScript errors during targeted review');
 }catch(e){failure=e;}
 finally{
  install('after');await browser.close();
  fs.writeFileSync(path.join(out,'p1-approved-copy.json'),JSON.stringify({scope:'targeted loopback WordPress only; external resources blocked; no login/order',baseline:scope.baseline,checks,observations,failures:checks.filter(c=>!c.passed).length+(failure&&!checks.some(c=>!c.passed)?1:0),external_fonts_and_photos_not_validated:true},null,2)+'\n');
 }
 if(failure)throw failure;
 console.log(`PASS: ${checks.length} targeted WordPress assertions; 390/1440, before/after, two newsletter states, focus/hover/contact, unchanged hero.`);
})().catch(e=>{console.error(e.message.startsWith('P1_TARGETED_ASSERTION_FAILED:')?e.message:'P1_TARGETED_REVIEW_FAILED');process.exit(1);});
