/* Runs in GitHub Actions against 127.0.0.1 ONLY; no authentication or remote site. */
const fs = require('node:fs');
const http = require('node:http');
const path = require('node:path');
const { chromium } = require(path.join(process.env.RUNNER_TEMP, 'ad-qa-tools/node_modules/playwright'));
const base = 'http://127.0.0.1:8787';
const ids = JSON.parse(fs.readFileSync(path.join(process.env.RUNNER_TEMP, 'ad-isolated-qa/fixtures.json')));
const report = { scope: 'isolated-runner-only', layout: [], checks: [], warnings: [], failures: [] };
const assert = (condition, label) => { report.checks.push({label, passed: !!condition}); if (!condition) report.failures.push(label); };
const pauseUntilReady = () => new Promise((resolve, reject) => {
  let tries = 0;
  const probe = () => { const req = http.get(base, res => {res.resume(); if(res.statusCode===200) resolve(); else retry();}); req.on('error', retry); req.setTimeout(2000,()=>req.destroy()); };
  const retry = () => { if(++tries>=30) return reject(new Error('Local PHP server unavailable')); setTimeout(probe,1000); }; probe();
});
(async () => {
  await pauseUntilReady();
  const browser = await chromium.launch({headless:true});
  const page = await browser.newPage();
  page.on('pageerror', error => report.failures.push(`JavaScript: ${error.message}`));
  for(const width of [320,390,768,1024,1440]) {
    await page.setViewportSize({width,height:1000});
    await page.goto(base,{waitUntil:'networkidle'});
    await page.evaluate(()=>document.fonts.ready);
    assert(await page.getByRole('heading',{name:'La table que vous méritez',exact:true}).count()===1,`Homepage heading ${width}px`);
    const metrics=await page.evaluate(()=>({viewport:innerWidth,scrollWidth:document.documentElement.scrollWidth,brokenLocal:[...document.images].filter(e=>e.src.startsWith(location.origin)&&e.complete&&e.naturalWidth===0).length}));
    report.layout.push({width,...metrics});
    assert(metrics.scrollWidth<=width+1,`No horizontal overflow ${width}px`);
    assert(metrics.brokenLocal===0,`Local images loaded ${width}px`);
    if(width<=820){
      const menu=page.getByRole('button',{name:'Menu',exact:true}); await menu.click();
      assert(await menu.getAttribute('aria-expanded')==='true',`Mobile menu opens ${width}px`);
      await menu.press('Escape'); assert(await menu.getAttribute('aria-expanded')==='false',`Mobile menu closes with Escape ${width}px`);
    }
  }
  await page.goto(base+'/reference/index.html',{waitUntil:'networkidle'});
  assert(await page.getByRole('heading',{name:'La table que vous méritez',exact:true}).count()===1,'Official reference heading');
  await page.goto(`${base}/?p=${ids.simple}&post_type=product`,{waitUntil:'networkidle'});
  const cartForm=page.locator('form.cart');
  assert(await cartForm.count()===1,'Simple product native cart form');
  await cartForm.locator('input.qty').fill('2');
  await cartForm.getByRole('button',{name:'Add to cart',exact:true}).click();
  await page.goto(`${base}/?page_id=${ids.cart}`,{waitUntil:'networkidle'});
  assert(await page.locator('.woocommerce-cart-form input.qty').inputValue()==='2','Cart quantity survives HTTP navigation');
  assert((await page.locator('.cart-subtotal').innerText()).includes('450'),'Cart subtotal after 2 x 225');
  await page.locator('.woocommerce-cart-form input.qty').fill('3');
  const update=page.getByRole('button',{name:'Update cart',exact:true});
  await update.click();
  await page.waitForFunction(()=>document.querySelector('.cart-subtotal')?.innerText.includes('675'));
  assert((await page.locator('.cart-subtotal').innerText()).includes('675'),'Cart update to 3 x 225');
  await page.goto(`${base}/?p=${ids.out}&post_type=product`,{waitUntil:'networkidle'});
  assert(await page.locator('.out-of-stock').count()>0,'Out-of-stock message');
  assert(await page.locator('button.single_add_to_cart_button').count()===0,'Out-of-stock cannot be added');
  await page.goto(`${base}/?p=${ids.variable}&post_type=product`,{waitUntil:'networkidle'});
  await page.locator('select[name="attribute_portion"]').selectOption({label:'250 g'});
  await page.waitForFunction(()=>Number(document.querySelector('input.variation_id')?.value)>0);
  assert(Number(await page.locator('input.variation_id').inputValue())>0,'Variation selection resolves product id');
  await page.goto(`${base}/?page_id=${ids.checkout}`,{waitUntil:'networkidle'});
  assert(await page.locator('form.checkout').count()===1,'Checkout form rendered');
  assert(await page.locator('input[name="payment_method"]').count()===0,'No payment method enabled');
  report.warnings.push('No order placed. Tax/shipping/payment rules not tested. Hosted Coming Soon and GoDaddy cache not covered. Screenshots and HTML are not published.');
  await browser.close();
  console.log(JSON.stringify(report,null,2));
  if(process.env.GITHUB_STEP_SUMMARY) fs.appendFileSync(process.env.GITHUB_STEP_SUMMARY,'## Isolated QA\n```json\n'+JSON.stringify(report,null,2)+'\n```\n');
  if(report.failures.length) process.exitCode=1;
})().catch(error=>{console.error('Isolated QA failed:',error.message);process.exitCode=1;});
