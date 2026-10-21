/* Pure geometry comparison: no browser, network or authenticated text. */
function compare(hosted,reference,width){
 const checks=[];
 for(const selector of ['.hero','.hero__left','.hero__right','.hero__title','.hero__ctas','.hero__trust']){
  const a=hosted[selector],b=reference[selector];
  checks.push({label:`Hosted reference geometry ${selector} ${width}px`,passed:!!a&&!!b&&['x','y','width','height'].every(k=>Math.abs(a[k]-b[k])<=1)});
 }
 const trust=hosted['.hero__trust'];
 checks.push({label:`Hosted trust nowrap ${width}px`,passed:trust?.flexWrap==='nowrap'});
 const items=trust?.items||[];
 checks.push({label:`Hosted trust three columns ${width}px`,passed:items.length===3&&items.every(x=>Math.abs(x.y-items[0].y)<=1&&!x.clipped)});
 checks.push({label:`Hosted trust no overlap ${width}px`,passed:items.length===3&&items.slice(1).every((x,i)=>x.x>=items[i].x+items[i].width-1)});
 return checks;
}
module.exports={compare};
