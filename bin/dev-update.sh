#!/usr/bin/env bash
set -e

# Update Compose
composer update

# Update Packages
cd public_html && yarn upgrade

# Update Migrations
php vendor/bin/ruckus.php db:migrate
