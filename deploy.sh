#!/usr/bin/env bash

set -euo pipefail

PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PLUGIN_SLUG="$(basename "${PROJECT_ROOT}")"
BUILD_ROOT="${PROJECT_ROOT}/build"
STAGING_DIR="${BUILD_ROOT}/${PLUGIN_SLUG}"
MAIN_PLUGIN_FILE="${PROJECT_ROOT}/addonext.php"

if [[ ! -f "${MAIN_PLUGIN_FILE}" ]]; then
    echo "Error: Could not find addonext.php in ${PROJECT_ROOT}"
    exit 1
fi

echo "Preparing deployment for ${PLUGIN_SLUG}..."

mkdir -p "${BUILD_ROOT}"
rm -rf "${STAGING_DIR}"

RSYNC_EXCLUDES=(
    --exclude ".git"
    --exclude "build"
    --exclude "/wordpress/"
    --exclude "/vendor/"
    --exclude "/deploy.sh"
    --exclude ".DS_Store"
    --exclude "/bin/"
    --exclude "/src/"
    --exclude "phpcs.xml.dist"
)

echo "Creating staged copy in build directory..."
rsync -a --delete \
    "${RSYNC_EXCLUDES[@]}" \
    "${PROJECT_ROOT}/" "${STAGING_DIR}/"

VERSION="$(
    sed -nE 's/^[[:space:]]*\*[[:space:]]Version:[[:space:]]*([^[:space:]]+).*/\1/p' "${MAIN_PLUGIN_FILE}" \
    | head -n 1
)"

if [[ -z "${VERSION}" ]]; then
    echo "Error: Could not extract plugin version from addonext.php"
    exit 1
fi

echo "Building assets with npm..."
(
    cd "${PROJECT_ROOT}"
    npm run build
)

echo "Refreshing staged copy with latest build output..."
rsync -a --delete \
    "${RSYNC_EXCLUDES[@]}" \
    "${PROJECT_ROOT}/" "${STAGING_DIR}/"

echo "Installing production-only Composer dependencies in staging..."
if ! command -v composer >/dev/null 2>&1; then
    echo "Error: composer is not installed or not in PATH (required for a lean vendor folder)."
    exit 1
fi
(
    cd "${STAGING_DIR}"
    composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist
)

echo "Removing non-runtime folders from vendor (tests, VCS metadata)..."
if [[ -d "${STAGING_DIR}/vendor" ]]; then
    while IFS= read -r -d '' dir_to_prune; do
        rm -rf "${dir_to_prune}"
    done < <(find "${STAGING_DIR}/vendor" -depth -type d \( \
        -name ".git" -o \
        -name ".github" -o \
        -name "tests" -o \
        -name "Tests" \
    \) -print0 2>/dev/null)
fi

echo "Removing development files from staged copy..."
FILES_TO_REMOVE=(
    ".gitignore"
    ".distignore"
    ".cursorignore"
    "compose.yml"
    "composer.lock"
    "package-lock.json"
    "node_modules"
    "configs"
    "phpcs.xml.dist"
)

for file_to_remove in "${FILES_TO_REMOVE[@]}"; do
    rm -rf "${STAGING_DIR}/${file_to_remove}"
done

find "${STAGING_DIR}" -name ".DS_Store" -delete 2>/dev/null || true

ZIP_NAME="${PLUGIN_SLUG}-${VERSION}.zip"
ZIP_PATH="${BUILD_ROOT}/${ZIP_NAME}"

rm -f "${ZIP_PATH}"

echo "Creating zip archive ${ZIP_NAME}..."
(
    cd "${BUILD_ROOT}"
    zip -rq "${ZIP_NAME}" "${PLUGIN_SLUG}"
)

rm -rf "${STAGING_DIR}"

echo "Deployment package ready: ${ZIP_PATH}"

FREEMIUS_MAX_BYTES=$((30 * 1024 * 1024))
case "$(uname -s)" in
    Darwin*) ZIP_BYTES=$(stat -f%z "${ZIP_PATH}" 2>/dev/null || echo 0) ;;
    *)       ZIP_BYTES=$(stat -c%s "${ZIP_PATH}" 2>/dev/null || echo 0) ;;
esac
if [[ "${ZIP_BYTES}" -gt "${FREEMIUS_MAX_BYTES}" ]]; then
    echo "Warning: ${ZIP_NAME} is $((ZIP_BYTES / 1024 / 1024))MB; Freemius upload limit is 30MB."
fi
