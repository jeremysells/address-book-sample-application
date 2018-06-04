#!/bin/bash
set -e

# Give MYSQL some time to fully start
sleep ${DATABASE_WAIT};

# Make sure the schema/database exists
if [ ! -z "${DATABASE_CREATE_SCHEMA}" ]; then
    mysql -u ${DATABASE_USERNAME} -h ${DATABASE_HOSTNAME} -p${DATABASE_PASSWORD} -e "CREATE DATABASE IF NOT EXISTS ${DATABASE_SCHEMA}"
else
    echo "Info: Skipped schema/database create"
fi

# Run migrations
php vendor/bin/ruckus.php db:migrate
