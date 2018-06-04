#!/bin/bash
set -e

# Go to the working directory and run the migrations etc and once done run PHP FPM
cd ${PATH_APPLICATION_CODE} \
    && bin/siteRun.sh \
    && /usr/sbin/php-fpm${PHP_VERSION} \
        --nodaemonize \
        -c /etc/php/${PHP_VERSION}/fpm/php.ini \
        --fpm-config /etc/php/${PHP_VERSION}/fpm/php-fpm.conf
