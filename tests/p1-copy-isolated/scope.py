"""Exact approved source scope; no settings or hosted access."""
from pathlib import Path
import hashlib, json, subprocess
repo=Path(__file__).resolve().parents[2]
scope=json.loads((Path(__file__).with_name('scope.json')).read_text())
def changed(*paths):
    return subprocess.check_output(['git','diff','--name-only',scope['baseline'],'HEAD','--',*paths],cwd=repo,text=True).splitlines()
assert set(changed('wordpress/aliments-distinction'))==set(scope['files']), 'Unreviewed theme scope'
for name, hashes in scope['files'].items():
    assert hashlib.sha256((repo/name).read_bytes().replace(b'Version: 0.1.3\n',b'Version: 0.1.2\n',1) if name.endswith('/style.css') else (repo/name).read_bytes()).hexdigest()==hashes['candidate_sha256'], 'Reviewed bytes differ'
assert hashlib.sha256((repo/'docs/p1/approved-copy-newsletter.patch').read_bytes()).hexdigest()==scope['approved_patch_sha256']
subprocess.run(['git','apply','--reverse','--check','docs/p1/approved-copy-newsletter.patch'],cwd=repo,check=True)
assert not changed('index.html','scripts/locale'), 'Reference changed'
allowed_ops={'scripts/ops/'+p for p in ['prepare-p1-013-release.py','p1-013-files.php','p1-013-state.php','update-p1-013-temp.sh','wait-p1-013-qa.py']}
assert set(changed('scripts/ops')).issubset(allowed_ops), 'Unreviewed deployment controller change'
assert set(changed('.github/workflows')).issubset({'.github/workflows/qa-wordpress-isolated.yml','.github/workflows/qa-p1-approved-copy.yml','.github/workflows/update-godaddy-temp-p1-013.yml'}), 'Protected workflow changed'
qa=(repo/'.github/workflows/qa-wordpress-isolated.yml').read_text()
assert '  workflow_dispatch:' in qa and '  workflow_call:' in qa
for name in scope['files']:
    assert "      - '!"+name+"'" in qa
target=(repo/'.github/workflows/qa-p1-approved-copy.yml').read_text()
for forbidden in ['environment:','secrets.','sshpass','1302636.']:
    assert forbidden not in target
print('PASS: exactly four approved theme files; original design/assets/recipes/promises and only dedicated 0.1.3 preparation allowed; full reusable QA retained.')

if b'Version: 0.1.3\n' in (repo/'wordpress/aliments-distinction/style.css').read_bytes():
    subprocess.run(['python3','scripts/package-theme.py'],cwd=repo,check=True)
    subprocess.run(['python3','scripts/ops/prepare-p1-013-release.py'],cwd=repo,check=True)
