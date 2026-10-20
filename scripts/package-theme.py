#!/usr/bin/env python3
"""Build an installable theme ZIP without repository, test data or credentials."""
from pathlib import Path
import argparse, zipfile
root = Path(__file__).resolve().parents[1]
parser = argparse.ArgumentParser()
parser.add_argument('--output', type=Path, default=root / 'dist/aliments-distinction-0.1.0.zip')
args = parser.parse_args()
theme = root / 'wordpress/aliments-distinction'
args.output.parent.mkdir(parents=True, exist_ok=True)
with zipfile.ZipFile(args.output, 'w', zipfile.ZIP_DEFLATED) as archive:
    for path in sorted(theme.rglob('*')):
        if path.is_file():
            info = zipfile.ZipInfo(str(Path(theme.name) / path.relative_to(theme)), (2026, 10, 20, 0, 0, 0))
            info.compress_type = zipfile.ZIP_DEFLATED
            info.external_attr = 0o100644 << 16
            archive.writestr(info, path.read_bytes())
print(args.output.resolve())
