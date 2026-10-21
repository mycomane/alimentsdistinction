/* Screenshots only after passing targeted QA. No tests or hosted access. */
const fs=require('node:fs'),path=require('node:path');
const {chromium}=require(path.join(process.env.RUNNER_TEMP,'ad-qa-tools/node_modules/playwright'));
const dir=path.join(process.env.RUNNER_TEMP,'ad-isolated-qa'),out=path.join(dir,'evidence/p1');fs.mkdirSync(out,{recursive:true});
const ids=JSON.parse(fs.readFileSync(path.join(dir,'fixtures.json'))),base='http://127.0.0.1:8787',shots={};
(async()=>{const browser=await chromium.launch();try{const page=await browser.newPage({viewport:{width:390,height:1000},reducedMotion:'reduce'});
 for(const width of [390,1440])for(const v of ['base','a','b']){
  await page.setViewportSize({width,height:1000});await page.mouse.move(0,0);await page.goto(base,{waitUntil:'networkidle'});
  if(v!=='base'){await page.evaluate(v=>document.documentElement.dataset.p1Variant=v,v);await page.addStyleTag({content:fs.readFileSync('docs/p1/common.css','utf8')+fs.readFileSync('docs/p1/variant-'+v+'.css','utf8')});}
  await page.evaluate(()=>document.fonts.ready);await page.waitForTimeout(450);
  for(const [name,selector]of [['hero','.hero'],['footer','.newsletter']]){shots[`${v}-${name}-${width}`]=await page.locator(selector).screenshot({type:'jpeg',quality:80,animations:'disabled'});}
  if(width===390){await page.mouse.move(0,0);await page.goto(base+'/?p='+ids.variable,{waitUntil:'networkidle'});if(v!=='base'){await page.evaluate(v=>document.documentElement.dataset.p1Variant=v,v);await page.addStyleTag({content:fs.readFileSync('docs/p1/common.css','utf8')+fs.readFileSync('docs/p1/variant-'+v+'.css','utf8')});}shots[`${v}-disabled-${width}`]=await page.locator('.summary').screenshot({type:'jpeg',quality:80,animations:'disabled'});}
 }
 const sheet=await browser.newPage({viewport:{width:1200,height:100}});
 for(const [board,width,type]of [['desktop',1440,'hero'],['mobile',390,'hero'],['newsletter',390,'footer'],['woo-states',390,'disabled']]){
  const names={base:'Actuel · 0.1.1',a:'A · Évolution discrète',b:'B · Lecture renforcée'};
  const cards=['base','a','b'].map(v=>`<article><h2>${names[v]}</h2><p>${v==='base'?'Référence':'Proposition à approuver'} · ${width}px<br>${v==='base'?'Contrastes insuffisants':v==='a'?'Navigation ≥ 5,41:1 · bouton rose encre':'Barre opaque · bouton charbon'}<br>${v==='base'?'': 'P0 : 0px · styles de repos'}</p><img src="data:image/jpeg;base64,${shots[`${v}-${type}-${width}`].toString('base64')}"></article>`).join('');
  await sheet.setContent(`<html lang="fr"><style>*{box-sizing:border-box}body{margin:0;background:#f4ede3;color:#2c2a27;font:15px Arial}header{padding:18px 22px;background:#faf7f2}h1{margin:0;font:26px Georgia}section{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;padding:12px}article{background:white;min-width:0}h2{margin:15px 14px 7px;font-size:19px}p{margin:0 14px 14px;font-size:12px;line-height:1.5}img{width:100%;display:block}</style><header><h1>Aliments Distinction · P1 · ${board}</h1><p style="margin:8px 0 0">Captures isolées · Deux propositions · Aucun déploiement · Tests ciblés : 39690003471</p></header><section>${cards}</section></html>`);
  const bytes=await sheet.screenshot({fullPage:true,type:'jpeg',quality:82});fs.writeFileSync(path.join(out,'choice-'+board+'.jpg'),bytes);console.log('P1_IMAGE '+JSON.stringify({name:'choice-'+board+'.jpg',base64:bytes.toString('base64')}));
 }
 console.log('PASS: isolated review boards refreshed in neutral pointer state; CSS unchanged.');
 }finally{await browser.close();}})().catch(e=>{console.error(e.message);process.exitCode=1;});
