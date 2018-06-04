#!/usr/bin/env bash
set -e

cd ${PATH_APPLICATION_CODE}

# Composer Install
composer install

# Make sure the schema/database exists
if [ ! -z "${DATABASE_CREATE_SCHEMA}" ]; then
    mysql -u ${DATABASE_USERNAME} -h ${DATABASE_HOSTNAME} -p${DATABASE_PASSWORD} -e "CREATE DATABASE IF NOT EXISTS ${DATABASE_SCHEMA}"
else
    echo "Info: Skipped schema/database create"
fi

# Run migrations
php -f vendor/bin/ruckus.php db:migrate

# Yarn Install
cd ${PATH_APPLICATION_CODE}/public_html && yarn install