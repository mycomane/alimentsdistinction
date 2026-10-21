/* French native WooCommerce labels and narrowly approved purchase controls. */
const fs=require('node:fs'),path=require('node:path');
const {chromium}=require(path.join(process.env.RUNNER_TEMP,'ad-qa-tools/node_modules/playwright'));
const dir=path.join(process.env.RUNNER_TEMP,'ad-isolated-qa'),ids=JSON.parse(fs.readFileSync(path.join(dir,'fixtures.json')));
const report={scope:'isolated French site only',checks:[],failures:[],screenshots:[],ordersSubmitted:0};
const check=(ok,label)=>{report.checks.push({label,passed:!!ok});if(!ok)report.failures.push(label);};
(async()=>{const browser=await chromium.launch();try{
 const page=await browser.newPage({reducedMotion:'reduce'});
 await page.route('**/*',route=>{const u=new URL(route.request().url());if(u.origin!=='http://127.0.0.1:8787'||u.searchParams.get('wc-ajax')==='checkout')return route.abort();return route.continue();});
 page.on('pageerror',e=>report.failures.push(e.message));
 for(const width of [320,390,768,1024,1440]){
  await page.setViewportSize({width,height:1000});
  await page.goto('http://127.0.0.1:8787/?p='+ids.variable,{waitUntil:'networkidle'});
  check((await page.locator('html').getAttribute('lang')).startsWith('fr'),width+' French document language');
  const select=page.locator('select[name="attribute_portion"]'),button=page.locator('button.single_add_to_cart_button');
  check(/Choisir une option/i.test(await select.locator('option').first().innerText()),width+' French native variation prompt');
  check(/Ajouter au panier/i.test(await button.innerText()),width+' French native add-to-cart label');
  const disabled=await button.evaluate(e=>({disabled:e.disabled||e.classList.contains('disabled'),bg:getComputedStyle(e).backgroundColor,opacity:getComputedStyle(e).opacity}));
  check(disabled.disabled&&disabled.bg==='rgb(228, 217, 206)'&&disabled.opacity==='1',width+' neutral disabled button');
  check(await select.evaluate(e=>getComputedStyle(e).borderTopColor)==='rgb(110, 105, 100)',width+' A variation border');
  check(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth),width+' no horizontal overflow');
  await select.selectOption({label:'250 g'});
  await page.waitForFunction(()=>!document.querySelector('button.single_add_to_cart_button').classList.contains('disabled'));
  check(await button.evaluate(e=>getComputedStyle(e).backgroundColor)==='rgb(148, 84, 75)',width+' A enabled button');
  await button.hover();check(await button.evaluate(e=>getComputedStyle(e).backgroundColor)==='rgb(113, 62, 55)',width+' A hover');
  await button.focus();check(await button.evaluate(e=>e===document.activeElement),width+' button keyboard focus');
  await page.mouse.move(0,0);
  if(width===390||width===1440){const name='french-controls-'+width+'.jpg';await page.locator('.summary').screenshot({path:path.join(dir,'evidence',name),type:'jpeg',quality:85});report.screenshots.push(name);}
 }
 }finally{await browser.close();fs.writeFileSync(path.join(dir,'evidence/french-controls.json'),JSON.stringify(report,null,2));}
 console.log(JSON.stringify(report));if(report.failures.length)process.exitCode=1;
})().catch(e=>{console.error(e);process.exitCode=1;});
