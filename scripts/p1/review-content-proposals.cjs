/* Documentary preview only. No WordPress, hosted URL, secrets or transactions. */
const fs=require('node:fs'),path=require('node:path'),{pathToFileURL}=require('node:url');
const {chromium}=require('playwright');
(async()=>{
 const file=path.resolve(__dirname,'../../docs/p1/content-ux-proposals.html');
 const out=path.join(process.env.RUNNER_TEMP,'p1-content-review');fs.mkdirSync(out,{recursive:true});
 const browser=await chromium.launch({headless:true});
 const checks=[],errors=[];
 const ok=(value,label)=>{checks.push({label,passed:!!value});if(!value)throw new Error('PREVIEW_ASSERTION_FAILED: '+label);};
 try{
  const page=await browser.newPage();
  page.on('pageerror',e=>errors.push(e.name));
  // Embedded images only; do not contact a site or third party while reviewing.
  await page.route(/^https?:/,route=>route.abort());
  for(const width of [320,390,768,1024,1440]){
   await page.setViewportSize({width,height:900});await page.goto(pathToFileURL(file).href);
   const state=await page.evaluate(()=>({
    french:document.documentElement.lang==='fr-CA',
    overflow:document.documentElement.scrollWidth>innerWidth,
    images:[...document.images].every(i=>i.complete&&i.naturalWidth>0&&i.src.startsWith('data:image/jpeg;base64,')),
    h1:document.querySelectorAll('h1').length,
    forms:document.querySelectorAll('form,input,button,script').length,
   }));
   ok(state.french,`${width} documentary preview fr_CA`);
   ok(!state.overflow,`${width} documentary preview no overflow`);
   ok(state.images,`${width} reused embedded screenshots load`);
   ok(state.h1===1,`${width} single documentary H1`);
   ok(state.forms===0,`${width} no executable form or script`);
  }
  await page.setViewportSize({width:390,height:900});
  await page.locator('summary').focus();
  ok(await page.locator('summary').evaluate(e=>e===document.activeElement&&getComputedStyle(e).outlineStyle==='solid'),'preview keyboard focus visible');
  await page.keyboard.press('Enter');ok(await page.locator('details').getAttribute('open')!==null,'preview keyboard opens reused checkout evidence');
  await page.locator('details').evaluate(e=>e.open=false);
  ok(errors.length===0,'no preview JavaScript page errors');
  await page.screenshot({path:path.join(out,'proposals-390.png'),fullPage:true});
  await page.setViewportSize({width:1440,height:1000});
  await page.screenshot({path:path.join(out,'proposals-1440.png'),fullPage:true});
  await page.locator('h2').filter({hasText:'3. Infolettre'}).locator('xpath=following-sibling::div[1]').screenshot({path:path.join(out,'newsletter-proposal.png')});
  const report={scope:'static documentary preview only; no hosted or WordPress test',checks,failures:0,viewports:[320,390,768,1024,1440],reused_run:39843113925};
  fs.writeFileSync(path.join(out,'preview-checks.json'),JSON.stringify(report,null,2)+'\n');
  console.log(`PASS: ${checks.length} documentary preview checks; five widths, embedded evidence, keyboard; no hosted operation.`);
 }finally{await browser.close();}
})().catch(error=>{console.error(error.message.startsWith('PREVIEW_ASSERTION_FAILED: ')?error.message:'P1_CONTENT_PREVIEW_FAILED');process.exit(1);});
