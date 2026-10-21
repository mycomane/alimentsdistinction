/* Reproducible sRGB contrast calculations; proposal only, never imports into theme. */
const fs=require('node:fs'),path=require('node:path');
const luminance=hex=>{
 const c=hex.replace('#','').match(/../g).map(v=>parseInt(v,16)/255).map(v=>v<=0.04045?v/12.92:((v+0.055)/1.055)**2.4);
 return .2126*c[0]+.7152*c[1]+.0722*c[2];
};
const ratio=(fg,bg)=>{const a=luminance(fg),b=luminance(bg);return (Math.max(a,b)+.05)/(Math.min(a,b)+.05);};
const current=[['Rose sur crème','#C4857A','#FAF7F2'],['Blanc sur rose','#FFFFFF','#C4857A'],['Gris sur crème','#8C8884','#FAF7F2'],['Gris clair sur crème 2','#B0ADA8','#F4EDE3'],['Infolettre indisponible','#4A4744','#2C2A27'],['Infolettre lien configuré','#2C2A27','#2C2A27'],['Sauge sur fond pâle','#8BA888','#EBF2EA'],['Terre sur blanc','#B8956A','#FFFFFF']];
const proposal=[];
for(const bg of ['#FAF7F2','#F4EDE3','#F5E8E6','#EBF2EA','#FFFFFF']){
 proposal.push(['Rose encre sur '+bg,'#94544B',bg],['Gris texte sur '+bg,'#6E6964',bg]);
}
proposal.push(['Bouton rose encre','#FFFFFF','#94544B'],['Infolettre claire','#E8E2DA','#2C2A27'],['Texte terre','#806342','#FFFFFF'],['Texte sauge','#52694F','#EBF2EA']);
const samples=rows=>rows.map(([label,foreground,background])=>{const raw=ratio(foreground,background);return {label,foreground,background,ratio:+raw.toFixed(4),normalAA:raw>=4.5,largeAA:raw>=3};});
const report={scope:'proposal-only-no-theme-change',sources:['https://www.w3.org/WAI/WCAG22/Understanding/contrast-minimum.html','https://www.w3.org/WAI/WCAG22/Understanding/focus-visible.html'],current:samples(current),proposal:samples(proposal),limits:'Opaque flat-color pairs only. Photographic backgrounds, opacity, actual font weight, focus and hover need context checks. No WCAG certification claimed.'};
if(report.proposal.some(p=>!p.normalAA))throw new Error('A proposed flat-color pair fails 4.5:1');
const dir=path.join(process.env.RUNNER_TEMP,'ad-isolated-qa/evidence');fs.mkdirSync(dir,{recursive:true});fs.writeFileSync(path.join(dir,'contrast-report.json'),JSON.stringify(report,null,2));
console.log('AD_CONTRAST_REPORT '+JSON.stringify(report));
if(process.env.GITHUB_STEP_SUMMARY)fs.appendFileSync(process.env.GITHUB_STEP_SUMMARY,'## Contrast proposal (not applied)\n```json\n'+JSON.stringify(report,null,2)+'\n```\n');
