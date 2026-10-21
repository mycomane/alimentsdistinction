#!/usr/bin/env python3
"""Fail closed: allow only the approved CSS deletion and 0.1.1 header."""
from pathlib import Path
import hashlib, json, subprocess, zipfile

repo = Path(__file__).resolve().parents[2]
base = 'cf17c10ff102f10806a4ca99d6e35e94af769556'
prefix = 'wordpress/aliments-distinction/'
paths = subprocess.check_output(['git', 'ls-tree', '-r', '--name-only', base, prefix], cwd=repo, text=True).splitlines()
old = {p[len(prefix):]: subprocess.check_output(['git', 'show', f'{base}:{p}'], cwd=repo) for p in paths}
new = {str(p.relative_to(repo / prefix)): p.read_bytes() for p in (repo / prefix).rglob('*') if p.is_file()}
assert old.keys() == new.keys(), 'Unexpected theme file inventory'
assert [p for p in old if old[p] != new[p]] == ['style.css'], 'Only style.css may change'
line = b'  .hero__trust { flex-wrap: wrap; }\n'
assert old['style.css'].count(line) == 1
expected = old['style.css'].replace(line, b'').replace(b'Version: 0.1.0\n', b'Version: 0.1.1\n', 1)
assert expected == new['style.css'], 'Unapproved CSS/version difference'
archive = repo / 'dist/aliments-distinction-0.1.1.zip'
with zipfile.ZipFile(archive) as z:
    assert z.testzip() is None
    assert set(z.namelist()) == {'aliments-distinction/' + p for p in new}
    for p, data in new.items():
        assert z.read('aliments-distinction/' + p) == data
manifest = {'baseline_commit': base, 'from_version': '0.1.0', 'to_version': '0.1.1',
            'old': {p: hashlib.sha256(v).hexdigest() for p, v in sorted(old.items())},
            'new': {p: hashlib.sha256(v).hexdigest() for p, v in sorted(new.items())}}
(repo / 'dist/p0-manifest.json').write_text(json.dumps(manifest, indent=2) + '\n')
print(f'PASS: {len(new)} files verified; only approved P0 deletion and version header differ; ZIP exact.')
print('Release ZIP SHA256:', hashlib.sha256(archive.read_bytes()).hexdigest())
print('Old CSS SHA256:', manifest['old']['style.css'])
print('New CSS SHA256:', manifest['new']['style.css'])
