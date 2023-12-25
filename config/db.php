<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=database:' . getenv('HOST_MACHINE_MYSQL_PORT') . ';dbname=' . getenv('MYSQL_DATABASE'),
    'username' => getenv('MYSQL_USER'),
    'password' => getenv('MYSQL_PASSWORD'),
    'charset' => 'utf8mb4',
    'tablePrefix' => '',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
