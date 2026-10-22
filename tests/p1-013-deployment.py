#!/usr/bin/env python3
"""Exercise the real orchestration locally with fake WP/HTTP transports, never SSH."""
from pathlib import Path
import hashlib, json, os, shutil, subprocess, tempfile
repo=Path(__file__).resolve().parents[1]
php=os.environ.get('PHP_BIN') or shutil.which('php')
assert php, 'PHP required'
manifest=json.loads((repo/'dist/p1-013-manifest.json').read_text())
wp_mock='''#!/usr/bin/env python3
import os,sys,re,json
from pathlib import Path
args=[a for a in sys.argv[1:] if not a.startswith('--')]
home=Path(os.environ['HOME']);root=home/'html';theme=root/'wp-content/themes/aliments-distinction'
assert '--path='+str(root) in sys.argv
with (home/'wp-calls').open('a') as f:f.write(' '.join(args[:3])+'\\n')
if args[:2]==['option','get'] and args[2] in ['siteurl','home']:print('https://1302636.us22.myftpupload.com')
elif args[:2]==['db','query']:
 assert 'information_schema.TABLES' in args[2];print('12\\t4096\\t0\\n')
elif args[:2]==['db','export']:Path(args[2]).write_text('CREATE TABLE fictional (id int);\\n')
elif args[0]=='eval-file' and Path(args[1]).name=='p1-013-state.php':
 mode=args[2]
 if mode=='locale':print('P1_LOCALE simulation only')
 elif mode=='capture':
  assert re.search('Version: '+re.escape(args[4]),(theme/'style.css').read_text());Path(args[3]).write_text(json.dumps({'mock':'protected state','languages':{'fr_CA.mo':__import__('hashlib').sha256(b'fictional translation').hexdigest()}}))
 elif mode=='cache':
  if args[4]=='0.1.3' and os.environ['CASE']=='cache_failure' and not (home/'injected').exists():
   (home/'injected').touch();sys.exit(1)
 elif mode=='verify':
  assert json.loads(Path(args[3]).read_text())['mock']=='protected state'
  assert re.search('Version: '+re.escape(args[4]),(theme/'style.css').read_text())
 else:raise AssertionError('Unauthorized state mode')
else:raise AssertionError('Unexpected WP mutation/command')
'''
curl_mock='''#!/usr/bin/env python3
import os,sys,urllib.parse
from pathlib import Path
args=sys.argv[1:];out=Path(args[args.index('-o')+1]);url=next(a for a in args if a.startswith('https://'))
u=urllib.parse.urlparse(url);assert u.netloc=='1302636.us22.myftpupload.com'
home=Path(os.environ['HOME']);root=home/'html';status=200
if u.path.startswith('/wp-content/themes/aliments-distinction/'):
 data=(root/u.path.lstrip('/')).read_bytes()
 if 'ver=0.1.3' in u.query and os.environ['CASE']=='http_failure' and not (home/'injected').exists():
  (home/'injected').touch();status=500
else:
 data=b'<h1>Coming Soon</h1>'
 if '-after' in u.query and os.environ['CASE']=='coming_failure' and not (home/'injected').exists():
  (home/'injected').touch();data=b'<h1>Unexpected public content</h1>'
out.write_bytes(data);print(status,end='')
'''
for case in ['success','http_failure','cache_failure','coming_failure']:
 with tempfile.TemporaryDirectory(prefix='ad-p1-orchestration-') as tmp:
  base=Path(tmp).resolve();home=base/'home';root=home/'html';theme=root/'wp-content/themes/aliments-distinction';theme.mkdir(parents=True)
  (root/'wp-load.php').touch();(root/'wp-content/languages').mkdir(parents=True);(root/'wp-content/languages/fr_CA.mo').write_text('fictional translation');ops=base/'ops';ops.mkdir();bin_dir=base/'bin';bin_dir.mkdir()
  for path in manifest['old']:
   p=theme/path;p.parent.mkdir(exist_ok=True,parents=True)
   p.write_bytes(subprocess.check_output(['git','show',manifest['baseline_commit']+':wordpress/aliments-distinction/'+path],cwd=repo))
  for name in ['p0-files.php','p1-013-files.php','p1-013-state.php','update-p1-013-temp.sh','p0-db-size.php','p0-db-size.sql','measure-p0-db.sh']:
   shutil.copy(repo/'scripts/ops'/name,ops/name)
  shutil.copy(repo/'dist/p1-013-manifest.json',ops/'p1-013-manifest.json')
  for path in manifest['changed']:
   p=ops/'payload'/path;p.parent.mkdir(exist_ok=True,parents=True);shutil.copy(repo/'wordpress/aliments-distinction'/path,p)
  for name,content in [('wp',wp_mock),('curl',curl_mock)]:
   p=bin_dir/name;p.write_text(content);p.chmod(0o700)
  (bin_dir/'php').symlink_to(Path(php).resolve())
  env={**os.environ,'HOME':str(home),'PATH':str(bin_dir)+':'+os.environ['PATH'],'CASE':case}
  result=subprocess.run(['bash',str(ops/'update-p1-013-temp.sh'),'123456'],cwd=root,env=env,text=True,capture_output=True,timeout=90)
  try:
   assert (result.returncode==0)==(case=='success')
   phase='new' if case=='success' else 'old'
   assert {str(p.relative_to(theme)):hashlib.sha256(p.read_bytes()).hexdigest() for p in theme.rglob('*') if p.is_file()}==manifest[phase]
   backup=home/'.ad-private-backups/p1-013-123456'
   assert {str(p.relative_to(backup/'theme-0.1.2')):hashlib.sha256(p.read_bytes()).hexdigest() for p in (backup/'theme-0.1.2').rglob('*') if p.is_file()}==manifest['old']
   subprocess.run(['sha256sum','-c','SHA256SUMS'],cwd=backup,check=True,capture_output=True)
   assert ('SUCCESS: P1' if case=='success' else 'ROLLBACK VERIFIED:') in result.stdout
  except Exception:
   print(result.stdout[-2500:]);print(result.stderr[-3500:]);raise
  print('PASS: P1 orchestration simulation '+case+'; exact final inventory and pristine backup verified')
print('PASS: 4 real-shell simulated transport scenarios; not a hosted deployment or SQL restoration test.')
