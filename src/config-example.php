<?php

return [
    'db' => [
        'dsn' => 'sqlite:'.dirname(__FILE__, 2).DIRECTORY_SEPARATOR.'store'.DIRECTORY_SEPARATOR.'db'.DIRECTORY_SEPARATOR.'sqlite.db',
        'user' => null,
        'password' => null,
    ],
    'viewDirectory' => dirname(__FILE__, 1) . DIRECTORY_SEPARATOR.'View',
];
