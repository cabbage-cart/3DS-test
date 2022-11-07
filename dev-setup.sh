#!/usr/bin/env bash

set -e

echo "Setting up 3DS test proj"

echo "checking dependencies"

if command -v php  &> /dev/null; then
    php_installed=true
fi

if command -v composer &> /dev/null; then
    composer_installed=true
fi


PHP_VERSION=$(cat .php-version)
if [[ $php_installed ]]; then
  echo "Php installed"
  echo "php: $php_installed"
else
  echo "No php version installed"
  exit 1
fi

if [[ $composer_installed ]]; then
  echo "Composer installed"
  echo "Composer: $php_installed"
else
  echo "No Composer version installed"
  exit 1
fi





