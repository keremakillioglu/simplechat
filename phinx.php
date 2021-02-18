<?php

require __DIR__ . '/config/bootstrap.php';

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],

    'migration_base_class'=> 'App\Database\Migrations\Migration',

    'templates' => [
        'file' => 'src/Database/Migrations/MigrationStub.php',
    ],

    'environments' => [
        'default_migration_table' => 'migrations',
        'default' => [
            'adapter' => 'sqlite',
            'name' => './db/database',
            'suffix' => '.db',
        ]
    ],
    'version_order' => 'creation'
];
