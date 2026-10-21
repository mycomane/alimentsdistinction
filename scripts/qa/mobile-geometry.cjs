/* Only the disposable 127.0.0.1 WordPress and official reference. No GoDaddy or credentials. */
const fs=require('node:fs');
const path=require('node:path');
const {chromium}=require(path.join(process.env.RUNNER_TEMP,'ad-qa-tools/node_modules/playwright'));
const base='http://127.0.0.1:8787';
const out=path.join(process.env.RUNNER_TEMP,'ad-isolated-qa/evidence');
fs.mkdirSync(out,{recursive:true});
const diagnostic=process.argv.includes('--diagnose');
const report={scope:'isolated-only',mode:diagnostic?'controlled-diagnosis':'actual-theme-regression',geometry:[],checks:[],failures:[]};
const check=(pass,label)=>{report.checks.push({label,passed:!!pass});if(!pass)report.failures.push(label);};
const measure=async page=>page.evaluate(()=>{
 const box=el=>{const r=el.getBoundingClientRect();return {x:+r.x.toFixed(2),y:+r.y.toFixed(2),width:+r.width.toFixed(2),height:+r.height.toFixed(2)};};
 const q=s=>box(document.querySelector(s));
 const trust=document.querySelector('.hero__trust');
 return {hero:q('.hero'),left:q('.hero__left'),photo:q('.hero__right'),title:q('.hero__title'),ctas:q('.hero__ctas'),trust:box(trust),wrap:getComputedStyle(trust).flexWrap,items:[...trust.children].map(el=>({box:box(el),label:box(el.querySelector('.trust-item__label')),clipped:el.scrollWidth>el.clientWidth+1||el.scrollHeight>el.clientHeight+1})),overflow:document.documentElement.scrollWidth>innerWidth+1};
});
const same=(a,b)=>Object.keys(a).every(k=>Math.abs(a[k]-b[k])<=1);
(async()=>{
 const browser=await chromium.launch({headless:true});
 try{
  const page=await browser.newPage();const ref=await browser.newPage();
  page.on('pageerror',e=>report.failures.push('JavaScript: '+e.message));
  for(const width of [320,390,768,1024,1440]){
   for(const p of [page,ref])await p.setViewportSize({width,height:1000});
   await page.goto(base,{waitUntil:'networkidle'});await ref.goto(base+'/reference/index.html',{waitUntil:'networkidle'});
   await Promise.all([page.evaluate(()=>document.fonts.ready),ref.evaluate(()=>document.fonts.ready)]);
   const reference=await measure(ref);
   // Replay the installed 0.1.0 rule as a negative control even after the fix is committed.
   const negative=await page.addStyleTag({content:'@media(max-width:820px){.hero__trust{flex-wrap:wrap;}}'});
   const before=await measure(page);
   const pictures={};
   if(width===390){
    pictures.reference=await ref.locator('.hero').screenshot({type:'jpeg',quality:65});
    pictures.before=await page.locator('.hero').screenshot({type:'jpeg',quality:65});
   }
   await negative.evaluate(el=>el.remove());
   let controlled;
   if(diagnostic)controlled=await page.addStyleTag({content:'@media(max-width:820px){.hero__trust{flex-wrap:nowrap;}}'});
   const after=await measure(page);
   report.geometry.push({width,reference,before,after,deltaBefore:+(before.hero.height-reference.hero.height).toFixed(2),deltaAfter:+(after.hero.height-reference.hero.height).toFixed(2)});
   if(width===320||width===390)check(Math.abs(before.hero.height-reference.hero.height-53)<=1,'Reproduce installed 53px difference '+width);
   for(const key of ['hero','left','photo','title','ctas','trust'])check(same(after[key],reference[key]),'Reference geometry '+key+' '+width);
   check(!after.overflow,'No horizontal overflow '+width);
   check(after.items.every(i=>!i.clipped),'No clipped trust item '+width);
   check(after.items.every((item,i,items)=>i===0||item.box.x>=items[i-1].box.x+items[i-1].box.width-1),'Trust items do not overlap '+width);
   check(after.wrap==='nowrap','Trust stays on reference flex line '+width);
   if(width<=820){
    const toggle=page.getByRole('button',{name:'Menu',exact:true});await toggle.click();check(await toggle.getAttribute('aria-expanded')==='true','Menu opens '+width);
    await toggle.press('Escape');check(await toggle.getAttribute('aria-expanded')==='false','Menu closes '+width);
    check(await toggle.evaluate(el=>el===document.activeElement),'Menu restores keyboard focus '+width);
   }
   if(width===390){
    await page.evaluate(()=>document.activeElement?.blur());
    pictures.after=await page.locator('.hero').screenshot({type:'jpeg',quality:65});
    for(const [name,data]of Object.entries(pictures))fs.writeFileSync(path.join(out,'hero-390-'+name+'.jpg'),data);
    const sheet=await browser.newPage({viewport:{width:1170,height:1720}});
    await sheet.setContent('<html lang="fr"><body style="margin:0;background:#faf7f2;font:18px Arial"><div style="display:flex">'+Object.entries(pictures).map(([name,data])=>'<div style="width:390px"><p style="padding:12px;margin:0">'+name+' · 390 px · isolé</p><img style="display:block;width:390px" src="data:image/jpeg;base64,'+data.toString('base64')+'"></div>').join('')+'</div></body></html>');
    const preview=await sheet.screenshot({type:'jpeg',quality:60,fullPage:true});
    fs.writeFileSync(path.join(out,'hero-comparison-390.jpg'),preview);
    console.log('AD_QA_PREVIEW_BASE64 '+preview.toString('base64'));
    await sheet.close();
   }
   if(controlled)await controlled.evaluate(el=>el.remove());
  }
 }finally{await browser.close();}
 fs.writeFileSync(path.join(out,'mobile-geometry.json'),JSON.stringify(report,null,2));
 console.log('AD_P0_REPORT '+JSON.stringify(report));
 if(process.env.GITHUB_STEP_SUMMARY)fs.appendFileSync(process.env.GITHUB_STEP_SUMMARY,'## Mobile geometry ('+report.mode+')\n```json\n'+JSON.stringify(report,null,2)+'\n```\n');
 if(report.failures.length)process.exitCode=1;
})().catch(e=>{console.error('Isolated geometry failed:',e.message);process.exitCode=1;});
