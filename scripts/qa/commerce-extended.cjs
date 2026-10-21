/* Real HTTP interactions only with synthetic local products. Never submit checkout or create an order. */
const fs=require('node:fs'),path=require('node:path');
const {chromium}=require(path.join(process.env.RUNNER_TEMP,'ad-qa-tools/node_modules/playwright'));
const dir=path.join(process.env.RUNNER_TEMP,'ad-isolated-qa'),ids=JSON.parse(fs.readFileSync(path.join(dir,'fixtures.json'))),fixtures=JSON.parse(fs.readFileSync(path.join(dir,'extended-fixtures.json')));
const base='http://127.0.0.1:8787';
const report={scope:'isolated-only',stage:'catalogue',checks:[],ordersSubmitted:0,sorts:{},responsive:[],focusReview:[],failures:[]};
const check=(passed,label)=>{report.checks.push({label,passed:!!passed});if(!passed)report.failures.push(label);};
const orderedIds=page=>page.locator('ul.products li.product').evaluateAll(els=>els.map(e=>Number(e.className.match(/\bpost-(\d+)\b/)?.[1])));
(async()=>{
 const browser=await chromium.launch({headless:true});
 try{
  const context=await browser.newContext();
  await context.route('**/*',route=>{
   const u=new URL(route.request().url());
   if(u.origin===base&&(u.searchParams.get('wc-ajax')==='checkout'||u.pathname.includes('order-received')))return route.abort();
   return route.continue();
  });
  const page=await context.newPage();page.on('pageerror',e=>report.failures.push('JavaScript: '+e.message));
  const collect=async url=>{
   const collected=[];let pages=0;
   while(url&&pages<8){
    await page.goto(url,{waitUntil:'networkidle'});collected.push(...await orderedIds(page));pages++;
    const next=page.locator('.woocommerce-pagination a.next');url=await next.count()?await next.getAttribute('href'):null;if(url)url=new URL(url,base).href;
   }
   return {ids:collected,pages};
  };
  const expected=fixtures.products.map(p=>p.id);
  const asc=await collect(base+'/?product_cat=qa-recette&orderby=price');report.sorts.ascending=asc;
  check(JSON.stringify(asc.ids)===JSON.stringify(expected),'Numeric ascending order across all 12 products');
  check(asc.pages===4&&new Set(asc.ids).size===12,'Four pagination pages without duplicate or missing product');
  const desc=await collect(base+'/?product_cat=qa-recette&orderby=price-desc');report.sorts.descending=desc;
  check(JSON.stringify(desc.ids)===JSON.stringify([...expected].reverse()),'Numeric descending order across all 12 products');
  const filtered=await collect(base+'/?product_cat=qa-recette&orderby=price&min_price=20&max_price=40');report.sorts.filtered=filtered;
  check(JSON.stringify(filtered.ids)===JSON.stringify(fixtures.products.filter(p=>p.price>=20&&p.price<=40).map(p=>p.id)),'Price filter applies real 20–40 inclusive range');
  report.stage='variations';
  await page.goto(base+'/?p='+ids.variable+'&post_type=product',{waitUntil:'networkidle'});
  for(const portion of ['250 g','500 g']){
   await page.locator('select[name="attribute_portion"]').selectOption({label:portion});
   await page.locator('.woocommerce-variation-add-to-cart-enabled button:not(.disabled)').waitFor({state:'visible'});
   // A previous variation can leave the button enabled while the next price live region updates.
   await page.waitForFunction(price=>document.querySelector('.woocommerce-variation-price')?.textContent.includes(price),portion==='250 g'?'20':'35');
   check(Number(await page.locator('input.variation_id').inputValue())>0,'Available variation resolves '+portion);
   check((await page.locator('.woocommerce-variation-price').innerText()).includes(portion==='250 g'?'20':'35'),'Variation price changes '+portion);
  }
  await page.locator('button.single_add_to_cart_button').click();await page.waitForLoadState('networkidle');
  await page.goto(base+'/?page_id='+ids.cart,{waitUntil:'networkidle'});
  check((await page.locator('.woocommerce-cart-form').innerText()).includes('500 g'),'Second variation added with correct portion');
  check((await page.locator('.cart-subtotal').innerText()).includes('35'),'Second variation subtotal is 35');
  await page.goto(base+'/?p='+ids.variable+'&post_type=product',{waitUntil:'networkidle'});
  const unavailable=page.locator('select[name="attribute_portion"] option[value="1 kg"]');
  check(await unavailable.count()===1,'Out-of-stock variation option is present in persisted fixture');
  if(await unavailable.count()===1){
   if(await unavailable.isDisabled())check(true,'Unavailable variation option cannot be selected');
   else{
    await page.locator('select[name="attribute_portion"]').selectOption({label:'1 kg'});
    await page.locator('button.wc-variation-is-unavailable').waitFor({state:'visible'});
    check(await page.locator('button.single_add_to_cart_button.disabled').count()===1,'Unavailable variation blocks purchase');
   }
  }
  const stockout=await context.request.post(base+'/?p='+ids.variable+'&post_type=product',{form:{'add-to-cart':String(ids.variable),product_id:String(ids.variable),variation_id:String(fixtures.stockout_variation),attribute_portion:'1 kg',quantity:'1'}});
  check(await page.evaluate(html=>new DOMParser().parseFromString(html,'text/html').querySelectorAll('.woocommerce-error').length,await stockout.text())>0,'Server rejects an unavailable variation');
  report.stage='quantity_validation';
  await page.goto(base+'/?p='+ids.simple+'&post_type=product',{waitUntil:'networkidle'});
  await page.locator('input.qty').fill('6');check(await page.locator('input.qty').evaluate(e=>e.validity.rangeOverflow),'Browser quantity limit reflects available stock');
  const response=await context.request.post(base+'/?p='+ids.simple+'&post_type=product',{form:{'add-to-cart':String(ids.simple),quantity:'6'}});
  const errors=await page.evaluate(html=>new DOMParser().parseFromString(html,'text/html').querySelectorAll('.woocommerce-error').length,await response.text());
  check(errors>0,'Server rejects quantity exceeding stock');
  await page.goto(base+'/?page_id='+ids.cart,{waitUntil:'networkidle'});
  check(await page.locator('tr.cart_item').count()===1,'Rejected stock quantity does not add another cart line');
  report.stage='checkout_inspection_no_submission';
  await page.goto(base+'/?page_id='+ids.checkout,{waitUntil:'networkidle'});
  check(await page.locator('form.checkout').count()===1,'Classic checkout renders with nonempty synthetic cart');
  check(await page.locator('.validate-required input').count()>0,'Checkout required billing controls are present');
  check(await page.locator('input[name="payment_method"]').count()===0,'Checkout has no available payment gateway');
  check(await page.locator('form.checkout input[name="woocommerce-process-checkout-nonce"]').count()===1,'Native checkout nonce present without submission');
  report.finalCheckoutPath=new URL(page.url()).pathname+new URL(page.url()).search;
  report.stage='responsive_commerce';
  const screens=[];
  const destinations=[['catalogue',base+'/?product_cat=qa-recette'],['simple',base+'/?p='+ids.simple+'&post_type=product'],['variable',base+'/?p='+ids.variable+'&post_type=product'],['panier',base+'/?page_id='+ids.cart],['commande',base+'/?page_id='+ids.checkout]];
  for(const width of [320,390,768,1024,1440]){
   await page.setViewportSize({width,height:1000});
   for(const [key,url]of destinations){
    await page.goto(url,{waitUntil:'networkidle'});await page.evaluate(()=>document.fonts.ready);
    const metrics=await page.evaluate(()=>({overflow:document.documentElement.scrollWidth>innerWidth+1,brokenLocal:[...document.images].filter(i=>i.src.startsWith(location.origin)&&i.complete&&i.naturalWidth===0).length,h1:document.querySelectorAll('h1').length,unlabeled:[...document.querySelectorAll('#main-content input:not([type=hidden]),#main-content select,#main-content textarea')].filter(e=>e.getClientRects().length&&!e.labels?.length&&!e.getAttribute('aria-label')&&!e.getAttribute('aria-labelledby')&&!e.getAttribute('title')&&!['submit','button'].includes(e.type)).map(e=>e.name)}));
    report.responsive.push({width,page:key,...metrics});
    check(!metrics.overflow,'Commerce no horizontal overflow '+key+' '+width);
    check(metrics.brokenLocal===0,'Commerce local images loaded '+key+' '+width);
    check(metrics.h1===1,'Commerce one H1 '+key+' '+width);
    check(metrics.unlabeled.length===0,'Commerce controls labeled '+key+' '+width);
    if(width===390||width===1440){
     const first=page.locator('#main-content input:visible:not([type=hidden])').first();
     if(await first.count()){
      await first.focus();await page.keyboard.press('Tab');await page.keyboard.press('Shift+Tab');
      check(await first.evaluate(e=>document.activeElement===e),'Commerce keyboard focus returns '+key+' '+width);
      const indicator=await first.evaluate(e=>{const s=getComputedStyle(e);return {outline:s.outlineStyle,width:s.outlineWidth,boxShadow:s.boxShadow};});
      report.focusReview.push({width,page:key,...indicator,needsManualReview:(indicator.outline==='none'||parseFloat(indicator.width)===0)&&indicator.boxShadow==='none'});
     }
    }
    if(width===390){
     await page.evaluate(()=>document.activeElement?.blur());
     const picture=await page.locator('#main-content').screenshot({type:'jpeg',quality:60});
     fs.mkdirSync(path.join(dir,'evidence'),{recursive:true});fs.writeFileSync(path.join(dir,'evidence/commerce-'+key+'-390.jpg'),picture);
     if(key!=='catalogue')screens.push({key,picture});
    }
   }
  }
  const sheet=await browser.newPage({viewport:{width:1560,height:2200}});
  await sheet.setContent('<body style="margin:0;background:#faf7f2;font:18px Arial"><div style="display:flex;align-items:flex-start">'+screens.map(({key,picture})=>'<div style="width:390px"><p style="padding:12px">'+key+' · isolé · 390 px</p><img style="width:390px" src="data:image/jpeg;base64,'+picture.toString('base64')+'"></div>').join('')+'</div></body>');
  const picture=await sheet.screenshot({type:'jpeg',quality:60,fullPage:true});fs.writeFileSync(path.join(dir,'evidence/commerce-comparison-390.jpg'),picture);
  console.log('AD_COMMERCE_PREVIEW_BASE64 '+picture.toString('base64'));
 }finally{
  await browser.close();
  fs.mkdirSync(path.join(dir,'evidence'),{recursive:true});fs.writeFileSync(path.join(dir,'evidence/commerce-extended.json'),JSON.stringify(report,null,2));
  console.log('AD_COMMERCE_REPORT '+JSON.stringify(report));
  if(process.env.GITHUB_STEP_SUMMARY)fs.appendFileSync(process.env.GITHUB_STEP_SUMMARY,'## Isolated extended WooCommerce\n```json\n'+JSON.stringify(report,null,2)+'\n```\n');
 }
 if(report.failures.length)process.exitCode=1;
})().catch(e=>{console.error('Extended isolated commerce failed:',e.message);process.exitCode=1;});
