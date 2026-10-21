#!/usr/bin/env python3
"""Exact reviewed P1 controls + header only. Refuse all other theme changes."""
from pathlib import Path
import hashlib, json, subprocess, zipfile
repo = Path(__file__).resolve().parents[2]
prefix = 'wordpress/aliments-distinction/'
base = 'be5703eeaa434ada55cb33a961a0b08e11f6a862'
approved = '206972b1ab2228b17b6131c66a9475e7f085f3da'
def read(commit, path):
    return subprocess.check_output(['git', 'show', f'{commit}:{prefix}{path}'], cwd=repo)
paths = subprocess.check_output(['git','ls-tree','-r','--name-only',base,prefix],cwd=repo,text=True).splitlines()
old = {p[len(prefix):]: read(base,p[len(prefix):]) for p in paths}
new = {str(p.relative_to(repo/prefix)): p.read_bytes() for p in (repo/prefix).rglob('*') if p.is_file()}
assert old.keys() == new.keys() and len(old) == 23, 'Theme inventory drift'
changed = sorted(p for p in old if old[p] != new[p])
assert changed == ['assets/css/commerce.css','style.css'], 'Only approved pair allowed'
assert old['style.css'].count(b'Version: 0.1.1\n') == 1
assert new['style.css'] == old['style.css'].replace(b'Version: 0.1.1\n',b'Version: 0.1.2\n',1), 'Header change only'
assert new['assets/css/commerce.css'] == read(approved,'assets/css/commerce.css'), 'Unreviewed controls CSS'
archive = repo/'dist/aliments-distinction-0.1.2.zip'
with zipfile.ZipFile(archive) as z:
    assert z.testzip() is None
    assert set(z.namelist()) == {'aliments-distinction/'+p for p in new}
    for p,data in new.items():
        assert z.read('aliments-distinction/'+p) == data
manifest = {'baseline_commit':base,'approved_controls_commit':approved,'from_version':'0.1.1','to_version':'0.1.2',
            'changed':changed,'old':{p:hashlib.sha256(v).hexdigest() for p,v in sorted(old.items())},
            'new':{p:hashlib.sha256(v).hexdigest() for p,v in sorted(new.items())}}
(repo/'dist/p1-manifest.json').write_text(json.dumps(manifest,indent=2)+'\n')
print('PASS: exact approved P1 pair; 23-file inventory and ZIP bytes verified.')
print('P1_RELEASE '+json.dumps({'zip_sha256':hashlib.sha256(archive.read_bytes()).hexdigest(),'old':{p:manifest['old'][p] for p in changed},'new':{p:manifest['new'][p] for p in changed}}))
