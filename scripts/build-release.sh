#!/usr/bin/env bash
set -euo pipefail

VERSION="${1:-1.0.0}"
ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
PLUGIN_ID="MGD_EU_Garantiehinweise"
BUILD_DIR="${ROOT_DIR}/build/release"
DIST_DIR="${ROOT_DIR}/dist"
ARCHIVE="${DIST_DIR}/MGD_EU-Garantiehinweise_JTL-Plugin-${VERSION}.zip"

rm -rf "${BUILD_DIR}"
mkdir -p "${BUILD_DIR}" "${DIST_DIR}"
cp -R "${ROOT_DIR}/plugin/${PLUGIN_ID}" "${BUILD_DIR}/${PLUGIN_ID}"
find "${BUILD_DIR}" -name '.DS_Store' -delete
rm -f "${ARCHIVE}" "${ARCHIVE}.sha256"

(
    cd "${BUILD_DIR}"
    zip -X -q -r "${ARCHIVE}" "${PLUGIN_ID}"
)

(
    cd "${DIST_DIR}"
    shasum -a 256 "$(basename "${ARCHIVE}")" > "$(basename "${ARCHIVE}").sha256"
)

printf 'Release erstellt: %s\n' "${ARCHIVE}"
