#!/usr/bin/env bash

set -euo pipefail

PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PLUGIN_SLUG="$(basename "${PROJECT_ROOT}")"
BUILD_ROOT="${PROJECT_ROOT}/build"
STAGING_DIR="${BUILD_ROOT}/${PLUGIN_SLUG}"
MAIN_PLUGIN_FILE="${PROJECT_ROOT}/elementrify.php"

if [[ ! -f "${MAIN_PLUGIN_FILE}" ]]; then
    echo "Error: Could not find elementrify.php in ${PROJECT_ROOT}"
    exit 1
fi

echo "Preparing deployment for ${PLUGIN_SLUG}..."

mkdir -p "${BUILD_ROOT}"
rm -rf "${STAGING_DIR}"

echo "Creating staged copy in build directory..."
rsync -a --delete \
    --exclude ".git" \
    --exclude "build" \
    "${PROJECT_ROOT}/" "${STAGING_DIR}/"

VERSION="$(
    sed -nE 's/^[[:space:]]*\*[[:space:]]Version:[[:space:]]*([^[:space:]]+).*/\1/p' "${MAIN_PLUGIN_FILE}" \
    | head -n 1
)"

if [[ -z "${VERSION}" ]]; then
    echo "Error: Could not extract plugin version from elementrify.php"
    exit 1
fi

echo "Building assets with npm..."
(
    cd "${PROJECT_ROOT}"
    npm run build
)

echo "Refreshing staged copy with latest build output..."
rsync -a --delete \
    --exclude ".git" \
    --exclude "build" \
    "${PROJECT_ROOT}/" "${STAGING_DIR}/"

echo "Removing development files from staged copy..."
FILES_TO_REMOVE=(
    ".gitignore"
    ".cursorignore"
    "compose.yml"
    "composer.lock"
    "package-lock.json"
    "vendor"
    "node_modules"
    "wordpress"
    "configs"
)

for file_to_remove in "${FILES_TO_REMOVE[@]}"; do
    rm -rf "${STAGING_DIR}/${file_to_remove}"
done

ZIP_NAME="${PLUGIN_SLUG}-${VERSION}.zip"
ZIP_PATH="${BUILD_ROOT}/${ZIP_NAME}"

rm -f "${ZIP_PATH}"

echo "Creating zip archive ${ZIP_NAME}..."
(
    cd "${BUILD_ROOT}"
    zip -rq "${ZIP_NAME}" "${PLUGIN_SLUG}"
)

echo "Deployment package ready: ${ZIP_PATH}"
