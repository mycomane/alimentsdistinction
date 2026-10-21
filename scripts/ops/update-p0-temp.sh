#!/usr/bin/env bash
# Protected environment only. No theme activation, DB writes, accounts or commerce mutations.
set -euo pipefail
umask 077
run_id="${1:?run required}"
case "$run_id" in ''|*[!0-9]*) exit 1;; esac
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
home_dir="$(cd "$HOME" && pwd -P)"
expected='https://1302636.us22.myftpupload.com'
case "$home_dir/" in "$root/"*) echo 'No private storage outside webroot'; exit 1;; esac
test "$(wp --path="$root" --skip-plugins --skip-themes option get siteurl)" = "$expected"
test "$(wp --path="$root" --skip-plugins --skip-themes option get home)" = "$expected"
theme="$root/wp-content/themes/aliments-distinction"
test "$(realpath "$theme")" = "$theme"
test ! -L "$home_dir/.ad-private-backups"
for utility in php wp curl tar gzip sha256sum flock; do command -v "$utility" >/dev/null; done
exec 9> "$home_dir/.ad-p0-update.lock"
flock -n 9 || { echo 'Another P0 update is running'; exit 1; }
backup="$home_dir/.ad-private-backups/p0-011-$run_id"
test ! -e "$backup" || { echo 'Existing run state; no blind retry'; exit 1; }
php "$ops/p0-files.php" check "$theme" "$ops/p0-manifest.json" old
test -w "$theme" && test -w "$theme/style.css"
# rename(2) across this boundary must be atomic; never fall back to copy-overwrite.
test "$(stat -c %d "$home_dir")" = "$(stat -c %d "$theme")"
test "$(stat -c %u "$theme/style.css")" = "$(id -u)"
theme_kb="$(du -sk "$theme" | awk '{print $1}')"
db_bytes="$(wp --path="$root" --skip-plugins --skip-themes db size --size_format=b --format=csv | tail -n 1 | awk -F, '{print $NF}')"
case "$db_bytes" in ''|*[!0-9]*) echo 'Database size not measurable'; exit 1;; esac
free_kb="$(df -Pk "$home_dir" | awk 'NR==2{print $4}')"
test "$free_kb" -gt "$((theme_kb*4+db_bytes/1024*3+102400))" || { echo 'Insufficient private backup space'; exit 1; }
mkdir -p "$home_dir/.ad-private-backups"
chmod 700 "$home_dir/.ad-private-backups"
mkdir -m 700 "$backup"
check_public() {
  local phase="$1" id code
  for id in home 6 7 8 9; do
    local query="?ad_p0=$run_id-$phase"
    if [ "$id" != home ]; then query="?page_id=$id&ad_p0=$run_id-$phase"; fi
    code="$(curl --silent --show-error --max-time 35 --max-redirs 0 --proto '=https' -H 'Cache-Control: no-cache' -w '%{http_code}' "$expected/$query" -o "$backup/public-check.html")" || return 1
    test "$code" = 200 || return 1
    php -r '$s=file_get_contents($argv[1]);$s=preg_replace("~<(script|style)\b[^>]*>.*?</\1>~is","",$s);$s=strtolower(html_entity_decode(strip_tags($s)));if(strpos($s,"coming soon")===false)exit(1);' "$backup/public-check.html" || return 1
  done
  echo 'PASS: anonymous home and four technical pages HTTP 200 / Coming Soon.'
}
check_css() {
  local version="$1" hash="$2" code
  code="$(curl --silent --show-error --max-time 35 --max-redirs 0 --proto '=https' -w '%{http_code}' "$expected/wp-content/themes/aliments-distinction/style.css?ver=$version" -o "$backup/served-style.css")" || return 1
  test "$code" = 200 || return 1
  test "$(sha256sum "$backup/served-style.css" | awk '{print $1}')" = "$hash" || return 1
  echo "PASS: HTTP serves exact CSS bytes for version $version."
}
old_sha="$(php -r '$m=json_decode(file_get_contents($argv[1]),true);echo $m["old"]["style.css"];' "$ops/p0-manifest.json")"
new_sha="$(php -r '$m=json_decode(file_get_contents($argv[1]),true);echo $m["new"]["style.css"];' "$ops/p0-manifest.json")"
check_public before
check_css 0.1.0 "$old_sha"
wp --path="$root" eval-file "$ops/p0-state.php" capture "$backup/protected-state.json" 0.1.0
cp -a "$theme" "$backup/theme-0.1.0"
php "$ops/p0-files.php" check "$backup/theme-0.1.0" "$ops/p0-manifest.json" old
tar -C "$backup" -czf "$backup/theme-0.1.0.tar.gz" theme-0.1.0
wp --path="$root" --skip-plugins --skip-themes db export "$backup/database.sql" >/dev/null 2>&1
test -s "$backup/database.sql" && grep -q 'CREATE TABLE' "$backup/database.sql"
gzip -n "$backup/database.sql"
gzip -t "$backup/database.sql.gz"
tar -tzf "$backup/theme-0.1.0.tar.gz" >/dev/null
(cd "$backup" && sha256sum theme-0.1.0.tar.gz database.sql.gz > SHA256SUMS && sha256sum -c SHA256SUMS >/dev/null)
# Restore rehearsal to a private directory: entire theme, not only CSS.
mkdir "$backup/restore-rehearsal"
tar -xzf "$backup/theme-0.1.0.tar.gz" -C "$backup/restore-rehearsal"
php "$ops/p0-files.php" check "$backup/restore-rehearsal/theme-0.1.0" "$ops/p0-manifest.json" old
cp "$backup/theme-0.1.0/style.css" "$backup/rollback-ready.css"
cp "$ops/style.css" "$backup/candidate-ready.css"
# Exercise the exact replace + rollback helper on private scratch files, same device/owner.
cp -p "$theme/style.css" "$backup/rehearsal-live.css"
cp "$ops/style.css" "$backup/rehearsal-new.css"
cp "$backup/theme-0.1.0/style.css" "$backup/rehearsal-old.css"
php "$ops/p0-files.php" replace "$backup/rehearsal-new.css" "$backup/rehearsal-live.css" "$old_sha" "$new_sha"
php "$ops/p0-files.php" replace "$backup/rehearsal-old.css" "$backup/rehearsal-live.css" "$new_sha" "$old_sha"
cp "$ops/p0-manifest.json" "$backup/p0-manifest.json"
cat > "$backup/RESTORE-READ-ME.txt" <<'RESTORE'
Temporary site ONLY: https://1302636.us22.myftpupload.com
Full previous theme preserved in theme-0.1.0 and theme-0.1.0.tar.gz.
Verify SHA256SUMS and the complete old manifest before recovery.
Rollback only style.css using a fresh private copy of theme-0.1.0/style.css,
the p0-files.php atomic replace guard (new SHA -> old SHA), then the scoped
p0-state.php cache operation with 0.1.0 and baseline verification.
Refuse drift: never overwrite unrelated later edits. Recheck full old manifest.
No theme activation or SQL restoration is authorized by this operation.
SQL is a private on-host backup only. No full SQL restore/offsite recovery tested.
If interrupted after rename, compare the live full manifest to old/new before action.
RESTORE
echo "PASS: fresh SQL/files/SHA256 backup, extracted full-theme restore and atomic rollback rehearsal: p0-011-$run_id"
armed=0
finish() {
  local code=$? now rollback_ok=1
  trap - EXIT INT TERM HUP
  if [ "$code" -ne 0 ] && [ "$armed" -eq 1 ]; then
    echo 'FAILED: attempting same-theme CSS rollback; no SQL restoration.'
    now="$(sha256sum "$theme/style.css" | awk '{print $1}')"
    if [ "$now" = "$new_sha" ]; then
      php "$ops/p0-files.php" replace "$backup/rollback-ready.css" "$theme/style.css" "$new_sha" "$old_sha" || rollback_ok=0
    elif [ "$now" != "$old_sha" ]; then rollback_ok=0; fi
    wp --path="$root" eval-file "$ops/p0-state.php" cache "$backup/protected-state.json" 0.1.0 || rollback_ok=0
    php "$ops/p0-files.php" check "$theme" "$ops/p0-manifest.json" old || rollback_ok=0
    wp --path="$root" eval-file "$ops/p0-state.php" verify "$backup/protected-state.json" 0.1.0 || rollback_ok=0
    check_public rollback || rollback_ok=0
    check_css 0.1.0 "$old_sha" || rollback_ok=0
    if [ "$rollback_ok" -eq 1 ]; then echo 'ROLLBACK VERIFIED: previous 0.1.0 theme restored.';
    else echo 'CRITICAL: rollback could not be fully verified; stop and alert, no SQL restore.'; fi
  fi
  exit "$code"
}
trap finish EXIT
trap 'exit 130' INT
trap 'exit 143' TERM HUP
# Last checks immediately before a single atomic filesystem mutation.
php "$ops/p0-files.php" check "$theme" "$ops/p0-manifest.json" old
wp --path="$root" eval-file "$ops/p0-state.php" verify "$backup/protected-state.json" 0.1.0
armed=1
php "$ops/p0-files.php" replace "$backup/candidate-ready.css" "$theme/style.css" "$old_sha" "$new_sha"
wp --path="$root" eval-file "$ops/p0-state.php" cache "$backup/protected-state.json" 0.1.1
php "$ops/p0-files.php" check "$theme" "$ops/p0-manifest.json" new
wp --path="$root" eval-file "$ops/p0-state.php" verify "$backup/protected-state.json" 0.1.1
check_css 0.1.1 "$new_sha"
check_public after
armed=0
echo 'SUCCESS: P0 theme 0.1.1 files and CSS HTTP verified; Coming Soon/protected settings preserved.'
echo 'LIMIT: authenticated hosted responsive geometry not tested; no review identity created.'
