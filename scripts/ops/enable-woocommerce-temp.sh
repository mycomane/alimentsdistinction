#!/usr/bin/env bash
# Invoked on the authorized temporary host by an approval-gated Actions job.
set -euo pipefail
umask 077
run_id="${1:?GitHub run id required}"
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
[ -n "$root" ] || { echo 'WordPress root unavailable'; exit 1; }
root="$(cd "$root" && pwd -P)"
expected='https://1302636.us22.myftpupload.com'
[ "$(wp --path="$root" --skip-plugins --skip-themes option get siteurl)" = "$expected" ] || { echo 'Site identity mismatch'; exit 1; }
[ "$(wp --path="$root" --skip-plugins --skip-themes option get stylesheet)" = aliments-distinction ] || exit 1
home_dir="$(cd "$HOME" && pwd -P)"
case "$home_dir/" in "$root/"*) echo 'No private location outside document root'; exit 1;; esac
for utility in curl tar gzip sha256sum; do command -v "$utility" >/dev/null || exit 1; done
backup="$home_dir/.ad-private-backups/wc-preactivate-$run_id"
[ ! -e "$backup" ] || { echo 'Run already initialized; refusing blind retry'; exit 1; }
mkdir -p "$home_dir/.ad-private-backups"
chmod 700 "$home_dir/.ad-private-backups"
mkdir -m 700 "$backup"
state="$backup/page-state.json"
mutated=0
probe=''
finish() {
  code=$?
  trap - EXIT
  if [ -n "$probe" ] && [ -f "$probe" ]; then php -r 'if(is_file($argv[1]))unlink($argv[1]);' "$probe"; fi
  if [ "$code" -ne 0 ] && [ "$mutated" -eq 1 ]; then
    echo 'Operation failed after activation began; applying scoped rollback.'
    wp --path="$root" eval-file "$ops_dir/woocommerce-stage.php" rollback "$state" || echo 'WARNING: scoped rollback failed; manual recovery required from private backup.'
  fi
  exit "$code"
}
trap finish EXIT
# Use no redirects and emit no page content or cookies.
check_coming_soon() {
  local path="$1"
  curl --fail --silent --show-error --max-time 30 --max-redirs 0 --proto '=https' -H 'Cache-Control: no-cache' "$expected/$path" -o "$backup/public-check.html"
  php -r '$s=file_get_contents($argv[1]);$s=preg_replace("~<(script|style)\\b[^>]*>.*?</\\1>~is","",$s);$s=strtolower(html_entity_decode(strip_tags($s)));if(strpos($s,"coming soon")===false){fwrite(STDERR,"Coming Soon text not confirmed; stopping.\n");exit(1);}echo "PASS: anonymous response remains Coming Soon.\n";' "$backup/public-check.html"
}
check_coming_soon "?ad_check=$run_id-before"
wp --path="$root" eval-file "$ops_dir/woocommerce-stage.php" capture "$state"
# Validate a complete private backup before creating the temporary version-only web probe.
if ! find "$root" -type l -print | while IFS= read -r link; do
  target="$(readlink -f "$link" 2>/dev/null || true)"
  case "$target" in "$root/"*) :;; *) echo 'External symlink prevents standalone backup'; exit 1;; esac
