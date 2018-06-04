#!/usr/bin/env bash
set -e

# Composer Install
composer install

# Yarn Install
cd public_html && yarn install

# Run Migrations
php vendor/bin/ruckus.php db:migrate
