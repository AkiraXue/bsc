<?php

/**
 * Wechat.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/17/21 2:12 AM
 */

use EasyWeChat\Factory;

class Wechat extends MY_Controller
{
    public $config;

    public function __construct()
    {
        parent::__construct();

        $this->config = [
            'app_id' => WECHAT_APP_ID,
            'secret' => WECHAT_SECRET,
            'token'  => WECHAT_TOKEN,
            'response_type' => 'array',
        ];
    }

    public function index()
    {
        $app = Factory::officialAccount($this->config);
    }

    public function test()
    {
        // 公众号
        $app = Factory::officialAccount($this->config);

        // 小程序
        $app = Factory::miniProgram($this->config);

        // 开放平台
        $app = Factory::openPlatform($this->config);

        // 企业微信
        $app = Factory::work($this->config);

        // 企业微信开放平台
        $app = Factory::openWork($this->config);

        // 微信支付
        $app = Factory::payment($this->config);
    }
}