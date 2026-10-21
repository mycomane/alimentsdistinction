#!/usr/bin/env python3
"""Real MySQL aggregate + deployed shell/parser, disposable runner fixture only."""
from pathlib import Path
import os, subprocess, tempfile, shutil, json, sys

repo = Path(__file__).resolve().parents[1]
assert os.environ.get('P0_TEST_MYSQL') == '1', 'Explicit isolated test flag required'
mysql = ['mysql', '--protocol=TCP', '--host=127.0.0.1', '--port=33306', '--user=root', '--connect-timeout=3']

def query(sql, database=None):
    result = subprocess.run(mysql + ['--batch', '--raw', '--skip-column-names'] + ([database] if database else []) + ['--execute', sql], capture_output=True, text=True)
    assert result.returncode == 0 and not result.stderr, 'Isolated MySQL query failed (raw details withheld)'
    return result.stdout

query('CREATE DATABASE ad_p0_measure; CREATE DATABASE ad_p0_empty;')
query("CREATE TABLE first_fixture (id INT PRIMARY KEY, body TEXT) ENGINE=InnoDB; CREATE TABLE second_fixture (id INT PRIMARY KEY) ENGINE=InnoDB; INSERT INTO first_fixture VALUES (1, 'synthetic fixture only');", 'ad_p0_measure')
sql = (repo / 'scripts/ops/p0-db-size.sql').read_text()
raw = query(sql, 'ad_p0_measure')
parts = raw.rstrip('\n').split('\t')
assert len(parts) == 3 and parts[0] == '2' and int(parts[1]) > 0 and parts[2] == '0'
with tempfile.TemporaryDirectory(prefix='ad-p0-db-integration-') as tmp:
    directory = Path(tmp)
    for name in ['measure-p0-db.sh', 'p0-db-size.php', 'p0-db-size.sql']:
        shutil.copy(repo / 'scripts/ops' / name, directory / name)
    # Only WP-CLI transport is replaced by this fixture shim; actual SQL, MySQL,
    # capture/cleanup shell, parser and exit-code handling are the production code.
    shim = directory / 'wp'
    shim.write_text('#!' + sys.executable + '\n' + '''import os, subprocess, sys
a=sys.argv[1:]
assert a[:5] == ['--path=/isolated-fixture','--skip-plugins','--skip-themes','db','query']
assert a[6:] == ['--batch','--raw','--skip-column-names']
mode=os.environ['P0_FIXTURE_MODE']
if mode=='unavailable':
    print('synthetic mysql unavailable',file=sys.stderr);sys.exit(127)
if mode=='warning':
    print('synthetic warning',file=sys.stderr)
database='ad_p0_empty' if mode=='empty' else 'ad_p0_measure'
sys.exit(subprocess.run(['mysql','--protocol=TCP','--host=127.0.0.1','--port=33306','--user=root','--connect-timeout=3','--batch','--raw','--skip-column-names',database,'--execute',a[5]]).returncode)
''')
    shim.chmod(0o700)
    for mode, success in [('ok', True), ('empty', False), ('warning', False), ('unavailable', False)]:
        env = dict(os.environ, PATH=str(directory) + os.pathsep + os.environ['PATH'], P0_FIXTURE_MODE=mode)
        r = subprocess.run(['bash', str(directory / 'measure-p0-db.sh'), '/isolated-fixture'], env=env, capture_output=True, text=True)
        assert (r.returncode == 0) == success, f'Unexpected measure outcome: {mode}'
        if success:
            assert r.stdout == parts[1] and r.stderr == ''
        else:
            assert r.stdout == '' and 'DB_MEASUREMENT_REFUSED ' in r.stderr
            assert 'synthetic mysql' not in r.stderr and 'synthetic warning' not in r.stderr and 'ad_p0_' not in r.stderr
            json.loads(r.stderr.split('DB_MEASUREMENT_REFUSED ', 1)[1])
        assert not list(directory.glob('.db-measure.*')), 'Private measurement scratch was not cleaned'
        print(f'PASS: isolated MySQL measurement {mode}; output isolation and cleanup verified.')
print('PASS: actual SQL aggregate + four deployed capture/parser scenarios; no GoDaddy access.')
