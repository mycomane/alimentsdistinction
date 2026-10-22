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
exec 9> "$home_dir/.ad-theme-update.lock"
flock -n 9 || { echo 'Another theme update is running'; exit 1; }
backup="$home_dir/.ad-private-backups/p1-013-$run_id"
test ! -e "$backup" || { echo 'Existing run state; no blind retry'; exit 1; }
php "$ops/p0-files.php" check "$theme" "$ops/p1-013-manifest.json" old
for file in footer.php front-page.php style.css template-parts/navigation.php; do
  test -w "$theme/$file" && test -w "$(dirname "$theme/$file")"
  test "$(stat -c %u "$theme/$file")" = "$(id -u)"
done
# rename(2) across this boundary must be atomic; never fall back to copy-overwrite.
test "$(stat -c %d "$home_dir")" = "$(stat -c %d "$theme")"
theme_kb="$(du -sk "$theme" "$root/wp-content/languages" | awk '{s+=$1} END{print s}')"
db_bytes="$(bash "$ops/measure-p0-db.sh" "$root")"
free_kb="$(df -Pk "$home_dir" | awk 'NR==2{print $4}')"
php "$ops/p0-db-size.php" space "$theme_kb" "$db_bytes" "$free_kb"
mkdir -p "$home_dir/.ad-private-backups"
chmod 700 "$home_dir/.ad-private-backups"
mkdir -m 700 "$backup"
check_public() {
  local phase="$1" id code
  for id in home 6 7 8 9; do
    local query="?ad_p1=$run_id-$phase"
    if [ "$id" != home ]; then query="?page_id=$id&ad_p1=$run_id-$phase"; fi
    code="$(curl --silent --show-error --max-time 35 --max-redirs 0 --proto '=https' -H 'Cache-Control: no-cache' -w '%{http_code}' "$expected/$query" -o "$backup/public-check.html")" || return 1
    test "$code" = 200 || return 1
    php "$ops/p0-files.php" coming-soon "$backup/public-check.html" || return 1
  done
  echo 'PASS: anonymous home and four technical pages HTTP 200 / Coming Soon.'
}
check_css() {
  local phase="$1" version="$2" file hash code
  for file in assets/css/design.css assets/css/commerce.css assets/js/theme.js style.css; do
    hash="$(php -r '$m=json_decode(file_get_contents($argv[1]),true,512,JSON_THROW_ON_ERROR);echo $m[$argv[2]][$argv[3]];' "$ops/p1-013-manifest.json" "$phase" "$file")" || return 1
    code="$(curl --silent --show-error --max-time 35 --max-redirs 0 --proto '=https' -H 'Cache-Control: no-cache' -w '%{http_code}' "$expected/wp-content/themes/aliments-distinction/$file?ver=$version" -o "$backup/served-css")" || return 1
    test "$code" = 200 || return 1
    test "$(sha256sum "$backup/served-css" | awk '{print $1}')" = "$hash" || return 1
  done
  echo "PASS: HTTP serves four exact static sources for version $version."
}
check_public before
check_css old 0.1.2
wp --path="$root" eval-file "$ops/p1-013-state.php" locale
wp --path="$root" eval-file "$ops/p1-013-state.php" capture "$backup/protected-state.json" 0.1.2
cp -a "$theme" "$backup/theme-0.1.2"
cp -a "$root/wp-content/languages" "$backup/languages"
php -r 'require $argv[1];$s=json_decode(file_get_contents($argv[2]),true,512,JSON_THROW_ON_ERROR);p0_check($argv[3],$s["languages"]);' "$ops/p0-files.php" "$backup/protected-state.json" "$backup/languages"
tar -C "$backup" -czf "$backup/languages.tar.gz" languages
tar -tzf "$backup/languages.tar.gz" >/dev/null
php "$ops/p0-files.php" check "$backup/theme-0.1.2" "$ops/p1-013-manifest.json" old
tar -C "$backup" -czf "$backup/theme-0.1.2.tar.gz" theme-0.1.2
wp --path="$root" --skip-plugins --skip-themes db export "$backup/database.sql" >/dev/null 2>&1
test -s "$backup/database.sql" && grep -q 'CREATE TABLE' "$backup/database.sql"
gzip -n "$backup/database.sql"
gzip -t "$backup/database.sql.gz"
tar -tzf "$backup/theme-0.1.2.tar.gz" >/dev/null
(cd "$backup" && sha256sum theme-0.1.2.tar.gz database.sql.gz languages.tar.gz > SHA256SUMS && sha256sum -c SHA256SUMS >/dev/null)
# Restore rehearsal to a private directory: entire theme, not only CSS.
mkdir "$backup/restore-rehearsal"
tar -xzf "$backup/theme-0.1.2.tar.gz" -C "$backup/restore-rehearsal"
php "$ops/p0-files.php" check "$backup/restore-rehearsal/theme-0.1.2" "$ops/p1-013-manifest.json" old
# Rehearse the complete four-file set update and recovery against the extracted private theme.
for staging in rehearsal-candidate rehearsal-old candidate-ready rollback-ready; do
  mkdir -p "$backup/$staging/template-parts"
