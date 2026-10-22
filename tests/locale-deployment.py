#!/usr/bin/env python3
"""Run real file/backup/rollback shell with simulated WP and HTTP transports only."""
from pathlib import Path
import hashlib,json,os,shutil,subprocess,tempfile
repo=Path(__file__).resolve().parents[1];payload=Path(os.environ['RUNNER_TEMP'])/'ad-fr-ca-payload'
php=os.environ.get('PHP_BIN') or shutil.which('php');assert php
manifest=json.loads((payload/'manifest.json').read_text());theme_manifest=json.loads((payload/'theme-manifest.json').read_text())
wp_mock='''#!/usr/bin/env python3
from pathlib import Path
import json,os,sys
args=[a for a in sys.argv[1:] if not a.startswith('--')];home=Path(os.environ['HOME']);root=home/'html'
assert '--path='+str(root) in sys.argv
locale=home/'mock-locale';value=locale.read_text()
if args[:2]==['option','get'] and args[2] in ['siteurl','home']:print('https://1302636.us22.myftpupload.com')
elif args[:2]==['db','query']:assert 'information_schema.TABLES' in args[2];print('12\\t4096\\t0\\n')
elif args[:2]==['db','export']:Path(args[2]).write_text('CREATE TABLE fictional (id int);\\n')
elif args[0]=='eval-file' and Path(args[1]).name=='state.php':
 mode=args[2]
 if mode=='audit':print('LOCALE_AUDIT '+json.dumps({'scope':'simulation','locale':value}))
 elif mode=='capture':assert value=='en_US';Path(args[3]).write_text(json.dumps({'locale_before':'en_US','protected':'simulation'}))
 elif mode=='activate':
  assert value=='en_US';locale.write_text('fr_CA')
  if os.environ['CASE']=='activation_failure':sys.exit(1)
 elif mode=='rollback':assert value in ['en_US','fr_CA'];locale.write_text('en_US')
 elif mode=='verify':
  assert json.loads(Path(args[3]).read_text())['protected']=='simulation'
  assert value==('fr_CA' if args[4]=='new' else 'en_US')
  if args[4]=='new' and os.environ['CASE']=='verification_failure':sys.exit(1)
 else:raise AssertionError('Unauthorized locale state operation')
else:raise AssertionError('Unexpected command or commercial mutation')
'''
curl_mock='''#!/usr/bin/env python3
from pathlib import Path
import os,sys,urllib.parse
args=sys.argv[1:];u=urllib.parse.urlparse(next(a for a in args if a.startswith('https://')));assert u.netloc=='1302636.us22.myftpupload.com'
home=Path(os.environ['HOME']);locale=(home/'mock-locale').read_text();text='Prochainement' if locale=='fr_CA' else 'Coming Soon'
if '-after' in u.query and os.environ['CASE']=='coming_failure':text='Shop open'
Path(args[args.index('-o')+1]).write_text('<h1>'+text+'</h1>');print('200',end='')
'''
def inventory(path):return {str(p.relative_to(path)):hashlib.sha256(p.read_bytes()).hexdigest() for p in path.rglob('*') if p.is_file()} if path.exists() else {}
for case in ['success','activation_failure','verification_failure','coming_failure']:
 with tempfile.TemporaryDirectory(prefix='ad-locale-shell-') as tmp:
  base=Path(tmp).resolve();home=base/'home';root=home/'html';theme=root/'wp-content/themes/aliments-distinction';theme.mkdir(parents=True)
  shutil.copytree(repo/'wordpress/aliments-distinction',theme,dirs_exist_ok=True);(root/'wp-load.php').touch();(home/'mock-locale').write_text('en_US')
  lang=root/'wp-content/languages';existing=case in ['success','verification_failure']
  if existing:
   (lang/'plugins').mkdir(parents=True);(lang/'fr_CA.mo').write_text('old custom translation');(lang/'plugins/other-fr_FR.mo').write_text('unchanged other language/plugin')
  old=inventory(lang)
  bundle=base/'bundle';(bundle/'locale').mkdir(parents=True);(bundle/'ops').mkdir();(bundle/'payload').mkdir()
  for name in ['files.php','state.php','wp-functions.php','public-check.php','space.php','update-temp.sh']:shutil.copy(repo/'scripts/locale'/name,bundle/'locale'/name)
  for name in ['p0-files.php','p0-db-size.php','p0-db-size.sql','measure-p0-db.sh']:shutil.copy(repo/'scripts/ops'/name,bundle/'ops'/name)
  shutil.copytree(payload/'languages',bundle/'payload/languages');shutil.copy(payload/'manifest.json',bundle/'manifest.json');shutil.copy(payload/'theme-manifest.json',bundle/'theme-manifest.json')
  bin_dir=base/'bin';bin_dir.mkdir()
  for name,content in [('wp',wp_mock),('curl',curl_mock)]:p=bin_dir/name;p.write_text(content);p.chmod(0o700)
  (bin_dir/'php').symlink_to(Path(php).resolve())
  env={**os.environ,'HOME':str(home),'PATH':str(bin_dir)+':'+os.environ['PATH'],'CASE':case}
  result=subprocess.run(['bash',str(bundle/'locale/update-temp.sh'),'123456'],cwd=root,env=env,text=True,capture_output=True,timeout=120)
  try:
   assert (result.returncode==0)==(case=='success')
   expected={**old,**manifest['files']} if case=='success' else old
   assert inventory(lang)==expected
   assert (home/'mock-locale').read_text()==('fr_CA' if case=='success' else 'en_US')
   if case!='success':assert lang.exists()==existing
   assert inventory(theme)==theme_manifest['old']
   backup=home/'.ad-private-backups/locale-fr-ca-123456';assert inventory(backup/'languages-before')==old;assert inventory(backup/'theme-0.1.2')==theme_manifest['old']
   subprocess.run(['sha256sum','-c','SHA256SUMS'],cwd=backup,check=True,capture_output=True)
   assert ('SUCCESS: fr_CA' if case=='success' else 'ROLLBACK VERIFIED:') in result.stdout
  except Exception:print(result.stdout[-3000:]);print(result.stderr[-1500:]);raise
  print('PASS: language shell simulation '+case+'; locale/languages/theme/pristine backups verified')
print('PASS: 4 locale orchestration simulations; no SSH/hosted operation or SQL restore.')
