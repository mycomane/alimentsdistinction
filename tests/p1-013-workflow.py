"""Static approval boundary and legacy immutability checks; no remote transport."""
from pathlib import Path
import subprocess
r=Path(__file__).resolve().parents[1]
w=(r/'.github/workflows/update-godaddy-temp-p1-013.yml').read_text()
assert w.count('environment: godaddy-temp')==1
assert 'needs: [prepare, isolated-qa]' in w
assert 'paths: [\'.github/workflows/update-godaddy-temp-p1-013.yml\']' in w
assert 'workflow_dispatch:' not in w and 'pull_request' not in w
assert 'cancel-in-progress: false' in w and 'group: godaddy-temp-site-mutation' in w
assert "test \"$GITHUB_RUN_ATTEMPT\" = 1" in w
assert '/approvals' in w and "== 'mycomane'" in w and "== 'User'" in w
assert w.index('/approvals') < w.index('ssh-keyscan') < w.index('sshpass -e ssh')
assert 'test "$(git rev-parse HEAD)" = "$GITHUB_SHA"' in w
for path in ['.github/workflows/update-godaddy-temp-p1.yml','.github/workflows/configure-locale-fr-ca.yml','scripts/ops/p1-files.php','scripts/ops/update-p1-temp.sh','scripts/ops/measure-p0-db.sh','scripts/ops/p0-db-size.php','scripts/ops/p0-db-size.sql']:
    assert (r/path).read_bytes()==subprocess.check_output(['git','show','4544ead988c2aeb3ded1f33520c8ecb8d2791d00:'+path],cwd=r), 'Legacy protection drift '+path
s=(r/'scripts/ops/update-p1-013-temp.sh').read_text()
for required in ['languages.tar.gz','protected-state.json','SHA256SUMS','measure-p0-db.sh','restore-rehearsal','rehearsal-apply.json','rehearsal-rollback.json','check_css old 0.1.2','check_css new 0.1.3','check_public after','flock -n 9','test ! -e "$backup"']:
    assert required in s, required
for forbidden in ['db import','theme activate','user create','option update','cache flush']:
    assert forbidden not in s
print('PASS: exact-run human approval before SSH; shared lock, single push trigger, legacy controls unchanged and backup/rollback checks retained.')

# Execute the exact inline approval predicate against isolated API response fixtures.
import json, os, tempfile, textwrap
start=w.index("          import json, os",w.index('/approvals'))
end=w.index("          PY",start)
gate=textwrap.dedent(w[start:end])
for name,rows,accepted in [
    ('missing',[],False),
    ('wrong-reviewer',[{'state':'approved','user':{'login':'other','type':'User'},'environments':[{'name':'godaddy-temp'}]}],False),
    ('wrong-environment',[{'state':'approved','user':{'login':'mycomane','type':'User'},'environments':[{'name':'other'}]}],False),
    ('human-exact-run',[{'state':'approved','user':{'login':'mycomane','type':'User'},'environments':[{'name':'godaddy-temp'}]}],True),
]:
    with tempfile.TemporaryDirectory() as tmp:
        Path(tmp,'p1-approvals.json').write_text(json.dumps(rows))
        result=subprocess.run(['python3','-c',gate],env={**os.environ,'RUNNER_TEMP':tmp},capture_output=True)
        assert (result.returncode==0)==accepted, name
print('PASS: 4 isolated approval-gate response fixtures, including refusal cases.')
