<?php
/**
 * UserInfoService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/16/21 2:53 PM
 */

namespace Service\User;

use Exception;
use Lib\Constants;

use Service\BaseTrait;
use Service\BaseService;

use Exception\Common\DBInvalidObjectException;

/**
 * Class UserInfoService
 * @package Service\User
 */
class UserInfoService extends BaseService
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
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }
#endregion

#region save user info
    /**
     * 保存用户信息
     *
     * @param array $params
     *
     * @return mixed
     * @throws Exception
     */
    public function save(array $params)
    {
        /** 1. check user info column */
        $necessaryParamArr = [
            'account_id', 'session_key', 'openid', 'unionid',
            'name', 'nickname', 'birthday', 'phone', 'mobile', 'avatar'
        ];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);

        $user = UserInfoService::getInstance()->checkByAccountId(
            $filter['account_id'], Constants::NO_VALUE
        );
        $condition = $filter;

        if ($user && isset($user['account_id'])) {
           $res = IoC()->User_model->_update(['account_id' => $filter['account_id']], $condition);
        } else {
            $condition['register_time'] = date("Y-m-d H:i:s");
            $res = IoC()->User_model->_insert($condition);
        }
        return $res;
    }

#endregion

#region base func
    /**
     * @param string $accountId
     * @param int $isThrowError
     *
     * @return array
     * @throws DBInvalidObjectException
     */
    public function checkByAccountId(string $accountId, $isThrowError=Constants::YES_VALUE)
    {
        $condition = [
            'account_id'     => $accountId,
        ];
        $userInfo = IoC()->User_model->get($condition);
        if (empty($userInfo)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('UserObj', 'account_id');
        }
        return $userInfo;
    }

    /**
     * @param string $openId
     * @param int $isThrowError
     *
     * @return array
     * @throws DBInvalidObjectException
     */
    public function checkByOpenId(string $openId, $isThrowError=Constants::YES_VALUE)
    {
        $condition = [
            'openid'     => $openId,
        ];
        $userInfo = IoC()->User_model->get($condition);
        if (empty($userInfo)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('UserObj', 'account_id');
        }
        return $userInfo;
    }
#endregion
}
