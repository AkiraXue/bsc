<?php
/**
 * env.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/16/21 10:46 AM
 */

require_once dirname(__DIR__) . '/vendor/autoload.php';

$environment = 'develop';

$environment == 'develop' && $environment = 'development';

define('ENV', $environment);

/** db config */
define('DB_HOST', 'localhost');
define('DB_PORT', 3306);
define('DB_USER', 'root');
define('DB_PASSWORD', '123456');
define('DB_DATABASE', 'bsc');
define('SESSION_CACHE', '');

/** wechat config */
define('WECHAT_APP_ID', 'wx4006c81a2a18dbe0');
define('WECHAT_SECRET', 'e814e4f21bfb7487e2f8daee9418f525');
define('WECHAT_TOKEN', 'token');
define('AES_KEY', '');


/** upload config */
define('ARCHIVE_PATH',  'resource/upload/');
define('ALLOW_RESOURCE_TYPE', 'gif|jpg|png|jpeg|mp4');
define('MAX_BUFFER_LIMIT', '2048');

define('CDN_HOST', 'https://cdn.akiraxue.com');
