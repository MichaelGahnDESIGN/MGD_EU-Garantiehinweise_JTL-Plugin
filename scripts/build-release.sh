#!/usr/bin/env bash
set -euo pipefail

VERSION="${1:-}"
ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
PLUGIN_ID="MGD_EU_Garantiehinweise"
BUILD_DIR="${ROOT_DIR}/build/release"
DIST_DIR="${ROOT_DIR}/dist"
ARCHIVE="${DIST_DIR}/MGD_EU-Garantiehinweise_JTL-Plugin-${VERSION}.zip"

if [[ ! "$VERSION" =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]] \
    || ! grep -Fqx "    <Version>${VERSION}</Version>" "${ROOT_DIR}/plugin/${PLUGIN_ID}/info.xml"; then
    echo "Aufruf: scripts/build-release.sh X.Y.Z (muss info.xml entsprechen)" >&2
    exit 2
fi

if [[ -e "$BUILD_DIR" || -e "$ARCHIVE" ]]; then
    echo "Release-Build existiert bereits; nur in sauberem Build-Verzeichnis starten." >&2
    exit 1
fi

mkdir -p "${BUILD_DIR}" "${DIST_DIR}"
cp -R "${ROOT_DIR}/plugin/${PLUGIN_ID}" "${BUILD_DIR}/${PLUGIN_ID}"
find "${BUILD_DIR}" -name '.DS_Store' -delete

(
    cd "${BUILD_DIR}"
    zip -X -q -r "${ARCHIVE}" "${PLUGIN_ID}"
)

(
    cd "${DIST_DIR}"
    shasum -a 256 "$(basename "${ARCHIVE}")" > "$(basename "${ARCHIVE}").sha256"
)

printf 'Release erstellt: %s\n' "${ARCHIVE}"
