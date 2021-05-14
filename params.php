<?php
/**
 * params.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/14/21 1:52 PM
 */
require_once __DIR__ . '/vendor/autoload.php';

$enviroment == 'develop' && $enviroment = 'development';
define('ENV', $enviroment);
define('DB_HOST', '');
define('DB_PORT', 3306);
define('DB_USER', 'root');
define('DB_PASSWORD', '123456');
define('DB_DATABASE', 'test');
define('SESSION_CACHE', '');