<?php
/** Strict, non-disclosing parser for the single aggregate SQL row. No DB access. */
if (PHP_SAPI !== 'cli') { exit(1); }
function p0_safe_integer(string $value, bool $positive = true): int {
    // Bound before casting: safe in PHP 64-bit and in the later shell arithmetic.
    $max = '9007199254740991';
    if (PHP_INT_SIZE !== 8 || !preg_match('/\A(?:0|[1-9][0-9]*)\z/D', $value)
        || strlen($value) > strlen($max) || (strlen($value) === strlen($max) && strcmp($value, $max) > 0)
        || ($positive && $value === '0')) {
        throw new RuntimeException('unsafe_integer');
    }
    return (int) $value;
}
function p0_db_bytes(string $stdout, string $stderr, int $status): int {
    if ($status !== 0) { throw new RuntimeException('query_failed'); }
    if ($stderr !== '') { throw new RuntimeException('query_stderr'); }
    // MySQL --batch --raw --skip-column-names: exactly one TSV row, LF/CRLF optional.
    // Do not strip units, locale separators, headers, warnings, or extra rows.
    if (!preg_match('/\A([0-9]+)\t([0-9]+)\t([0-9]+)(?:\r?\n)?\z/D', $stdout, $m)) {
        throw new RuntimeException('unexpected_output_shape');
    }
    p0_safe_integer($m[1]); // At least one visible base table is required.
    $bytes = p0_safe_integer($m[2]);
    if (p0_safe_integer($m[3], false) !== 0) { throw new RuntimeException('incomplete_table_statistics'); }
    return $bytes;
}
function p0_check_space(string $theme, string $database, string $free): void {
    $theme = p0_safe_integer($theme); $database = p0_safe_integer($database); $free = p0_safe_integer($free);
    // Preserve/increase the previous margin: 4 theme copies + 3 database sizes + 100 MiB.
    $required = $theme * 4 + intdiv($database * 3 + 1023, 1024) + 102400;
    if ($free <= $required) { throw new RuntimeException('insufficient_private_backup_space'); }
}
if (realpath($_SERVER['SCRIPT_FILENAME']) === __FILE__) {
    $out = ''; $err = ''; $status = 1;
    try {
        if (($argv[1] ?? '') === 'space') {
            p0_check_space($argv[2], $argv[3], $argv[4]);
            echo "PASS: measured backup space satisfies unchanged safety margin.\n";
        } elseif (($argv[1] ?? '') === 'parse') {
            if (!isset($argv[4]) || !ctype_digit($argv[4]) || (int) $argv[4] > 255
                || !is_file($argv[2]) || !is_file($argv[3])
                || filesize($argv[2]) > 65536 || filesize($argv[3]) > 65536) {
                throw new RuntimeException('invalid_measurement_transport');
            }
            $out = file_get_contents($argv[2]); $err = file_get_contents($argv[3]); $status = (int) $argv[4];
            echo p0_db_bytes($out, $err, $status);
        } else { throw new RuntimeException('unknown_measurement_mode'); }
    } catch (RuntimeException $e) {
        // Fixed codes and lengths only. Never print SQL errors, database names or raw output.
        fwrite(STDERR, 'DB_MEASUREMENT_REFUSED ' . json_encode([
            'reason' => $e->getMessage(), 'command_exit' => $status,
            'stdout_chars' => strlen($out), 'stderr_chars' => strlen($err),
            'shape' => preg_match('/\A[0-9]+\t[0-9]+\t[0-9]+(?:\r?\n)?\z/D', $out) ? 'numeric_tsv' : 'non_numeric_or_empty',
        ]) . "\n");
        exit(1);
    }
}
