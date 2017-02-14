<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PDO Fetch Style
    |--------------------------------------------------------------------------
    |
    | By default, database results will be returned as instances of the PHP
    | stdClass object; however, you may desire to retrieve records in an
    | array format for simplicity. Here you can tweak the fetch style.
    |
    */

    'fetch' => PDO::FETCH_CLASS,

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
        ],

        'mysql' => [
//            "read" => [
//                'host' => '192.168.1.232',
//                'username' => 'root',
//                'password' => 'test_123456'
//            ],
//            "write" => [
//                'host' => env('DB_HOST'),
//                'username' => env('DB_USERNAME'),
//                'password' => env('DB_PASSWORD'),
//            ],
            'host' => env('DB_HOST'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'driver' => 'mysql',
            'port' => env('DB_PORT'),
            'database' => env('DB_DATABASE'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ],

        'yf_new_console' => [
            'host' => env('YF_NEW_CONSOLE_DB_HOST'),
            'username' => env('YF_NEW_CONSOLE_DB_USERNAME'),
            'password' => env('YF_NEW_CONSOLE_DB_PASSWORD'),
            'driver' => 'mysql',
            'port' => env('YF_NEW_CONSOLE_DB_PORT'),
            'database' => env('YF_NEW_CONSOLE_DB_DATABASE'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
        ],

        'mongodb' => [
            'driver'   => 'mongodb',
            'host'     => env('AAS_MONGO_HOST'),
            'port'     => env('AAS_MONGO_PORT'),
            'database' => env('AAS_MONGO_DATABASE'),
            'username' => env('AAS_MONGO_USERNAME', ''),
            'password' => env('AAS_MONGO_PASSWORD', ''),
            'options' => [
                'database' => 'admin' // sets the authentication database required by mongo 3
            ]
        ]

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'cluster' => false,

        'default' => [
            'host' => env('REDIS_HOST', 'localhost'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => 0,
        ],

        'aas_redis' => [
            'host' => env('AAS_REDIS_HOST', 'localhost'),
            'password' => env('AAS_REDIS_PASSWORD', null),
            'port' => env('AAS_REDIS_PORT', 6379),
            'database' => env('AAS_REDIS_DB', 0)
        ]

    ],

];
