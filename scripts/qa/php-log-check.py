#!/usr/bin/env python3
"""Report only diagnostic counters from the disposable PHP server, never raw log content."""
from pathlib import Path
import json, os, re

runner = Path(os.environ['RUNNER_TEMP']).resolve()
root = runner / 'ad-isolated-qa'
server_log = runner / 'ad-qa-php.log'
if not server_log.is_file():
    raise SystemExit('Missing isolated PHP server log')
report = {'scope': 'isolated-only', 'logs': [], 'fatal_count': 0}
for name, log in [('php_server', server_log), ('wordpress_debug', root / 'wordpress/wp-content/debug.log')]:
    content = log.read_text(errors='replace') if log.is_file() else ''
    fatal = len(re.findall(r'\b(?:Fatal error|Parse error|Uncaught \w*(?:Error|Exception))\b', content, re.I))
    warnings = len(re.findall(r'\b(?:PHP )?Warning:', content, re.I))
    deprecated = len(re.findall(r'\b(?:PHP )?Deprecated:', content, re.I))
    report['logs'].append({'name': name, 'present': log.is_file(), 'fatal': fatal, 'warnings': warnings, 'deprecated': deprecated})
    report['fatal_count'] += fatal
evidence = root / 'evidence'
evidence.mkdir(exist_ok=True)
(evidence / 'php-diagnostics.json').write_text(json.dumps(report, indent=2))
print('AD_PHP_DIAGNOSTICS ' + json.dumps(report))
if report['fatal_count']:
    raise SystemExit('Fatal PHP diagnostics in isolated scenario')
