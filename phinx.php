<?php
/**
 * phinx.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/14/21 1:52 PM
 */

@require_once  __DIR__ . '/params.php';

return [
    'paths' => [
        'migrations' => __DIR__ . '/db/migrations',
        'seeds' => __DIR__ . '/db/seeds',
    ],
    'environments' => [
        'default' => [
            'adapter' => 'mysql',
            'host' => DB_HOST,
            'name' => DB_DATABASE,
            'user' => DB_USER,
            'pass' => DB_PASSWORD,
            'port' => DB_PORT,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ],
    ],
];