<?php

//https://github.com/ruckus/ruckusing-migrations/blob/master/config/database.inc.php

date_default_timezone_set('UTC');

$config = array(
    'db' => array(
        'development' => array(
            'type' => 'mysql',
            'host' => getenv('DATABASE_HOSTNAME'),
            'port' => getenv('DATABASE_PORT'),
            'database' => getenv('DATABASE_SCHEMA'),
            'user' => getenv('DATABASE_USERNAME'),
            'password' => getenv('DATABASE_PASSWORD'),
            'charset' => getenv('DATABASE_CHARSET')
        )
    ),
    'migrations_dir' => array('default' => getenv('PATH_APPLICATION_CODE') . '/migrations'),
    'db_dir' => RUCKUSING_WORKING_BASE . DIRECTORY_SEPARATOR . 'db',
    'log_dir' => getenv('PATH_APPLICATION_CODE_DYNAMIC_USER') . '/migration-logs',
    'ruckusing_base' => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'vendor/ruckusing/ruckusing-migrations'
);
return $config;
