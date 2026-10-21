/* Actual authorized temporary site, disposable subscriber, read-only frontend checks. No screenshots/HTML/logged-in text exported. */
const fs=require('node:fs');
const path=require('node:path');
const crypto=require('node:crypto');
const {compare}=require('./hosted-p0-geometry.cjs');
const {chromium}=require(path.join(process.env.RUNNER_TEMP,'ad-review-tools/node_modules/playwright'));
const base='https://1302636.us22.myftpupload.com';
const report={scope:'godaddy-temporary-only',stage:'browser_launch',checks:[],layout:[],pages:[],failures:[],pageErrorCount:0};
const check=(passed,label)=>{report.checks.push({label,passed:!!passed});if(!passed)report.failures.push(label);};
const comingSoon=async page=>/coming\s+soon/i.test(await page.locator('body').innerText());
const geometry=async page=>page.evaluate(()=>{
  const result={};
  for(const selector of ['.hero','.hero__left','.hero__right','.hero__title','.hero__ctas','.hero__trust']){
    const el=document.querySelector(selector);if(!el){result[selector]=null;continue;}
    const r=el.getBoundingClientRect(),s=getComputedStyle(el);
    const box=r=>({x:+r.x.toFixed(2),y:+r.y.toFixed(2),width:+r.width.toFixed(2),height:+r.height.toFixed(2)});
    result[selector]={...box(r),fontSize:s.fontSize,fontFamily:s.fontFamily,color:s.color};
    if(selector==='.hero__trust'){
      result[selector].flexWrap=s.flexWrap;
      result[selector].items=[...el.children].map(x=>({...box(x.getBoundingClientRect()),clipped:x.scrollWidth>x.clientWidth+1||x.scrollHeight>x.clientHeight+1}));
    }
  }return result;
});
(async()=>{
  const browser=await chromium.launch({headless:true});
  try{
    const publicContext=await browser.newContext({ignoreHTTPSErrors:false});
    const anon=await publicContext.newPage();
    report.stage='anonymous_navigation';
    const pre=await anon.goto(base+'/?ad_review='+process.env.GITHUB_RUN_ID+'-public',{waitUntil:'domcontentloaded',timeout:45000});
    report.stage='anonymous_coming_soon_check';
    check(pre.status()===200 && new URL(anon.url()).origin===base && await comingSoon(anon),'Anonymous homepage remains Coming Soon');
    if(report.failures.length)throw new Error('precondition');
    if(process.argv.includes('--public-only'))return;
    const auth=JSON.parse(fs.readFileSync(path.join(process.env.RUNNER_TEMP,'ad-review-session.json'),'utf8'));
    if(auth.expires*1000-Date.now()<600000)throw new Error('session expired or too short');
    for(const url of Object.values(auth.pages))if(new URL(url).origin!==base)throw new Error('origin refused');
    const context=await browser.newContext({ignoreHTTPSErrors:false});
    // Strict host-only cookie; native WP session expires server-side after 30 minutes.
    await context.addCookies([{...auth.cookie,domain:new URL(base).hostname,path:'/',expires:auth.expires,httpOnly:true,secure:true,sameSite:'Lax'}]);
    await context.route('**/*',route=>{
      const u=new URL(route.request().url());
      const allowed=[new URL(base).hostname,'fonts.googleapis.com','fonts.gstatic.com','images.unsplash.com','secure.gravatar.com'];
      if(!['GET','HEAD'].includes(route.request().method()) || u.protocol!=='https:' || !allowed.includes(u.hostname))return route.abort();
      if(u.hostname===new URL(base).hostname && (/\/(wp-admin|wp-login\.php)(\/|$)/.test(u.pathname) || u.searchParams.has('add-to-cart') || u.searchParams.has('remove_item') || u.searchParams.has('wc-ajax')))return route.abort();
      return route.continue();
    });
    const page=await context.newPage();
    page.on('pageerror',()=>{report.pageErrorCount++;});
    page.on('dialog',async dialog=>{report.failures.push('Unexpected browser dialog');await dialog.dismiss();});
    report.stage='subscriber_navigation';
    await page.goto(base+'/?ad_review='+process.env.GITHUB_RUN_ID+'-private',{waitUntil:'networkidle',timeout:45000});
    if(await comingSoon(page)){
      report.outcome='subscriber_blocked_by_coming_soon';
      check(false,'Subscriber could not access theme; no role escalation');process.exitCode=1;
      console.log('RESULT: subscriber blocked by Coming Soon; no role escalation or visibility change.');
      return;
    }
    check(await page.locator('.hero__title').count()===1,'Authenticated homepage contains design hero');
    if(report.failures.length)throw new Error('unexpected private response');
    report.stage='deployed_css';
    const cssURL=await page.locator('link#ad-theme-css').getAttribute('href');
    const parsedCSS=new URL(cssURL);
    check(parsedCSS.origin===base&&parsedCSS.pathname==='/wp-content/themes/aliments-distinction/style.css'&&parsedCSS.searchParams.get('ver')==='0.1.1','Hosted HTML requests theme CSS version 0.1.1');
    if(report.failures.length)throw new Error('unexpected stylesheet');
    const css=await context.request.get(cssURL,{maxRedirects:0});
    const digest=crypto.createHash('sha256').update(await css.body()).digest('hex');
    check(css.status()===200&&digest==='5a4c3a5a8ae6fccd66437151555b66d05c1f5c18dc56cfff3de3f66a92bde780','Hosted browser session receives exact corrected CSS');
    report.css={version:'0.1.1',sha256:digest,status:css.status()};
    const refContext=await browser.newContext();
    const reference=await refContext.newPage();
    for(const width of [320,390,768,1024,1440]){
      report.stage='responsive_layout';
      await page.setViewportSize({width,height:1000});
      await page.goto(base+'/?ad_review='+process.env.GITHUB_RUN_ID+'-'+width,{waitUntil:'networkidle',timeout:45000});
      await page.evaluate(()=>document.fonts.ready);
      check(!await comingSoon(page),'Private theme visible '+width+'px');
      const metrics=await page.evaluate(()=>({scrollWidth:document.documentElement.scrollWidth,viewport:innerWidth,brokenLocal:[...document.images].filter(i=>i.src.startsWith(location.origin)&&i.complete&&i.naturalWidth===0).length,brokenExternal:[...document.images].filter(i=>!i.src.startsWith(location.origin)&&i.complete&&i.naturalWidth===0).length}));
      check(metrics.scrollWidth<=width+1,'No hosted horizontal overflow '+width+'px');
      check(metrics.brokenLocal===0,'Hosted local images loaded '+width+'px');
      await reference.setViewportSize({width,height:1000});
      await reference.goto('http://127.0.0.1:8788/index.html',{waitUntil:'networkidle'});
      await reference.evaluate(()=>document.fonts.ready);
      const hosted=await geometry(page),ref=await geometry(reference);
      for(const c of compare(hosted,ref,width))check(c.passed,c.label);
      const delta=+(hosted['.hero'].height-ref['.hero'].height).toFixed(2);
      report.layout.push({width,...metrics,hosted,reference:ref,heroHeightDelta:delta});
      const anchors=await page.evaluate(()=>[...document.querySelectorAll('a[href]')].filter(a=>{
        const u=new URL(a.href,location.href);if(u.origin!==location.origin||u.pathname!==location.pathname||!u.hash||u.hash==='#')return false;
        try{return !document.getElementById(decodeURIComponent(u.hash.slice(1)));}catch{return true;}
      }).length);
      check(anchors===0,'Hosted homepage anchor targets exist '+width+'px');
      if(width<=820){
        const toggle=page.getByRole('button',{name:'Menu',exact:true});await toggle.focus();await toggle.press('Enter');
        check(await toggle.getAttribute('aria-expanded')==='true','Hosted mobile menu opens '+width+'px');
        await toggle.press('Escape');check(await toggle.getAttribute('aria-expanded')==='false','Hosted mobile menu closes '+width+'px');
        check(await toggle.evaluate(el=>document.activeElement===el),'Hosted mobile menu restores focus '+width+'px');
      }
    }
    for(const [key,url] of Object.entries(auth.pages)){
      report.stage='technical_pages';
      await page.setViewportSize({width:390,height:1000});
      const response=await page.goto(url,{waitUntil:'networkidle',timeout:45000});
      const stillPrivate=new URL(page.url()).origin===base&&!await comingSoon(page);
      const overflow=await page.evaluate(()=>document.documentElement.scrollWidth>innerWidth+1);
      const finalURL=new URL(page.url());
      const finalPageKey=Object.entries(auth.pages).find(([,u])=>{const known=new URL(u);return known.pathname===finalURL.pathname&&known.searchParams.get('page_id')===finalURL.searchParams.get('page_id');})?.[0]||'other_same_origin_path';
      report.pages.push({key,status:response.status(),sameOrigin:finalURL.origin===base,finalPageKey,themeVisible:stillPrivate,overflow,checkoutFormCount:await page.locator('form.checkout').count()});
      check(response.status()===200&&stillPrivate,'Private technical page reachable: '+key);
      check(!overflow,'No hosted technical page overflow: '+key);
    }
    report.stage='anonymous_final_check';
    const post=await anon.goto(base+'/?ad_review='+process.env.GITHUB_RUN_ID+'-public-after',{waitUntil:'domcontentloaded',timeout:45000});
    check(post.status()===200&&await comingSoon(anon),'Anonymous Coming Soon preserved after private checks');
    check(report.pageErrorCount===0,'No JavaScript pageerror during hosted checks');
    report.outcome=report.failures.length?'failed':'automated_frontend_checks_passed';
  }finally{
    await browser.close();
    console.log('AD_HOSTED_P0_REPORT '+JSON.stringify(report));
    if(process.env.GITHUB_STEP_SUMMARY)fs.appendFileSync(process.env.GITHUB_STEP_SUMMARY,'## Hosted subscriber review (no authenticated images exported)\n```json\n'+JSON.stringify(report,null,2)+'\n```\n');
  }
  if(report.failures.length)process.exitCode=1;
})().catch(error=>{
  // Fixed categories only: never emit a URL, cookie, raw exception or response body.
  let category=error?.name==='TimeoutError'?'timeout':'unexpected_error';
  for(const code of ['ERR_NAME_NOT_RESOLVED','ERR_CONNECTION_TIMED_OUT','ERR_CONNECTION_REFUSED','ERR_TUNNEL_CONNECTION_FAILED','ERR_CERT_AUTHORITY_INVALID','ERR_ABORTED']){
    if(String(error?.message).includes(code)){category=code;break;}
  }
  console.error('ERROR: hosted review incomplete; stage='+report.stage+'; category='+category+'. Cleanup must run if provisioned.');
  if(process.env.GITHUB_STEP_SUMMARY)fs.appendFileSync(process.env.GITHUB_STEP_SUMMARY,'\nIncomplete review: stage='+report.stage+', category='+category+'.\n');
  process.exitCode=1;
});