done; then exit 1; fi
[ -f "$root/wp-config.php" ] || [ -f "$root/../wp-config.php" ] || exit 1
size_kb="$(du -sk "$root" | awk '{print $1}')"
free_kb="$(df -Pk "$home_dir" | awk 'NR==2{print $4}')"
case "$free_kb" in ''|*[!0-9]*) exit 1;; esac
[ "$free_kb" -gt "$((size_kb*2+102400))" ] || { echo 'Insufficient private backup space'; exit 1; }
wp --path="$root" --skip-plugins --skip-themes db export "$backup/database.sql" >/dev/null 2>&1
[ -s "$backup/database.sql" ] && grep -q 'CREATE TABLE' "$backup/database.sql" || exit 1
gzip -n "$backup/database.sql"
tar -C "$root" -czf "$backup/site-files.tar.gz" .
tar -tzf "$backup/site-files.tar.gz" > "$backup/tar-index.txt"
grep -q '^./wp-load.php$' "$backup/tar-index.txt"
gzip -t "$backup/database.sql.gz"
if [ ! -f "$root/wp-config.php" ]; then cp "$root/../wp-config.php" "$backup/parent-wp-config.php"; fi
(
 cd "$backup"
 sha256sum site-files.tar.gz database.sql.gz > SHA256SUMS
 if [ -f parent-wp-config.php ]; then sha256sum parent-wp-config.php >> SHA256SUMS; fi
 sha256sum -c SHA256SUMS >/dev/null
)
cat > "$backup/RESTORE-READ-ME.txt" <<'RESTORE'
Authorized temporary WordPress ONLY: https://1302636.us22.myftpupload.com
This private on-host package precedes WooCommerce activation. Not an offsite backup.
Verify SHA256SUMS before recovery. site-files.tar.gz contains document-root files;
database.sql.gz contains the SQL export; parent-wp-config.php is included if relevant.
page-state.json records original page mappings and job-created page IDs.
Scoped rollback: deactivate WooCommerce, restore page mappings, trash only job-created pages.
WooCommerce schema/default options remain after scoped rollback.
Full file or database restoration requires a separate explicit recovery decision,
verified target root/database, paused writers, and protection of concurrent changes.
No complete restoration has been tested by this job.
RESTORE
chmod 600 "$backup"/*
echo "PASS: fresh private files/SQL backup verified: wc-preactivate-$run_id"
# This short-lived endpoint exposes only PHP version/SAPI and a small extension allowlist.
# No wp-config, phpinfo, credentials, WordPress content, user or database data is returned.
probe_name="_ad-webcheck-$(php -r 'echo bin2hex(random_bytes(16));').php"
probe="$root/$probe_name"
[ ! -e "$probe" ] || exit 1
cat > "$probe" <<'PHP'
<?php
if($_SERVER['REQUEST_METHOD']!=='GET'||time()-filemtime(__FILE__)>120){http_response_code(410);exit;}
header('Content-Type: application/json');header('Cache-Control: no-store');header('X-Robots-Tag: noindex, nofollow');
$extensions=[];foreach(['curl','dom','mbstring','openssl','mysqli','fileinfo','json'] as $name)$extensions[$name]=extension_loaded($name);
echo json_encode(['version'=>PHP_VERSION,'sapi'=>PHP_SAPI,'extensions'=>$extensions]);
PHP
chmod 644 "$probe"
curl --fail --silent --show-error --max-time 25 --max-redirs 0 --proto '=https' "$expected/$probe_name" -o "$backup/php-web.json"
php -r '$d=json_decode(file_get_contents($argv[1]),true);if(!is_array($d)||empty($d["version"])||$d["sapi"]==="cli"||version_compare($d["version"],"8.1","<")){fwrite(STDERR,"PHP web could not be validated.\n");exit(1);}foreach($d["extensions"] as $ok)if(!$ok){fwrite(STDERR,"A required web extension is missing.\n");exit(1);}echo "PASS: PHP web ",$d["version"]," and required extension allowlist confirmed.\n";' "$backup/php-web.json"
php -r 'unlink($argv[1]);' "$probe"
probe=''
mutated=1
wp --path="$root" eval-file "$ops_dir/woocommerce-stage.php" activate "$state"
wp --path="$root" eval-file "$ops_dir/woocommerce-stage.php" verify "$state"
check_coming_soon "?ad_check=$run_id-after"
for key in shop cart checkout myaccount; do
 id="$(wp --path="$root" --skip-plugins --skip-themes option get "woocommerce_${key}_page_id")"
 case "$id" in ''|*[!0-9]*) exit 1;; esac
 check_coming_soon "?page_id=$id&ad_check=$run_id"
done
[ "$(wp --path="$root" --skip-plugins --skip-themes option get siteurl)" = "$expected" ] || exit 1
echo 'SUCCESS: WooCommerce 11.2.1 active with four technical pages; anonymous homepage and pages remain Coming Soon.'
echo 'No product/customer/order creation, payment activation, theme redeployment, DNS, domain, Zoho or commercial-site operation performed.'
mutated=0
