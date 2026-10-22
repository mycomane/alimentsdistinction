#!/usr/bin/env python3
"""Pinned official fr_CA payload; no remote WordPress credentials or operations."""
from pathlib import Path
import argparse, hashlib, io, json, os, re, shutil, stat, urllib.request, zipfile
repo=Path(__file__).resolve().parents[2]
parser=argparse.ArgumentParser();parser.add_argument('--isolated',action='store_true');args=parser.parse_args()
runner=Path(os.environ['RUNNER_TEMP']).resolve();out=runner/'ad-fr-ca-payload'
if out.exists(): raise SystemExit('Fresh payload directory required')
out.mkdir();languages=out/'languages';languages.mkdir()
config=json.loads((repo/'docs/locale/fr-ca-packages.json').read_text());assert config['locale']=='fr_CA'
files={}
for package in config['packages']:
 kind=package['kind'];version=package['version'];url=package['url']
 assert (kind,version,url) in [('core','7.1.3','https://downloads.wordpress.org/translation/core/7.1.3/fr_CA.zip'),('woocommerce','11.2.1','https://downloads.wordpress.org/translation/plugin/woocommerce/11.2.1/fr_CA.zip')]
 with urllib.request.urlopen(url,timeout=90) as response:data=response.read(16*1024*1024+1)
 assert len(data)==package['bytes'] and hashlib.sha256(data).hexdigest()==package['sha256'],'Upstream package drift; new review required'
 with zipfile.ZipFile(io.BytesIO(data)) as z:
  assert sum(i.file_size for i in z.infolist())<64*1024*1024
  members=[i for i in z.infolist() if not i.is_dir()];assert len(members)==package['files']
  for info in members:
   name=info.filename
   pattern=r'(?:admin-|admin-network-|continents-cities-)?fr_CA(?:-[a-f0-9]{32})?\.(?:mo|po|l10n\.php|json)' if kind=='core' else r'woocommerce-fr_CA(?:-[a-f0-9]{32})?\.(?:mo|po|l10n\.php|json)'
   assert re.fullmatch(pattern,name) and stat.S_IFMT(info.external_attr>>16)!=stat.S_IFLNK,'Unexpected package member'
   rel=name if kind=='core' else 'plugins/'+name
   assert rel not in files,'Duplicate translation member'
   content=z.read(info);dest=languages/rel;dest.parent.mkdir(exist_ok=True);dest.write_bytes(content);dest.chmod(0o644)
   files[rel]=hashlib.sha256(content).hexdigest()
manifest={'locale':'fr_CA','wordpress':'7.1.3','woocommerce':'11.2.1','packages':config['packages'],'files':dict(sorted(files.items()))}
(out/'manifest.json').write_text(json.dumps(manifest,indent=2)+'\n')
if args.isolated:
 wp=runner/'ad-isolated-qa/wordpress'
 assert wp.is_dir() and "define('WP_ENVIRONMENT_TYPE','local')" in (wp/'wp-config.php').read_text()
 shutil.copytree(languages,wp/'wp-content/languages',dirs_exist_ok=True)
print('PASS: pinned official fr_CA payload, '+str(len(files))+' files verified; isolated='+str(args.isolated))
