/* Isolated-only section, link and keyboard audit; no remote credentials or commerce submission. */
const fs=require('node:fs'),path=require('node:path');
const {chromium}=require(path.join(process.env.RUNNER_TEMP,'ad-qa-tools/node_modules/playwright'));
const base='http://127.0.0.1:8787',out=path.join(process.env.RUNNER_TEMP,'ad-isolated-qa/evidence');
fs.mkdirSync(out,{recursive:true});
const report={scope:'isolated-only',checks:[],sections:[],destinations:[],contrastSamples:[],findings:[],failures:[]};
const check=(passed,label)=>{report.checks.push({label,passed:!!passed});if(!passed)report.failures.push(label);};
(async()=>{
 const browser=await chromium.launch({headless:true});
 try{
  const page=await browser.newPage();page.on('pageerror',e=>report.failures.push('JavaScript: '+e.message));
  for(const width of [320,390,768,1024,1440]){
   await page.setViewportSize({width,height:1000});await page.goto(base,{waitUntil:'networkidle'});await page.evaluate(()=>document.fonts.ready);
   const sections=await page.evaluate(()=>['.hero','.features-strip','.collections','.promise','.featured','.story','.recettes','.process','.testimonials','.health-strip','.cta','.ornate-frame','.newsletter','.footer'].map(selector=>{
    const el=document.querySelector(selector);if(!el)return {selector,present:false};const r=el.getBoundingClientRect();
    return {selector,present:true,x:Math.round(r.x),width:Math.round(r.width),height:Math.round(r.height),headingCount:el.querySelectorAll('h1,h2,h3').length};
   }));
   report.sections.push({width,sections});
   check(sections.every(s=>s.present&&s.height>0),'All 14 homepage regions rendered '+width);
   check(sections.every(s=>s.x>=-1&&s.x+s.width<=width+1),'Homepage region bounds '+width);
   const bad=await page.evaluate(()=>[...document.querySelectorAll('h1,h2,h3')].filter(el=>{const r=el.getBoundingClientRect();return r.width>0&&(r.left< -1||r.right>innerWidth+1);}).length);
   check(bad===0,'Headings stay within viewport '+width);
   // Exercise actual keyboard focus, not programmatic focus-only styling.
   await page.keyboard.press('Tab');
   check(await page.locator('.skip-link').evaluate(el=>el===document.activeElement),'First keyboard stop is skip link '+width);
   check(await page.locator('.skip-link').evaluate(el=>el.getBoundingClientRect().top>=0),'Skip link becomes visible '+width);
   await page.keyboard.press('Enter');check(new URL(page.url()).hash==='#main-content','Skip link reaches main '+width);
   if(width<=820){
    const toggle=page.getByRole('button',{name:'Menu',exact:true});await toggle.focus();await toggle.press('Enter');
    check(await toggle.getAttribute('aria-expanded')==='true','Menu keyboard Enter opens '+width);
    await page.locator('#ad-navigation a').first().click();check(await toggle.getAttribute('aria-expanded')==='false','Anchor click closes menu '+width);
   }else check(await page.locator('#ad-navigation').isVisible(),'Desktop navigation visible '+width);
   if(width===390||width===1440){
    await page.evaluate(()=>{document.activeElement?.blur();scrollTo(0,0);});
    const screenshot=await page.screenshot({path:path.join(out,'homepage-'+width+'.jpg'),type:'jpeg',quality:65,fullPage:true});
    console.log('AD_HOMEPAGE_'+width+'_BASE64 '+screenshot.toString('base64'));
   }
  }
  await page.goto(base,{waitUntil:'networkidle'});
  const links=await page.locator('a[href]').evaluateAll(els=>els.map(a=>({text:a.getAttribute('aria-label')||a.textContent.trim().replace(/\s+/g,' '),url:a.href})));
  const anchors=await page.evaluate(()=>[...document.querySelectorAll('a[href]')].filter(a=>a.origin===location.origin&&a.pathname==='/'&&a.hash).map(a=>({hash:a.hash,exists:!!document.getElementById(decodeURIComponent(a.hash.slice(1)))})));
  check(anchors.every(a=>a.exists),'All internal homepage anchors resolve');
  check(!links.some(l=>l.url.endsWith('/#')),'No empty placeholder link');
  const accessible=await page.evaluate(()=>({unnamed:[...document.querySelectorAll('a,button')].filter(e=>!e.textContent.trim()&&!e.getAttribute('aria-label')&&!e.querySelector('img[alt]')).length,imagesWithoutAlt:document.querySelectorAll('img:not([alt])').length,h1:document.querySelectorAll('h1').length}));
  check(accessible.unnamed===0,'Homepage links and buttons have accessible names');check(accessible.imagesWithoutAlt===0,'Homepage images have alt attributes');check(accessible.h1===1,'One homepage H1');
  const urls=[...new Set(links.map(l=>l.url).filter(url=>{const u=new URL(url);return u.origin===base&&!u.hash&&!u.searchParams.has('add-to-cart');}))];
  for(const url of urls){const response=await page.request.get(url,{maxRedirects:4});report.destinations.push({path:new URL(url).pathname+new URL(url).search,status:response.status()});check(response.ok()&&new URL(response.url()).origin===base,'Internal destination '+new URL(url).pathname+new URL(url).search);}
  check(await page.locator('.newsletter input,.newsletter form').count()===0,'No fake newsletter subscription form');
  check((await page.locator('.newsletter').innerText()).includes('L’inscription à l’infolettre n’est pas encore disponible.'),'Newsletter unavailability stated');
  report.findings.push({severity:'major',area:'content',detail:'FAQ, contact, phone and delivery policy use homepage-section fallbacks; complete approved content before launch.'});
  check(await page.locator('.newsletter .ad-newsletter-panel').count()===1,'Approved newsletter panel rendered');
  // Newsletter contrast is measured in tests/p1-copy-isolated/browser.cjs for both states.
  report.findings.push({severity:'major',area:'accessibility',detail:'Known rose/gray text and buttons require approved contrast adjustment; no global palette changed.'});
  report.findings.push({severity:'major',area:'commerce-content',detail:'Marketing quantities, origin, testimonials, guarantees and financing require owner validation.'});
  report.contrastSamples=await page.evaluate(()=>['.nav-link','.hero__title em','.trust-item__label','.btn-blush','.col-card__desc','.newsletter .t-body','.footer__copy'].map(selector=>{
   const el=document.querySelector(selector);if(!el)return {selector,present:false};const s=getComputedStyle(el);let bg=el;while(bg&&getComputedStyle(bg).backgroundColor==='rgba(0, 0, 0, 0)')bg=bg.parentElement;
   return {selector,color:s.color,background:bg?getComputedStyle(bg).backgroundColor:null,fontSize:s.fontSize,fontWeight:s.fontWeight};
  }));
  const preview=await browser.newPage({viewport:{width:1160,height:1000}});
  await preview.setContent(fs.readFileSync(path.resolve('docs/previews/contrast-options.html'),'utf8'));
  await preview.evaluate(()=>document.fonts.ready);
  const picture=await preview.screenshot({type:'jpeg',quality:75,fullPage:true});
  fs.writeFileSync(path.join(out,'contrast-proposal.jpg'),picture);
  console.log('AD_QA_CONTRAST_PREVIEW_BASE64 '+picture.toString('base64'));
 }finally{await browser.close();}
 fs.writeFileSync(path.join(out,'sections-audit.json'),JSON.stringify(report,null,2));console.log('AD_SECTIONS_REPORT '+JSON.stringify(report));
 if(process.env.GITHUB_STEP_SUMMARY)fs.appendFileSync(process.env.GITHUB_STEP_SUMMARY,'## Isolated sections audit\n```json\n'+JSON.stringify(report,null,2)+'\n```\n');
 if(report.failures.length)process.exitCode=1;
})().catch(e=>{console.error('Isolated sections audit failed:',e.message);process.exitCode=1;});
