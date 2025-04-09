#!/bin/bash

cd "$(dirname "$0")"

set -e

if [ ! -d "vendor" ]; then
  echo "🔧 'vendor/' not found. Running composer install..."
  composer install
fi

PHP_PATHS=$(update-alternatives --list php 2>/dev/null)

if [ -z "$PHP_PATHS" ]; then
  echo "No PHP alternatives found via update-alternatives."
  echo "Please install PHP 8.4 or newer and register it with update-alternatives."
  echo "   Example: sudo apt install php8.4"
  echo "            sudo update-alternatives --install /usr/bin/php php /usr/bin/php8.4 84"
  exit 1
fi

BEST_PHP=""
BEST_VERSION="0.0"

for php_path in $PHP_PATHS; do
  if [ ! -x "$php_path" ]; then
    continue
  fi

  VERSION=$("$php_path" -r 'echo PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION;' 2>/dev/null)

  if [[ "$VERSION" =~ ^[0-9]+\.[0-9]+$ ]]; then
    MAJOR=${VERSION%%.*}
    MINOR=${VERSION#*.}

    if (( MAJOR > 8 || (MAJOR == 8 && MINOR >= 4) )); then
      if [[ "$VERSION" > "$BEST_VERSION" ]]; then
        BEST_VERSION="$VERSION"
        BEST_PHP="$php_path"
      fi
    fi
  fi
done

if [ -n "$BEST_PHP" ]; then
  echo "Using PHP $BEST_VERSION from '$BEST_PHP'"
  "$BEST_PHP" bin/custom-filter.php "$@"
else
  echo "No suitable PHP version (≥ 8.4) found via update-alternatives."
  echo "Please install PHP 8.4 or newer."
  echo "   Example (Debian/Ubuntu):"
  echo "     sudo apt install php8.4"
  echo "     sudo update-alternatives --install /usr/bin/php php /usr/bin/php8.4 84"
  exit 1
fi
