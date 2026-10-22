#!/usr/bin/env python3
"""Confirm theme and reused guards unchanged; package only reviewed language data."""
from pathlib import Path
import hashlib,json,os,subprocess,tarfile
repo=Path(__file__).resolve().parents[2];out=Path(os.environ['RUNNER_TEMP'])/'ad-fr-ca-payload'
base='ad3006ca7c618b9d71617232bec67fe7c9cd322a';prefix='wordpress/aliments-distinction/'
paths=subprocess.check_output(['git','ls-tree','-r','--name-only',base,prefix],cwd=repo,text=True).splitlines()
hashes={}
for p in paths:
 old=subprocess.check_output(['git','show',base+':'+p],cwd=repo);assert (repo/p).read_bytes()==old,'Theme drift'
 hashes[p[len(prefix):]]=hashlib.sha256(old).hexdigest()
assert len(hashes)==23
for p in ['scripts/ops/p0-files.php','scripts/ops/p0-db-size.php','scripts/ops/p0-db-size.sql','scripts/ops/measure-p0-db.sh']:
 assert (repo/p).read_bytes()==subprocess.check_output(['git','show',base+':'+p],cwd=repo),'Reused guard changed'
(out/'theme-manifest.json').write_text(json.dumps({'old':dict(sorted(hashes.items())),'new':dict(sorted(hashes.items())),'version':'0.1.2','source_commit':base},indent=2)+'\n')
m=json.loads((out/'manifest.json').read_text());assert m['locale']=='fr_CA' and len(m['files'])==311
for p,h in m['files'].items():assert hashlib.sha256((out/'languages'/p).read_bytes()).hexdigest()==h
with tarfile.open(out/'review-package.tar.gz','w:gz') as tar:
 for name in ['languages','manifest.json','theme-manifest.json']:tar.add(out/name,arcname=name)
print('PASS: theme 0.1.2 (23 files) and reused P0 guards unchanged; exact 311-file pinned locale release verified.')
