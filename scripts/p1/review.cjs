/* P1 isolated previews only. Reuses P0 geometry + synthetic fixtures, no hosted access. */
const fs=require('node:fs'),path=require('node:path');
const tools=path.join(process.env.RUNNER_TEMP,'ad-qa-tools/node_modules');
const {chromium}=require(path.join(tools,'playwright')),{PNG}=require(path.join(tools,'pngjs'));
const {compare}=require('../qa/hosted-p0-geometry.cjs');
const base='http://127.0.0.1:8787',root=path.join(process.env.RUNNER_TEMP,'ad-isolated-qa'),out=path.join(root,'evidence/p1');fs.mkdirSync(out,{recursive:true});
const ids=JSON.parse(fs.readFileSync(path.join(root,'fixtures.json')));
const css={common:fs.readFileSync('docs/p1/common.css','utf8'),a:fs.readFileSync('docs/p1/variant-a.css','utf8'),b:fs.readFileSync('docs/p1/variant-b.css','utf8')};
const report={scope:'isolated-proposals-only',checks:[],failures:[],geometry:[],contrasts:[],states:[],limits:['Pixel background sampling at 3px steps, text hidden to avoid antialias artifacts. Conservative minimum across text rectangles, not a full WCAG certification.','Disabled controls and decorative symbol-only text are classified separately.','No commercial settings or hosted session. No checkout submission.']};
const check=(v,label)=>{report.checks.push({label,passed:!!v});if(!v)report.failures.push(label);};
const rgb=s=>(s.match(/[\d.]+/g)||[]).slice(0,3).map(Number);
const lum=c=>c.map(x=>x/255).map(x=>x<=.04045?x/12.92:((x+.055)/1.055)**2.4).reduce((s,x,i)=>s+x*[.2126,.7152,.0722][i],0);
const ratio=(a,b)=>(Math.max(lum(a),lum(b))+.05)/(Math.min(lum(a),lum(b))+.05);
const label=v=>v==='base'?'Actuel':v.toUpperCase();
const shots={};
async function apply(page,v){if(v!=='base'){await page.evaluate(v=>document.documentElement.dataset.p1Variant=v,v);await page.addStyleTag({content:css.common+'\n'+css[v]});}}
async function load(page,url,v){await page.goto(url,{waitUntil:'networkidle'});await apply(page,v);await page.evaluate(()=>document.fonts.ready);}
async function geometry(page){return page.evaluate(()=>{
 const result={},box=el=>{const r=el.getBoundingClientRect();return {x:+r.x.toFixed(2),y:+r.y.toFixed(2),width:+r.width.toFixed(2),height:+r.height.toFixed(2)};};
 for(const s of ['.hero','.hero__left','.hero__right','.hero__title','.hero__ctas','.hero__trust']){const e=document.querySelector(s);result[s]=box(e);if(s==='.hero__trust'){result[s].flexWrap=getComputedStyle(e).flexWrap;result[s].items=[...e.children].map(x=>({...box(x),clipped:x.scrollWidth>x.clientWidth+1||x.scrollHeight>x.clientHeight+1}));}}
 return result;
});}
async function contrastAudit(page,v,width,kind='home'){
 await page.evaluate(()=>{document.activeElement?.blur();scrollTo(0,0);});
 const records=await page.evaluate(()=>{
  const rows=[],walker=document.createTreeWalker(document.body,NodeFilter.SHOW_TEXT);let node;
  const excluded='script,style,noscript,option,[aria-hidden="true"]';
  while(node=walker.nextNode()){
   const el=node.parentElement,text=node.textContent.trim();if(!text||el.closest(excluded)||/^[\s♛★✦◆•·→]+$/u.test(text))continue;
   const style=getComputedStyle(el),rect=el.getBoundingClientRect();if(!rect.width||!rect.height||style.visibility==='hidden'||style.display==='none')continue;
   const range=document.createRange();range.selectNodeContents(node);
   const rects=[...range.getClientRects()].filter(r=>r.width>0&&r.height>0).map(r=>({x:r.left+scrollX,y:r.top+scrollY,width:r.width,height:r.height}));
   if(!rects.length)continue;
   let opacity=1;for(let p=el;p;p=p.parentElement)opacity*=+getComputedStyle(p).opacity;
   if(opacity===0)continue;
   const id=rows.length;el.dataset.p1Contrast=id;
   rows.push({selector:el.tagName.toLowerCase()+'.'+String(el.className||'').trim().replace(/\s+/g,'.'),sample:text.slice(0,45),color:style.color,fontSize:parseFloat(style.fontSize),fontWeight:+style.fontWeight,opacity,rects,disabled:!!el.closest(':disabled,[aria-disabled="true"]'),logo:!!el.closest('.nav-logo__main,.footer__brand-name')});
  }
  return rows;
 });
 // Hide only text paint, preserving layout and actual photograph/overlay/background layers.
 await page.evaluate(()=>{window.p1OriginalStyles=new Map();for(const el of document.querySelectorAll('[data-p1-contrast]')){window.p1OriginalStyles.set(el,el.getAttribute('style'));el.style.setProperty('color','transparent','important');el.style.setProperty('-webkit-text-fill-color','transparent','important');el.style.setProperty('text-shadow','none','important');}});
 const png=PNG.sync.read(await page.screenshot({fullPage:true,animations:'disabled'}));
 await page.evaluate(()=>{for(const [el,s]of window.p1OriginalStyles){if(s===null)el.removeAttribute('style');else el.setAttribute('style',s);}delete window.p1OriginalStyles;});
 const measured=records.map(r=>{let minimum=21,count=0;const fg=rgb(r.color);for(const rect of r.rects){for(let y=Math.max(0,Math.ceil(rect.y+1));y<Math.min(png.height,rect.y+rect.height-1);y+=3)for(let x=Math.max(0,Math.ceil(rect.x+1));x<Math.min(png.width,rect.x+rect.width-1);x+=3){const i=(png.width*y+x)*4,bg=[png.data[i],png.data[i+1],png.data[i+2]],effective=fg.map((c,k)=>c*r.opacity+bg[k]*(1-r.opacity));minimum=Math.min(minimum,ratio(effective,bg));count++;}}
  const threshold=(r.fontSize>=24||(r.fontSize>=18.6667&&r.fontWeight>=700))?3:4.5;
  return {selector:r.selector,sample:r.sample,color:r.color,threshold,minimum:+minimum.toFixed(4),sampledPixels:count,pass:minimum>=threshold,exempt:r.disabled||r.logo};});
 const failures=measured.filter(r=>!r.pass&&!r.exempt&&r.sampledPixels);
 const groups={};for(const [name,re]of Object.entries({navigation:/nav-link|nav-logo__sub|ad-cart-count/,cta:/cta__/,newsletter:/newsletter|t-body/,secondary:/col-card__desc|trust-item__label|footer__copy/,buttons:/btn|button/})){const a=measured.filter(r=>re.test(r.selector));groups[name]={count:a.length,minimum:a.length?Math.min(...a.map(x=>x.minimum)):null};}
 const entry={variant:v,width,kind,nodes:measured.length,failed:failures.length,groups,failures};report.contrasts.push(entry);
 if(v!=='base')check(failures.length===0,`Measured text contrast ${v} ${kind} ${width}`);
 return entry;
}
async function controlSample(page,selector,state){return page.locator(selector).first().evaluate((el,state)=>{const s=getComputedStyle(el);return {state,color:s.color,background:s.backgroundColor,border:s.borderColor,outline:s.outlineColor,outlineWidth:s.outlineWidth,outlineStyle:s.outlineStyle,boxShadow:s.boxShadow,opacity:s.opacity,disabled:el.disabled===true||el.classList.contains('disabled'),ariaDisabled:el.getAttribute('aria-disabled')};},state);}
async function picture(page,name,locator){await page.evaluate(()=>document.activeElement?.blur());const data=locator?await page.locator(locator).screenshot({type:'jpeg',quality:72,animations:'disabled'}):await page.screenshot({type:'jpeg',quality:72,animations:'disabled'});shots[name]=data;fs.writeFileSync(path.join(out,name+'.jpg'),data);}
(async()=>{
 const browser=await chromium.launch({headless:true});
 try{
  const context=await browser.newContext({reducedMotion:'reduce'});
  await context.route('**/*',route=>{const u=new URL(route.request().url());if(u.origin===base&&(u.searchParams.get('wc-ajax')==='checkout'||u.pathname.includes('order-received')))return route.abort();return route.continue();});
  const page=await context.newPage(),ref=await context.newPage();page.on('pageerror',()=>report.failures.push('JavaScript pageerror'));
  for(const width of [320,390,768,1024,1440]){
   await Promise.all([page.setViewportSize({width,height:1000}),ref.setViewportSize({width,height:1000})]);
   await ref.goto(base+'/reference/index.html',{waitUntil:'networkidle'});await ref.evaluate(()=>document.fonts.ready);const reference=await geometry(ref);
   for(const v of ['base','a','b']){
    await load(page,base,v);const g=await geometry(page),max=Math.max(...Object.keys(g).flatMap(s=>['x','y','width','height'].map(k=>Math.abs(g[s][k]-reference[s][k]))));
    report.geometry.push({variant:v,width,maxDelta:+max.toFixed(2)});check(max===0,`P0 zero geometry delta ${v} ${width}`);
    for(const c of compare(g,reference,width))check(c.passed,`${v} ${c.label}`);
    check(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth+1),`No overflow ${v} ${width}`);
    await page.keyboard.press('Tab');check(await page.locator('.skip-link').evaluate(el=>document.activeElement===el&&el.getBoundingClientRect().top>=0),`Keyboard skip link ${v} ${width}`);
    if(width<=820){const t=page.getByRole('button',{name:'Menu',exact:true});await t.focus();await t.press('Enter');check(await t.getAttribute('aria-expanded')==='true',`Menu opens ${v} ${width}`);await t.press('Escape');check(await t.getAttribute('aria-expanded')==='false',`Menu closes ${v} ${width}`);}
    if(width===390||width===1440){
     await page.evaluate(()=>{document.activeElement?.blur();scrollTo(0,0);});
     await picture(page,`${v}-hero-${width}`,'.hero');
     await picture(page,`${v}-footer-${width}`,'.newsletter');
     const a=await contrastAudit(page,v,width);console.log('P1_CONTRAST_PROGRESS '+JSON.stringify({v,width,nodes:a.nodes,failed:a.failed,failures:a.failures.slice(0,14)}));
     if(v!=='base'){
      const button=page.locator('.hero__ctas .btn-blush').first();await button.hover();await page.waitForTimeout(350);
      const hover=await controlSample(page,'.hero__ctas .btn-blush','hover');check(ratio(rgb(hover.color),rgb(hover.background))>=4.5,`Hero hover AA ${v} ${width}`);
      await page.keyboard.press('Tab');await button.focus();const focus=await controlSample(page,'.hero__ctas .btn-blush','focus');check(focus.outlineStyle==='solid'&&parseFloat(focus.outlineWidth)>=3,`Hero focus visible ${v} ${width}`);report.states.push({variant:v,width,hover,focus});
     }
     await page.evaluate(()=>scrollTo(0,260));await page.waitForTimeout(450);
     const scrolled=await page.locator('.nav').evaluate(e=>getComputedStyle(e).backgroundColor);report.states.push({variant:v,width,state:'navigation_scrolled',background:scrolled});
     if(v!=='base'){const target=page.locator('.nav a').first();await target.focus();const f=await controlSample(page,'.nav a','focus');check(f.outlineStyle==='solid'&&parseFloat(f.outlineWidth)>=3&&f.boxShadow.includes('255, 255, 255'),`Bicolor nav focus ${v} ${width}`);}
    }
   }
  }
  // Only isolated synthetic cart/checkout; no submission. Reuse native fixtures/variation options.
  await page.setViewportSize({width:390,height:1000});
  for(const v of ['base','a','b']){
   await context.clearCookies();await load(page,base+'/?p='+ids.variable,v);
   const button='.single_add_to_cart_button';
   await picture(page,`${v}-disabled-390`,'.summary');
   const disabled=await controlSample(page,button,'unselected');report.states.push({variant:v,...disabled});
   if(v!=='base'){check(disabled.disabled,'Variable unselected stays disabled '+v);check(ratio(rgb(disabled.color),rgb(disabled.background))>=4.5,'Disabled legible '+v);}
   await page.locator('select[name="attribute_portion"]').selectOption('500 g');await page.waitForFunction(()=>!document.querySelector('.single_add_to_cart_button').classList.contains('disabled'));
   await page.mouse.move(0,0);await page.waitForTimeout(350);const normal=await controlSample(page,button,'enabled');await page.locator(button).hover();await page.waitForTimeout(350);const hover=await controlSample(page,button,'hover');await page.locator(button).focus();const focus=await controlSample(page,button,'focus');
   report.states.push({variant:v,normal,hover,focus});
   if(v!=='base'){check(ratio(rgb(normal.color),rgb(normal.background))>=4.5,'Woo normal button AA '+v);check(ratio(rgb(hover.color),rgb(hover.background))>=4.5,'Woo hover button AA '+v);check(focus.outlineStyle==='solid'&&parseFloat(focus.outlineWidth)>=3,'Woo visible focus '+v);}
   await page.locator('select[name="attribute_portion"]').selectOption('1 kg');await page.waitForTimeout(400);check(await page.locator(button).isDisabled()||await page.locator(button).evaluate(e=>e.classList.contains('disabled')),'Unavailable variation remains blocked '+v);
   await load(page,base+'/?p='+ids.out,v);if(v!=='base')await contrastAudit(page,v,390,'out_of_stock');
   // Add only local synthetic simple product, through native GET; never checkout POST.
   await load(page,base+'/?add-to-cart='+ids.simple,v);await load(page,base+'/?page_id='+ids.checkout,v);
   check(await page.locator('form.checkout').count()===1,'Isolated checkout present '+v);
   const first=page.locator('#billing_first_name');await first.fill('');await first.focus();await page.keyboard.press('Tab');
   check(await first.evaluate(e=>e.closest('.form-row').classList.contains('woocommerce-invalid')),'Required-field error preserved '+v);
   if(v!=='base'){const state=await controlSample(page,'#billing_first_name','invalid');report.states.push({variant:v,...state});check(ratio(rgb(state.border),rgb(state.background))>=3,'Invalid field boundary AA '+v);await contrastAudit(page,v,390,'checkout');}
   await picture(page,`${v}-checkout-390`,'main');
  }
  // Comparison boards use only screenshots from this disposable environment.
  const sheet=await browser.newPage({viewport:{width:1200,height:900}});
  for(const [board,width,type]of [['desktop',1440,'hero'],['mobile',390,'hero'],['newsletter',390,'footer'],['woo-states',390,'disabled']]){
   const labels={base:'Actuel · 0.1.1',a:'A · Évolution discrète',b:'B · Lecture renforcée'};
   const cards=['base','a','b'].map(v=>{const data=shots[`${v}-${type}-${width}`];const c=report.contrasts.find(x=>x.variant===v&&x.width===width&&x.kind==='home');return `<article><h2>${labels[v]}</h2><p>${v==='base'?'Référence à améliorer':'Proposition isolée · aucun déploiement'}<br>${c?c.failed+' échantillons de texte sous le seuil sur l’accueil':''}</p><img src="data:image/jpeg;base64,${data.toString('base64')}"></article>`;}).join('');
   await sheet.setContent(`<html lang="fr"><head><style>*{box-sizing:border-box}body{margin:0;background:#f4ede3;color:#2c2a27;font:15px Arial}header{padding:18px 22px;background:#faf7f2}h1{margin:0;font:26px Georgia}section{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;padding:12px}article{background:#fff;min-width:0}h2{font-size:19px;margin:16px 14px 8px}p{font-size:12px;margin:0 14px 15px;line-height:1.5}img{width:100%;display:block}</style></head><body><header><h1>Aliments Distinction · P1 · ${board}</h1><p style="margin:8px 0 0">Source WordPress isolé · Maquette GitHub · Rose décoratif conservé · Choix esthétique à approuver</p></header><section>${cards}</section></body></html>`);
   const data=await sheet.screenshot({type:'jpeg',quality:78,fullPage:true});fs.writeFileSync(path.join(out,'choice-'+board+'.jpg'),data);console.log('P1_IMAGE '+JSON.stringify({name:'choice-'+board+'.jpg',base64:data.toString('base64')}));
  }
 }finally{await browser.close();fs.writeFileSync(path.join(out,'p1-report.json'),JSON.stringify(report,null,2));console.log('P1_REPORT '+JSON.stringify(report));}
 if(report.failures.length)process.exitCode=1;
})().catch(e=>{console.error('P1 isolated review incomplete: '+e.message);process.exitCode=1;});
