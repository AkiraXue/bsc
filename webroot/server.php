<?php
/**
 * server.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/17/21 2:15 AM
 */

use EasyWeChat\Factory;

require_once dirname(__DIR__) . '/etc/load_all.php';

$config = [
    'app_id' => WECHAT_APP_ID,
    'secret' => WECHAT_SECRET,
    'token'  => WECHAT_TOKEN,
    'response_type' => 'array',
];

$app = Factory::miniProgram($config);

$response = $app->server->serve();

// 将响应输出
$response->send();

exit;