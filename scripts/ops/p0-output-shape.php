<?php
/** No raw text/values/hashes: structure only, including for command failures. */
if (PHP_SAPI !== 'cli') { exit(1); }
function p0_shape(string $value): array {
    $length = strlen($value);
    $shape = ['bytes' => $length, 'digits' => preg_match_all('/[0-9]/', $value), 'letters' => preg_match_all('/[A-Za-z]/', $value)];
    foreach (["\t" => 'tabs', "\r" => 'cr', "\n" => 'lf', ' ' => 'spaces', ',' => 'commas', '|' => 'pipes', '.' => 'dots'] as $char => $label) {
        $shape[$label] = substr_count($value, $char);
    }
    $shape['other_bytes'] = $length - array_sum(array_slice($shape, 1));
    $shape['leading_whitespace'] = strlen($value) - strlen(ltrim($value, " \t\r\n"));
    $shape['trailing_whitespace'] = strlen($value) - strlen(rtrim($value, " \t\r\n"));
    // Tokens classified only; a database name, error, value or SQL never appears.
    $shape['cells'] = [];
    foreach (array_slice(explode("\t", rtrim($value, "\r\n")), 0, 8) as $cell) {
        $type = 'other';
        if ($cell === '') { $type = 'empty'; }
        elseif (preg_match('/\A[0-9]+\z/D', $cell)) { $type = 'digits'; }
        elseif (preg_match('/\A[0-9]+\.[0-9]+\z/D', $cell)) { $type = 'decimal'; }
        elseif (preg_match('/\A[0-9]+ B\z/D', $cell)) { $type = 'byte_unit'; }
        $shape['cells'][] = ['bytes' => strlen($cell), 'type' => $type];
    }
    return $shape;
}
if (realpath($_SERVER['SCRIPT_FILENAME']) === __FILE__) {
    $label = $argv[1] ?? '';
    if (!in_array($label, ['aggregate', 'constant_probe', 'native_bytes', 'cli_version'], true)) { exit(1); }
    if (!isset($argv[4]) || !ctype_digit($argv[4]) || (int) $argv[4] > 255) { exit(1); }
    $data = ['label' => $label, 'command_exit' => (int) $argv[4]];
    foreach (['stdout' => $argv[2], 'stderr' => $argv[3]] as $key => $path) {
        if (!is_file($path) || filesize($path) > 65536) { echo "DIAGNOSTIC_REFUSED invalid_capture\n"; exit(1); }
        $data[$key] = p0_shape(file_get_contents($path));
    }
    if ($label === 'cli_version' && $data['command_exit'] === 0 && $data['stderr']['bytes'] === 0) {
        $value = file_get_contents($argv[2]);
        if (preg_match('/\AWP-CLI ([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})(?:\r?\n)?\z/D', $value, $m)) {
            $data['version'] = $m[1];
        }
    }
    echo 'P0_OUTPUT_SHAPE ' . json_encode($data) . "\n";
}
