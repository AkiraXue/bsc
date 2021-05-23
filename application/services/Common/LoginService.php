<?php
/**
 * LoginService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/22/21 11:47 AM
 */

namespace Service\Common;

use Exception;

use Lib\Constants;
use Lib\Helper;
use Lib\WechatHelper;
use Service\BaseTrait;
use Service\BaseService;
use Service\User\UserInfoService;
use Service\Wechat\TokenService;

/**
 * Class LoginService
 * @package Service\Common
 */
class LoginService extends BaseService
{
    use BaseTrait;

#region initial info
    public static $instance;

    public function __construct()
    {
        parent::__construct();
    }

    public static function getInstance()
    {
        if (!self::$instance instanceof self){
            self::$instance = new self() ;
        }
        return self::$instance;
    }
#endregion

#region base
    /**
     * @param array $params
     *
     * @return mixed
     * @throws Exception
     */
    public function wxAppLogin($params)
    {
        /** 1. check params */
        $necessaryParamArr = ['code', 'user_info'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);

        /** 2. refresh session key */
        $wechatSessionInfo = WechatHelper::refreshSessionKey(
            WECHAT_APP_ID, WECHAT_SECRET, $filter['code']
        );
        if (!$wechatSessionInfo || empty($wechatSessionInfo['openid'])) {
            throw new Exception('获取微信信息错误', 3001);
        }
//        $wechatSessionInfo['openid'] = 'openid 2222';
//        $wechatSessionInfo['session_key'] = 'session_key 3333';
//        $wechatSessionInfo['unionid'] = 'unionid 4444';


        $openid = $wechatSessionInfo['openid'];
        $session_key = $wechatSessionInfo['session_key'];
        $unionid = $wechatSessionInfo['unionid']?:'';

        /** 3. check old user data record */
        $user = UserInfoService::getInstance()->checkByOpenId($openid, Constants::NO_VALUE);
        $userExist = Constants::NO_VALUE;
        if ($user && isset($user['account_id'])) {
            $userExist = Constants::YES_VALUE;
        }
        $accountId = ($userExist == Constants::YES_VALUE) ? $user['account_id'] : Helper::gen_uuid();

        /** 4. save user info */
        $data['account_id'] = $accountId;
        $data['session_key'] = $session_key;
        $data['openid'] = $openid;
        $data['unionid'] = $unionid;
        $data['name'] = $filter['user_info']["name"]?:'';
        $data['nickname'] = $filter['user_info']["nickname"]?:'';
        $data['birthday'] = $filter['user_info']["birthday"]?:'';
        $data['phone'] = $filter['user_info']["phone"]?:'';
        $data['mobile'] = $filter['user_info']["mobile"]?:'';
        $data['avatar'] = $filter['user_info']["avatar_url"]?:'';
        UserInfoService::getInstance()->save($data);

        /** 5. make token */
        $claim = [
            'account_id'         => $accountId,
            'openid'             => $openid,
            'session_key'        => $session_key,
        ];
        $accessToken =  TokenService::makeToken($claim, $accountId);
        $accessToken['token_type'] = 'bearer';
        $accessToken['session_key'] = $session_key;
        $accessToken['is_exist'] = $userExist;
        $accessToken['account_id'] = $accountId;

        return $accessToken;
    }
}
#endregion