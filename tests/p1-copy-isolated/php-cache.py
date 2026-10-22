"""Observe default PHP HTTP opcode cache and validate fresh renders with it disabled.
Disposable loopback probe only; no WordPress or hosted configuration modified.
"""
from pathlib import Path
import json, os, subprocess, tempfile, time, urllib.request
out=Path(os.environ['RUNNER_TEMP'])/'ad-isolated-qa/evidence'
out.mkdir(parents=True,exist_ok=True)
rows=[]
with tempfile.TemporaryDirectory(prefix='ad-php-cache-') as tmp:
    root=Path(tmp)
    (root/'config.php').write_text('<?php header("Content-Type: application/json");echo json_encode(["sapi"=>PHP_SAPI,"enable"=>ini_get("opcache.enable"),"enable_cli"=>ini_get("opcache.enable_cli"),"revalidate_freq"=>ini_get("opcache.revalidate_freq"),"file_update_protection"=>ini_get("opcache.file_update_protection")]);')
    for name,args in [('default',[]),('cli-only-disabled',['-d','opcache.enable_cli=0']),('disabled',['-d','opcache.enable=0','-d','opcache.enable_cli=0'])]:
        target=root/'render.php';target.write_text('<?php echo "OLD";')
        # Let default file-update protection expire before warming the probe.
        time.sleep(3)
        with (root/'server.log').open('w') as log:
            process=subprocess.Popen(['php',*args,'-S','127.0.0.1:18789','-t',str(root)],stdout=log,stderr=log)
            try:
                url='http://127.0.0.1:18789'
                for _ in range(50):
                    try:
                        with urllib.request.urlopen(url+'/config.php',timeout=1) as response: config=json.load(response)
                        break
                    except OSError: time.sleep(.1)
                else: raise RuntimeError('Local probe server unavailable')
                def render():
                    with urllib.request.urlopen(url+'/render.php',timeout=3) as response: return response.read().decode()
                before=render();target.write_text('<?php echo "NEW";');after=render()
                rows.append({'mode':name,'config':config,'before':before,'after_immediate_replace':after})
                (out/'php-cache-probe.json').write_text(json.dumps(rows,indent=2)+'\n')
                print('PHP_CACHE_OBSERVATION '+json.dumps(rows[-1]),flush=True)
                assert before=='OLD'
                if name=='disabled': assert config['enable'] in ['0',False] and after=='NEW', 'Opcode-free local render must use replaced bytes'
            finally:
                process.terminate();process.wait(timeout=5)
(out/'php-cache-probe.json').write_text(json.dumps(rows,indent=2)+'\n')
print('PHP_CACHE_PROBE '+json.dumps(rows))
print('PASS: explicit opcode-free local server renders freshly replaced PHP bytes.')
