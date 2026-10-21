<?php
require __DIR__ . '/../scripts/ops/p0-db-size.php';
$count = 0;
function checked($ok) { global $count; if (!$ok) { throw new RuntimeException('Assertion failed'); } ++$count; }
function rejected($fn) { try { $fn(); } catch (RuntimeException $e) { checked(true); return; } throw new RuntimeException('Unsafe measure accepted'); }
foreach (["23\t5865472\t0", "23\t5865472\t0\n", "23\t5865472\t0\r\n"] as $row) {
    checked(p0_db_bytes($row, '', 0) === 5865472);
}
foreach ([
    '', "\n", '5865472', '5865472 B', 'Name,Size\nfixture,5865472 B',
    "23\t5,865,472\t0\n", "23\t5 865 472\t0\n", "23\t5.865.472\t0\n",
    "23\t5\u{00A0}865\u{00A0}472\t0\n", "23\t5865472.0\t0\n", "23\t5.865472e6\t0\n",
    "23\t-1\t0\n", "23\t+1\t0\n", "23\t05865472\t0\n", "23\tNULL\t0\n",
    "0\t5865472\t0\n", "23\t0\t0\n", "23\t5865472\t1\n",
    "23\t9007199254740992\t0\n", "23\t18446744073709551615\t0\n",
    "23\t5865472\t0\n23\t5865472\t0\n", "Warning: synthetic\n23\t5865472\t0\n",
    "23\t5865472\t0\nSuccess: done\n", "23\t5865472\t0\0", " 23\t5865472\t0\n",
] as $row) { rejected(fn() => p0_db_bytes($row, '', 0)); }
rejected(fn() => p0_db_bytes("23\t5865472\t0\n", 'Warning: synthetic', 0));
rejected(fn() => p0_db_bytes("23\t5865472\t0\n", '', 1));
rejected(fn() => p0_db_bytes('', 'mysql unavailable', 127));
checked(p0_db_bytes("23\t9007199254740991\t0\n", '', 0) === 9007199254740991);
$required = 4 * 100 + intdiv(1025 * 3 + 1023, 1024) + 102400;
rejected(fn() => p0_check_space('100', '1025', (string) $required));
rejected(fn() => p0_check_space('100', '1025', (string) ($required - 1)));
p0_check_space('100', '1025', (string) ($required + 1)); checked(true);
foreach (['0', '-1', '1e9', '9007199254740992'] as $free) { rejected(fn() => p0_check_space('100', '1025', $free)); }
echo "PASS: $count database parser and unchanged-space-margin assertions.\n";
