<?php
/**
 * WechatHelper.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/22/21 11:03 AM
 */

namespace Lib;

/**
 * Class WechatHelper
 * @package Lib
 */
class WechatHelper
{
    const URL_AUTH_API = 'https://api.weixin.qq.com/sns/jscode2session?%s';

    /**
     * @param $appid
     * @param $secret
     * @param $jsCode
     *
     * @return mixed
     */
    public static function refreshSessionKey($appid, $secret, $jsCode)
    {
        $grantType='authorization_code';
        $params = [
            'appid'     => $appid,
            'secret'    => $secret,
            'js_code'   => $jsCode,
            'grant_type' => $grantType
        ];
        $url = sprintf(self::URL_AUTH_API, http_build_query($params));
        $resultJson = Http::request($url, '', 'GET');
        $result = json_decode($resultJson, true);
        if ($result['errcode'] && $result['errcode'] > 0) {
            return false;
        }
        return $result;

    }
}