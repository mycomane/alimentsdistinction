#!/usr/bin/env bash
# No credentials in this script. create stdout is secret and must be redirected by the caller.
set -euo pipefail
umask 077
mode="${1:?mode required}"
run_id="${2:?run required}"
case "$mode" in create|cleanup) :;; *) exit 1;; esac
case "$run_id" in ''|*[!0-9]*) exit 1;; esac
ops_dir="$(cd "$(dirname "$0")" && pwd -P)"
root=''
for candidate in "$PWD" "$HOME" "$HOME/html" "$HOME/public_html" "$PWD/html" "$PWD/public_html"; do
  if [ -f "$candidate/wp-load.php" ]; then root="$candidate"; break; fi
done
if [ -z "$root" ]; then
  found="$(find "$HOME" -maxdepth 4 -type f -name wp-load.php -print -quit 2>/dev/null || true)"
  [ -z "$found" ] || root="$(dirname "$found")"
fi
[ -n "$root" ] || exit 1
[ "$(wp --path="$root" --skip-plugins --skip-themes option get siteurl 2>/dev/null)" = 'https://1302636.us22.myftpupload.com' ] || exit 1
wp --path="$root" eval-file "$ops_dir/review-account.php" "$mode" "$run_id"
