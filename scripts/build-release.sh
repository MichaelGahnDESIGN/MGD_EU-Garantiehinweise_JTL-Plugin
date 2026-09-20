#!/usr/bin/env bash
set -euo pipefail

VERSION="${1:-1.1.0}"
[[ "$VERSION" =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]] || { echo "Ungültige Version" >&2; exit 1; }
ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
PLUGIN_ID="MGD_EU_Garantiehinweise"
XML_VERSION="$(php -r '$xml = simplexml_load_file($argv[1]); echo $xml->Version;' "${ROOT_DIR}/plugin/${PLUGIN_ID}/info.xml")"
[[ "$VERSION" == "$XML_VERSION" ]] || { echo "Paketversion stimmt nicht mit info.xml überein" >&2; exit 1; }
BUILD_DIR="${ROOT_DIR}/build/release"
DIST_DIR="${ROOT_DIR}/dist"
ARCHIVE="${DIST_DIR}/MGD_EU-Garantiehinweise_JTL-Plugin-${VERSION}.zip"

rm -rf "${BUILD_DIR}"
mkdir -p "${BUILD_DIR}" "${DIST_DIR}"
cp -R "${ROOT_DIR}/plugin/${PLUGIN_ID}" "${BUILD_DIR}/${PLUGIN_ID}"
cp "${ROOT_DIR}/LICENSE" "${BUILD_DIR}/${PLUGIN_ID}/LICENSE"
mkdir -p "${BUILD_DIR}/${PLUGIN_ID}/docs"
for DOC in INSTALLATION.md EINSTELLUNGEN.md JTL-WAWI-ATTRIBUTE.md SICHERHEIT.md RECHTLICHE-ABGRENZUNG.md EU-QUELLEN.md; do
    cp "${ROOT_DIR}/docs/${DOC}" "${BUILD_DIR}/${PLUGIN_ID}/docs/${DOC}"
done
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
