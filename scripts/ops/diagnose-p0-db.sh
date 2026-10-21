#!/usr/bin/env bash
# Read-only WordPress/MySQL diagnostics. Private scratch is removed; no deployment.
set -euo pipefail
umask 077
ops="$(cd "$(dirname "$0")" && pwd -P)"
root=''
for c in "$PWD" "$HOME" "$HOME/html" "$HOME/public_html" "$PWD/html" "$PWD/public_html"; do
  if [ -f "$c/wp-load.php" ]; then root="$c"; break; fi
done
if [ -z "$root" ]; then
  f="$(find "$HOME" -maxdepth 4 -type f -name wp-load.php -print -quit 2>/dev/null || true)"
  [ -z "$f" ] || root="$(dirname "$f")"
fi
test -n "$root"
root="$(cd "$root" && pwd -P)"
expected='https://1302636.us22.myftpupload.com'
test "$(wp --path="$root" --skip-plugins --skip-themes option get siteurl)" = "$expected"
test "$(wp --path="$root" --skip-plugins --skip-themes option get home)" = "$expected"
test "$(wp --path="$root" --skip-plugins --skip-themes option get stylesheet)" = aliments-distinction
php "$ops/p0-files.php" check "$root/wp-content/themes/aliments-distinction" "$ops/p0-manifest.json" old
scratch="$(mktemp -d "$ops/.db-diagnostic.XXXXXXXX")"
trap 'rm -f -- "$scratch/stdout" "$scratch/stderr" "$scratch/public.html"; rmdir -- "$scratch"' EXIT
trap 'exit 130' INT
trap 'exit 143' TERM HUP
for page in home 6 7 8 9; do
  query='?ad_p0_diagnostic=1'
  if [ "$page" != home ]; then query="?page_id=$page&ad_p0_diagnostic=1"; fi
  code="$(curl --silent --show-error --max-time 35 --max-redirs 0 --proto '=https' -w '%{http_code}' "$expected/$query" -o "$scratch/public.html")"
  test "$code" = 200
  php "$ops/p0-files.php" coming-soon "$scratch/public.html"
done
echo 'PASS: temporary identity, original 23 theme files and anonymous Coming Soon checked after failed update.'
capture() {
  local label="$1" status=0
  shift
  LC_ALL=C "$@" > "$scratch/stdout" 2> "$scratch/stderr" || status=$?
  php "$ops/p0-output-shape.php" "$label" "$scratch/stdout" "$scratch/stderr" "$status"
}
capture cli_version wp cli version
capture aggregate wp --path="$root" --skip-plugins --skip-themes db query "$(cat "$ops/p0-db-size.sql")" --batch --raw --skip-column-names
capture constant_probe wp --path="$root" --skip-plugins --skip-themes db query 'SELECT 17, 1234567, 0;' --batch --raw --skip-column-names
capture native_bytes wp --path="$root" --skip-plugins --skip-themes db size --size_format=b
echo 'PASS: structural diagnostics only; no backup, CSS replacement, cache purge, account or DB write command.'
