#!/usr/bin/env python3
"""Read-only gate: require the existing isolated QA workflow on this exact commit."""
import json, os, time, urllib.request, urllib.parse
repo = os.environ['GITHUB_REPOSITORY']
sha = os.environ['GITHUB_SHA']
assert repo == 'mycomane/alimentsdistinction'
assert os.environ['GITHUB_REF'] == 'refs/heads/feat/wordpress-woocommerce-theme'
query = urllib.parse.urlencode({'branch':'feat/wordpress-woocommerce-theme','event':'push','head_sha':sha,'per_page':30})
url = f'https://api.github.com/repos/{repo}/actions/workflows/qa-p1-approved-copy.yml/runs?{query}'
deadline = time.monotonic() + 900
while time.monotonic() < deadline:
    request = urllib.request.Request(url, headers={'Authorization':'Bearer '+os.environ['GH_TOKEN'],'Accept':'application/vnd.github+json','X-GitHub-Api-Version':'2022-11-28'})
    with urllib.request.urlopen(request,timeout=30) as response:
        rows=json.load(response)['workflow_runs']
    rows=[r for r in rows if r['head_sha']==sha and r['head_branch']=='feat/wordpress-woocommerce-theme' and r['event']=='push' and r['path']=='.github/workflows/qa-p1-approved-copy.yml']
    if len(rows)>1:
        raise SystemExit('Multiple exact-commit QA runs; explicit review required')
    if rows and rows[0]['status']=='completed':
        assert rows[0]['conclusion']=='success', 'Exact-commit isolated QA did not pass'
        print('PASS: exact-commit isolated QA '+rows[0]['html_url'])
        break
    time.sleep(15)
else:
    raise SystemExit('No successful exact-commit isolated QA within review window')
