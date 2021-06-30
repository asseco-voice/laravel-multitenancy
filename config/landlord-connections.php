<?php

return [

    /**
     * These are standard Laravel connections adapted to support different
     * landlord connections. Other than different ENV values and keys
     * prepended with 'landlord-', nothing is different.
     */
    'landlord-sqlite' => [
        'driver'                  => 'sqlite',
        'url'                     => env('LANDLORD_URL'),
        'database'                => env('LANDLORD_DATABASE', database_path('database.sqlite')),
        'prefix'                  => '',
        'foreign_key_constraints' => env('LANDLORD_FOREIGN_KEYS', true),
    ],

    'landlord-mysql' => [
        'driver'         => 'mysql',
        'url'            => env('LANDLORD_URL'),
        'host'           => env('LANDLORD_HOST', '127.0.0.1'),
        'port'           => env('LANDLORD_PORT', '3306'),
        'database'       => env('LANDLORD_DATABASE', 'forge'),
        'username'       => env('LANDLORD_USERNAME', 'forge'),
        'password'       => env('LANDLORD_PASSWORD', ''),
        'unix_socket'    => env('LANDLORD_SOCKET', ''),
        'charset'        => 'utf8mb4',
        'collation'      => 'utf8mb4_unicode_ci',
        'prefix'         => '',
        'prefix_indexes' => true,
        'strict'         => true,
        'engine'         => null,
        'options'        => extension_loaded('pdo_mysql') ? array_filter([
            PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
        ]) : [],
    ],

    'landlord-pgsql' => [
        'driver'         => 'pgsql',
        'url'            => env('LANDLORD_URL'),
        'host'           => env('LANDLORD_HOST', '127.0.0.1'),
        'port'           => env('LANDLORD_PORT', '5432'),
        'database'       => env('LANDLORD_DATABASE', 'forge'),
        'username'       => env('LANDLORD_USERNAME', 'forge'),
        'password'       => env('LANDLORD_PASSWORD', ''),
        'charset'        => 'utf8',
        'prefix'         => '',
        'prefix_indexes' => true,
        'schema'         => 'public',
        'sslmode'        => 'prefer',
    ],

    'landlord-sqlsrv' => [
        'driver'         => 'sqlsrv',
        'url'            => env('LANDLORD_URL'),
        'host'           => env('LANDLORD_HOST', 'localhost'),
        'port'           => env('LANDLORD_PORT', '1433'),
        'database'       => env('LANDLORD_DATABASE', 'forge'),
        'username'       => env('LANDLORD_USERNAME', 'forge'),
        'password'       => env('LANDLORD_PASSWORD', ''),
        'charset'        => 'utf8',
        'prefix'         => '',
        'prefix_indexes' => true,
    ],
];
