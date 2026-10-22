#!/usr/bin/env python3
"""Regression guards for native QA ordering and the mandatory human gate.

These source contracts supplement GitHub's actual workflow execution; they do
not simulate its scheduler or prove the current environment settings.
"""
from pathlib import Path
import re
import unittest

ROOT = Path(__file__).resolve().parents[1]
CONFIG = (ROOT / '.github/workflows/configure-locale-fr-ca.yml').read_text()
QA = (ROOT / '.github/workflows/qa-locale-fr-ca.yml').read_text()


def job(name):
    match = re.search(r'^  ' + re.escape(name) + r':\n(.*?)(?=^  [\w-]+:\n|\Z)', CONFIG, re.M | re.S)
    if not match:
        raise AssertionError('Missing workflow job: ' + name)
    return match.group(1)


class NativeWorkflowGuards(unittest.TestCase):
    def test_same_commit_local_call_without_polling(self):
        qa = job('isolated-qa')
        self.assertIn('uses: ./.github/workflows/qa-locale-fr-ca.yml', qa)
        for forbidden in ['steps:', 'runs-on:', 'timeout-minutes:', 'wait-qa.py', 'secrets:']:
            self.assertNotIn(forbidden, qa)
        self.assertIn('  workflow_call:', QA)
        self.assertNotIn('  push:', QA)  # No second automatic run of the same QA.
        self.assertNotIn('wait-qa.py', CONFIG)

    def test_deploy_requires_both_successes(self):
        deploy = job('deploy')
        self.assertIn('needs: [prepare, isolated-qa]', deploy)
        self.assertNotIn('always()', deploy)
        self.assertNotIn('continue-on-error:', CONFIG + QA)
        self.assertIn("if: github.ref == 'refs/heads/feat/wordpress-woocommerce-theme'", deploy)

    def test_human_gate_before_checkout_or_remote_access(self):
        deploy = job('deploy')
        self.assertIn('environment: godaddy-temp', deploy)
        gate = deploy.index('name: Require recorded human approval for this exact run')
        self.assertLess(gate, deploy.index('uses: actions/checkout@v4'))
        self.assertLess(gate, deploy.index('name: Pin SSH identity before authentication'))
        self.assertIn("r.get('state') == 'approved'", deploy)
        self.assertIn("r.get('user', {}).get('login') == 'mycomane'", deploy)
        self.assertIn('actions/runs/$GITHUB_RUN_ID/approvals', deploy)

    def test_qa_has_no_host_credentials(self):
        for forbidden in ['secrets.', 'secrets:', 'environment:', 'sshpass', 'GODADDY_']:
            self.assertNotIn(forbidden, QA)
        self.assertIn('contents: read', QA)
        self.assertIn('persist-credentials: false', QA)

    def test_isolation_and_mutation_lock_preserved(self):
        self.assertIn('group: godaddy-temp-site-mutation', CONFIG)
        self.assertIn('cancel-in-progress: false', CONFIG)
        self.assertIn('127.0.0.1:8787', QA)
        self.assertIn('php scripts/qa/no-orders.php', QA)
        self.assertIn('python3 tests/locale-workflow.py', job('prepare'))


if __name__ == '__main__':
    unittest.main(verbosity=2)
