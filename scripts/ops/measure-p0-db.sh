#!/usr/bin/env bash
# Called only after the caller verifies the exact temporary WordPress identity.
# Capture all raw output in a private scratch directory and discard it on any exit.
set -euo pipefail
umask 077
root="${1:?WordPress path required}"
ops="$(cd "$(dirname "$0")" && pwd -P)"
scratch="$(mktemp -d "$ops/.db-measure.XXXXXXXX")"
trap 'rm -f -- "$scratch/stdout" "$scratch/stderr"; rmdir -- "$scratch"' EXIT
trap 'exit 130' INT
trap 'exit 143' TERM HUP
status=0
LC_ALL=C wp --path="$root" --skip-plugins --skip-themes db query \
  "$(cat "$ops/p0-db-size.sql")" --batch --raw --skip-column-names \
  > "$scratch/stdout" 2> "$scratch/stderr" || status=$?
php "$ops/p0-db-size.php" parse "$scratch/stdout" "$scratch/stderr" "$status"
