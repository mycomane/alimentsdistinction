/* Real HTTP interactions only with synthetic local products. Never submit checkout or create an order. */
const fs=require('node:fs'),path=require('node:path');
const {chromium}=require(path.join(process.env.RUNNER_TEMP,'ad-qa-tools/node_modules/playwright'));
const dir=path.join(process.env.RUNNER_TEMP,'ad-isolated-qa'),ids=JSON.parse(fs.readFileSync(path.join(dir,'fixtures.json'))),fixtures=JSON.parse(fs.readFileSync(path.join(dir,'extended-fixtures.json')));
const base='http://127.0.0.1:8787';
const report={scope:'isolated-only',stage:'catalogue',checks:[],ordersSubmitted:0,sorts:{},failures:[]};
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
 }finally{
  await browser.close();
  fs.mkdirSync(path.join(dir,'evidence'),{recursive:true});fs.writeFileSync(path.join(dir,'evidence/commerce-extended.json'),JSON.stringify(report,null,2));
  console.log('AD_COMMERCE_REPORT '+JSON.stringify(report));
  if(process.env.GITHUB_STEP_SUMMARY)fs.appendFileSync(process.env.GITHUB_STEP_SUMMARY,'## Isolated extended WooCommerce\n```json\n'+JSON.stringify(report,null,2)+'\n```\n');
 }
 if(report.failures.length)process.exitCode=1;
})().catch(e=>{console.error('Extended isolated commerce failed:',e.message);process.exitCode=1;});
