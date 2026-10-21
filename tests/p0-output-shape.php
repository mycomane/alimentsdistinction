<?php
require __DIR__ . '/../scripts/ops/p0-output-shape.php';
$count = 0;
function verify($ok) { global $count; if (!$ok) { throw new RuntimeException('Shape assertion failed'); } ++$count; }
$a = p0_shape("23\t5865472\t0\n");
verify($a['tabs'] === 2 && $a['lf'] === 1 && $a['other_bytes'] === 0);
verify(array_column($a['cells'], 'type') === ['digits', 'digits', 'digits']);
verify(p0_shape("23\t5865472.0\t0\r\n")['cells'][1]['type'] === 'decimal');
verify(p0_shape('5865472 B')['cells'][0]['type'] === 'byte_unit');
verify(p0_shape(" 23\t5865472\t0\n")['leading_whitespace'] === 1);
verify(p0_shape("\xEF\xBB\xBF23\t5865472\t0\n")['other_bytes'] === 3);
verify(p0_shape('')['cells'][0]['type'] === 'empty');
foreach (['private_database_name', 'mysql password=do-not-publish', 'connection-host.example.invalid', '5865472', 'SQL error SELECT secret FROM private'] as $secret) {
    $serialized = json_encode(p0_shape($secret));
    verify(!str_contains($serialized, $secret));
}
verify(count(p0_shape(str_repeat("x\t", 50))['cells']) === 8);
echo "PASS: $count structural diagnostics/redaction assertions.\n";
