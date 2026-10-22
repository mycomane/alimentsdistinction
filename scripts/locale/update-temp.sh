#!/usr/bin/env bash
# Authorized language-only configuration. No stored text/commercial/theme change.
set -euo pipefail
umask 077
run_id="${1:?run required}"
case "$run_id" in ''|*[!0-9]*) exit 1;; esac
ops="$(cd "$(dirname "$0")" && pwd -P)"
bundle="$(cd "$ops/.." && pwd -P)"
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
for utility in php wp curl tar gzip sha256sum flock; do command -v "$utility" >/dev/null; done
test ! -L "$home_dir/.ad-private-backups"
exec 9> "$home_dir/.ad-theme-update.lock"
flock -n 9 || { echo 'Another temporary site operation is running'; exit 1; }
backup="$home_dir/.ad-private-backups/locale-fr-ca-$run_id"
test ! -e "$backup" || { echo 'Existing run state; no blind retry'; exit 1; }
theme="$root/wp-content/themes/aliments-distinction"
lang="$root/wp-content/languages"
php "$bundle/ops/p0-files.php" check "$theme" "$bundle/theme-manifest.json" old
wp --path="$root" eval-file "$ops/state.php" audit
# Same-device per-file renames and the unchanged strict SQL/disk measurement.
test "$(stat -c %d "$home_dir")" = "$(stat -c %d "$root/wp-content")"
theme_kb="$(du -sk "$theme" | awk '{print $1}')"
lang_kb=0
if [ -d "$lang" ]; then lang_kb="$(du -sk "$lang" | awk '{print $1}')"; fi
payload_kb="$(du -sk "$bundle/payload/languages" | awk '{print $1}')"
combined_kb="$(php "$ops/space.php" "$theme_kb" "$lang_kb" "$payload_kb")"
db_bytes="$(bash "$bundle/ops/measure-p0-db.sh" "$root")"
free_kb="$(df -Pk "$home_dir" | awk 'NR==2{print $4}')"
php "$bundle/ops/p0-db-size.php" space "$combined_kb" "$db_bytes" "$free_kb"
mkdir -p "$home_dir/.ad-private-backups"
chmod 700 "$home_dir/.ad-private-backups"
mkdir -m 700 "$backup"
check_public() {
  local phase="$1" id code query
  for id in home 6 7 8 9; do
    query="?ad_locale=$run_id-$phase"
    if [ "$id" != home ]; then query="?page_id=$id&ad_locale=$run_id-$phase"; fi
    code="$(curl --silent --show-error --max-time 35 --max-redirs 0 --proto '=https' -H 'Cache-Control: no-cache' -w '%{http_code}' "$expected/$query" -o "$backup/public-check.html")" || return 1
    test "$code" = 200 || return 1
    php "$ops/public-check.php" "$backup/public-check.html" || return 1
  done
  echo 'PASS: home/four technical pages HTTP 200 and Coming Soon preserved.'
}
check_public before
wp --path="$root" eval-file "$ops/state.php" capture "$backup/wp-baseline.json"
php "$ops/files.php" capture "$lang" "$bundle/manifest.json" "$backup/language-snapshot.json"
cp -a "$theme" "$backup/theme-0.1.2"
php "$bundle/ops/p0-files.php" check "$backup/theme-0.1.2" "$bundle/theme-manifest.json" old
mkdir "$backup/languages-before"
lang_existed=0
if [ -d "$lang" ]; then cp -a "$lang/." "$backup/languages-before/"; lang_existed=1; fi
php "$ops/files.php" check "$backup/languages-before" "$backup/language-snapshot.json" old
tar -C "$backup" -czf "$backup/theme-0.1.2.tar.gz" theme-0.1.2
tar -C "$backup" -czf "$backup/languages-before.tar.gz" languages-before
wp --path="$root" --skip-plugins --skip-themes db export "$backup/database.sql" >/dev/null 2>&1
test -s "$backup/database.sql" && grep -q 'CREATE TABLE' "$backup/database.sql"
gzip -n "$backup/database.sql"
gzip -t "$backup/database.sql.gz"
(cd "$backup" && sha256sum theme-0.1.2.tar.gz languages-before.tar.gz database.sql.gz wp-baseline.json language-snapshot.json > SHA256SUMS && sha256sum -c SHA256SUMS >/dev/null)
mkdir "$backup/extracted" "$backup/rehearsal-live"
tar -xzf "$backup/languages-before.tar.gz" -C "$backup/extracted"
php "$ops/files.php" check "$backup/extracted/languages-before" "$backup/language-snapshot.json" old
if [ "$lang_existed" -eq 1 ]; then cp -a "$backup/extracted/languages-before" "$backup/rehearsal-live/languages"; fi
cp -a "$bundle/payload/languages" "$backup/rehearsal-candidate"
cp -a "$backup/languages-before" "$backup/rehearsal-old"
php "$ops/files.php" apply "$backup/rehearsal-candidate" "$backup/rehearsal-live/languages" "$backup/language-snapshot.json" "$backup/rehearsal-journal.json"
php "$ops/files.php" rollback "$backup/rehearsal-old" "$backup/rehearsal-live/languages" "$backup/language-snapshot.json" "$backup/rehearsal-rollback.json"
cp -a "$bundle/payload/languages" "$backup/candidate-ready"
cp -a "$backup/languages-before" "$backup/rollback-ready"
cp "$bundle/manifest.json" "$bundle/theme-manifest.json" "$backup/"
mkdir "$backup/helpers"
cp -a "$ops" "$bundle/ops" "$backup/helpers/"
cat > "$backup/RESTORE-READ-ME.txt" <<'RESTORE'
Temporary site ONLY. Verify SHA256SUMS first. No SQL import is authorized.
Restore only WPLANG via helpers/locale/state.php rollback wp-baseline.json,
which restores the exact original option value/autoload or removes a newly
created option. It refuses an unknown locale edit. Then use helpers/locale/files.php
rollback with fresh old file copies from the verified languages archive, the
live wp-content/languages path, language-snapshot.json and a private journal.
Only known old/new translation files are accepted; unknown drift is refused.
Do not overwrite other plugins' or languages' changes. Verify original locale,
full language inventory, unchanged theme/protected state and Coming Soon again.
The pristine backups must not be consumed; rename consumes recovery staging.
Backup is on-host only; no full SQL restore/offsite recovery certified.
RESTORE
echo "PASS: fresh SQL/theme/languages/SHA backup and private language install/rollback rehearsal: locale-fr-ca-$run_id"
armed=0
finish() {
  local code=$? rollback_ok=1
  trap - EXIT INT TERM HUP
  if [ "$code" -ne 0 ] && [ "$armed" -eq 1 ]; then
    echo 'FAILED: targeted WPLANG and language-file rollback; no SQL import.'
    wp --path="$root" eval-file "$ops/state.php" rollback "$backup/wp-baseline.json" || rollback_ok=0
    if [ "$rollback_ok" -eq 1 ]; then
      php "$ops/files.php" rollback "$backup/rollback-ready" "$lang" "$backup/language-snapshot.json" "$backup/rollback-journal.json" || rollback_ok=0
    fi
    wp --path="$root" eval-file "$ops/state.php" invalidate-discovery "$backup/wp-baseline.json" || rollback_ok=0
    wp --path="$root" eval-file "$ops/state.php" verify "$backup/wp-baseline.json" old || rollback_ok=0
    php "$ops/files.php" check "$lang" "$backup/language-snapshot.json" old || rollback_ok=0
    php "$bundle/ops/p0-files.php" check "$theme" "$bundle/theme-manifest.json" old || rollback_ok=0
    check_public rollback || rollback_ok=0
    if [ "$rollback_ok" -eq 1 ]; then echo 'ROLLBACK VERIFIED: exact original locale and languages restored.';
    else echo 'CRITICAL: recovery not fully verified; stop, preserve backups, no SQL import.'; fi
  fi
  exit "$code"
}
trap finish EXIT
trap 'exit 130' INT
trap 'exit 143' TERM HUP
php "$ops/files.php" check "$lang" "$backup/language-snapshot.json" old
wp --path="$root" eval-file "$ops/state.php" verify "$backup/wp-baseline.json" old
php "$bundle/ops/p0-files.php" check "$theme" "$bundle/theme-manifest.json" old
armed=1
php "$ops/files.php" apply "$backup/candidate-ready" "$lang" "$backup/language-snapshot.json" "$backup/apply-journal.json"
wp --path="$root" eval-file "$ops/state.php" activate "$backup/wp-baseline.json"
wp --path="$root" eval-file "$ops/state.php" verify "$backup/wp-baseline.json" new
php "$ops/files.php" check "$lang" "$backup/language-snapshot.json" new
php "$bundle/ops/p0-files.php" check "$theme" "$bundle/theme-manifest.json" old
check_public after
wp --path="$root" eval-file "$ops/state.php" audit
armed=0
echo 'SUCCESS: fr_CA effective and native French labels verified; theme/protected settings/Coming Soon preserved.'
echo 'LIMIT: stored privacy content is unchanged; no authenticated visual review or test account created.'