done
for file in footer.php front-page.php style.css template-parts/navigation.php; do
  cp "$ops/payload/$file" "$backup/rehearsal-candidate/$file"
  cp "$ops/payload/$file" "$backup/candidate-ready/$file"
  cp "$backup/theme-0.1.2/$file" "$backup/rollback-ready/$file"
  cp "$backup/theme-0.1.2/$file" "$backup/rehearsal-old/$file"
done
php "$ops/p1-013-files.php" apply "$ops/p1-013-manifest.json" "$backup/rehearsal-candidate" "$backup/restore-rehearsal/theme-0.1.2" "$backup/rehearsal-apply.json"
php "$ops/p1-013-files.php" rollback "$ops/p1-013-manifest.json" "$backup/rehearsal-old" "$backup/restore-rehearsal/theme-0.1.2" "$backup/rehearsal-rollback.json"
# The pristine full backup and its tar remain untouched by the rehearsal.
php "$ops/p0-files.php" check "$backup/theme-0.1.2" "$ops/p1-013-manifest.json" old
cp "$ops/p1-013-manifest.json" "$backup/p1-013-manifest.json"
(cd "$backup" && sha256sum p1-013-manifest.json protected-state.json >> SHA256SUMS && sha256sum -c SHA256SUMS >/dev/null)
cp "$ops/p1-013-files.php" "$ops/p0-files.php" "$ops/p1-013-state.php" "$backup/"
cat > "$backup/RESTORE-READ-ME.txt" <<'RESTORE'
Temporary site ONLY: https://1302636.us22.myftpupload.com
Pristine full previous theme: theme-0.1.2 and theme-0.1.2.tar.gz.
Verify SHA256SUMS and the complete old manifest before recovery.
The exact set is footer.php, front-page.php, style.css, template-parts/navigation.php; no other file may differ.
If interrupted, inspect files-journal.json and full current inventory first.
p1-013-files.php rollback accepts only a known old/new mixture of the approved four-file set.
It refuses unknown or unrelated edits. Use fresh private old copies from the
verified tar with the same paths; rollback staging is consumed by rename.
After file recovery, invalidate only the theme-header cache through p1-013-state.php,
verify full old inventory/protected state/HTTP four-file set/Coming Soon again.
No theme activation or SQL restoration is authorized. Database backup is private
and on-host only; no offsite backup/full SQL restoration certified by this run.

RESTORE
echo "PASS: fresh SQL/files/SHA256 backup, extracted full-theme restore and four-file set update/rollback rehearsal: p1-013-$run_id"
armed=0
finish() {
  local code=$? rollback_ok=1
  trap - EXIT INT TERM HUP
  if [ "$code" -ne 0 ] && [ "$armed" -eq 1 ]; then
    echo 'FAILED: attempting same-theme four-file rollback; no SQL restoration.'
    php "$ops/p1-013-files.php" rollback "$ops/p1-013-manifest.json" "$backup/rollback-ready" "$theme" "$backup/files-rollback.json" || rollback_ok=0
    wp --path="$root" eval-file "$ops/p1-013-state.php" cache "$backup/protected-state.json" 0.1.2 || rollback_ok=0
    php "$ops/p0-files.php" check "$theme" "$ops/p1-013-manifest.json" old || rollback_ok=0
    wp --path="$root" eval-file "$ops/p1-013-state.php" verify "$backup/protected-state.json" 0.1.2 || rollback_ok=0
    check_public rollback || rollback_ok=0
    check_css old 0.1.2 || rollback_ok=0
    if [ "$rollback_ok" -eq 1 ]; then echo 'ROLLBACK VERIFIED: previous 0.1.2 four-file set and complete theme verified.';
    else echo 'CRITICAL: rollback could not be fully verified; stop and alert, no SQL restore.'; fi
  fi
  exit "$code"
}
trap finish EXIT
trap 'exit 130' INT
trap 'exit 143' TERM HUP
# Last checks before journaled per-file atomic replacements (not a global transaction).
php "$ops/p0-files.php" check "$theme" "$ops/p1-013-manifest.json" old
wp --path="$root" eval-file "$ops/p1-013-state.php" verify "$backup/protected-state.json" 0.1.2
armed=1
php "$ops/p1-013-files.php" apply "$ops/p1-013-manifest.json" "$backup/candidate-ready" "$theme" "$backup/files-journal.json"
wp --path="$root" eval-file "$ops/p1-013-state.php" cache "$backup/protected-state.json" 0.1.3
php "$ops/p0-files.php" check "$theme" "$ops/p1-013-manifest.json" new
wp --path="$root" eval-file "$ops/p1-013-state.php" verify "$backup/protected-state.json" 0.1.3
check_css new 0.1.3
check_public after
armed=0
echo 'SUCCESS: P1 theme 0.1.3 exact four-file set and static source HTTP verified; Coming Soon/protected settings preserved.'
echo 'LIMIT: authenticated hosted responsive geometry not tested; no review identity created.'
