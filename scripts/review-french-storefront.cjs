/* French-only follow-up. Synthetic loopback products; checkout submission is blocked. */
const fs=require('node:fs'),path=require('node:path');
const {chromium}=require(path.join(process.env.RUNNER_TEMP,'ad-qa-tools/node_modules/playwright'));
const dir=path.join(process.env.RUNNER_TEMP,'ad-isolated-qa'),base='http://127.0.0.1:8787';
const ids=JSON.parse(fs.readFileSync(path.join(dir,'fixtures.json')));
const report={scope:'isolated French storefront only',checks:[],failures:[],labels:{},ordersSubmitted:0};
const check=(ok,label)=>{report.checks.push({label,passed:!!ok});if(!ok)report.failures.push(label);};
const text=async locator=>(await locator.innerText()).replace(/\s+/g,' ').trim();
(async()=>{const browser=await chromium.launch();try{
 const context=await browser.newContext({viewport:{width:390,height:1000},reducedMotion:'reduce'});
 await context.route('**/*',route=>{const u=new URL(route.request().url());if(u.origin!==base||u.searchParams.get('wc-ajax')==='checkout'||u.pathname.includes('order-received'))return route.abort();return route.continue();});
 const page=await context.newPage();page.on('pageerror',e=>report.failures.push(e.message));
 const goto=async id=>page.goto(base+'/?page_id='+id,{waitUntil:'networkidle'});
 await goto(ids.cart);report.labels.emptyCart=await text(page.locator('#main-content'));
 check(/panier est vide/i.test(report.labels.emptyCart),'Empty cart notice is French');
 check(/Retour (?:à|vers) la boutique/i.test(report.labels.emptyCart),'Empty cart return link is French');
 await goto(ids.shop);report.labels.sorting=await text(page.locator('select.orderby'));
 check(/Tri par défaut/i.test(report.labels.sorting),'Catalog default sorting is French');
 check(/(?:prix|tarif) croissant/i.test(report.labels.sorting)&&/(?:prix|tarif) décroissant/i.test(report.labels.sorting),'Price sorting options are French');
 await page.goto(base+'/?p='+ids.out,{waitUntil:'networkidle'});
 report.labels.stockout=await text(page.locator('.summary'));
 check(/Rupture de stock/i.test(report.labels.stockout),'Stockout message is French');
 check(await page.locator('button.single_add_to_cart_button').count()===0,'Stockout product cannot be bought');
 await page.goto(base+'/?p='+ids.variable,{waitUntil:'networkidle'});
 const select=page.locator('select[name="attribute_portion"]'),button=page.locator('button.single_add_to_cart_button');
 check(/Choisir une option/i.test(await text(select.locator('option').first())),'Native variation placeholder is French');
 check(/Ajouter au panier/i.test(await text(button)),'Product add-to-cart label is French');
 fs.mkdirSync(path.join(dir,'evidence'),{recursive:true});
 await page.locator('.summary').screenshot({path:path.join(dir,'evidence/french-variable-unselected.jpg'),type:'jpeg',quality:85});
 await select.selectOption('250 g');await page.waitForFunction(()=>!document.querySelector('button.single_add_to_cart_button').classList.contains('disabled'));
 check(/Effacer/i.test(await text(page.locator('.reset_variations'))),'Variation reset link is French');
 await page.locator('input.qty').fill('2');await button.click();await page.waitForLoadState('networkidle');
 await goto(ids.cart);report.labels.cart=await text(page.locator('#main-content'));
 check(await page.locator('tr.cart_item').count()===1,'Selected synthetic variation reaches the cart');
 check(await page.locator('input.qty').inputValue()==='2','Synthetic quantity two is retained');
 check(/Mettre à jour le panier/i.test(report.labels.cart),'Cart update label is French');
 check(/Valider la commande/i.test(report.labels.cart),'Proceed-to-checkout label is French');
 check(/Sous-total/i.test(report.labels.cart),'Cart subtotal label is French');
 await page.locator('input.qty').fill('1');const [updated]=await Promise.all([page.waitForResponse(r=>r.request().method()==='POST'&&new URL(r.url()).origin===base),page.locator('button[name="update_cart"]').click()]);await updated.finished();await goto(ids.cart);
 check(await page.locator('input.qty').inputValue()==='1','French cart updates the synthetic quantity');
 await page.locator('#main-content').screenshot({path:path.join(dir,'evidence/french-cart.jpg'),type:'jpeg',quality:80});
 await goto(ids.checkout);report.labels.checkout=await text(page.locator('#main-content'));
 check(await page.locator('form.checkout').count()===1,'French checkout form renders with synthetic cart');
 check(/Prénom/i.test(await text(page.locator('label[for="billing_first_name"]'))),'Billing first-name label is French');
 check(/Nom/i.test(await text(page.locator('label[for="billing_last_name"]'))),'Billing surname label is French');
 check(/Commander/i.test(await text(page.locator('#place_order'))),'Checkout submission label is French (never clicked)');
 check(await page.locator('input[name="payment_method"]').count()===0,'No available real payment method');
 check(await page.locator('input[name="woocommerce-process-checkout-nonce"]').count()===1,'Native checkout security nonce remains present');
 for(const width of [390,1440]){await page.setViewportSize({width,height:1000});await goto(ids.checkout);check(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth),width+' French checkout has no overflow');}
 await page.setViewportSize({width:390,height:1000});await goto(ids.checkout);
 await page.locator('#main-content').screenshot({path:path.join(dir,'evidence/french-checkout.jpg'),type:'jpeg',quality:80});
 // Inspect a French form only: do not fill identifying data, click place_order or submit checkout.
 }finally{await browser.close();fs.writeFileSync(path.join(dir,'evidence/french-storefront.json'),JSON.stringify(report,null,2));console.log('AD_FRENCH_STOREFRONT '+JSON.stringify(report));}
 if(report.failures.length)process.exitCode=1;
})().catch(e=>{console.error(e);process.exitCode=1;});
