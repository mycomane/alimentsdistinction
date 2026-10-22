<?php
/** Strict bounded sum; the existing P0 margin is checked separately. */
require __DIR__ . '/../ops/p0-db-size.php';
if (count($argv) !== 4) {
    throw new RuntimeException('Three measured sizes required');
}
$sum = 0;
foreach (array_slice($argv, 1) as $value) {
    $sum += p0_safe_integer($value, false);
}
echo p0_safe_integer((string) $sum);
