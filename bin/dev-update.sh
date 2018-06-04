#!/usr/bin/env bash
set -e

cd ${PATH_APPLICATION_CODE}

# Composer Update
composer update

# Update Migrations
php -f vendor/bin/ruckus.php db:migrate

# Yarn Update
cd ${PATH_APPLICATION_CODE}/public_html &&  && yarn upgrade