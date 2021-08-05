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

/**
return [
'DB_TYPE' => 'sqlsrv',         // 数据库类型
'DB_HOST' => '192.168.91.212', // 服务器地址
'DB_NAME' => 'APP_NHO',          // 数据库名
'DB_USER' => 'AppNhouser',       // 用户名
'DB_PWD' => 'Boston2021',          // 密码
'DB_PORT' => '16000\Prod',        // 端口
'DB_PREFIX' => '',    // 数据库表前缀
];
 */

/** db config */
define('DB_HOST', '192.168.91.212');
define('DB_PORT', '16000\Prod');
define('DB_USER', 'AppNhouser');
define('DB_PASSWORD', 'Boston2021');
define('DB_DATABASE', 'APP_NHO');
define('SESSION_CACHE', '');

/** wechat config */
define('WECHAT_APP_ID', 'wx4006c81a2a18dbe0');
define('WECHAT_SECRET', 'e814e4f21bfb7487e2f8daee9418f525');
define('WECHAT_TOKEN', 'token');
define('AES_KEY', '');

/**
 http://nho.bscwin.com
 http://nho.bscwin.com/resource/
 http://nho.bscwin.com/backend
 */

/** upload config */
define('ARCHIVE_PATH',  '/upload/');
define('ALLOW_RESOURCE_TYPE', 'gif|jpg|png|jpeg|mp4');
define('MAX_BUFFER_LIMIT', '512000');

define('CDN_HOST', 'https://nho.bscwin.com/resource');
//define('CDN_HOST', 'https://cdn.bsc.com');
//define('CDN_HOST', 'https://cdn.sixtyden.com');

/** ali oss config */
define('ALI_OSS_ACCESS_KEY_ID', 'LTAI5tJo9ujyKrPktDmB194r');
define('ALI_OSS_ACCESS_KEY_SECRET', 'yeNSgjoKbusWYSmTfmV1s4EZomHpEc');
define('ALI_OSS_ENDPOINT', 'https://oss-cn-shanghai.aliyuncs.com/');
define('ALI_OSS_BUCKET', 'bageng-bsc-namespace2021');
define('ALI_OSS_DIR', '/bsc');
define('ALI_OSS_ROLE', 'acs:ram::1472580472326892:role/aliyunserviceroleforsddp');


