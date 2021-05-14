<?php
/**
 * params.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/14/21 1:52 PM
 */
require_once __DIR__ . '/vendor/autoload.php';

$environment == 'develop' && $environment = 'development';
define('ENV', $environment);
define('DB_HOST', 'localhost');
define('DB_PORT', 3306);
define('DB_USER', 'root');
define('DB_PASSWORD', '123456');
define('DB_DATABASE', 'bsc');
define('SESSION_CACHE', '');