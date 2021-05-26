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

use Service\Asset\AssetService;
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
    public function find(array $params)
    {
        $condition = [];

        empty($params['account_id']) || $condition['account_id'] = $params['account_id'];
        empty($params['openid']) || $condition['openid'] = $params['openid'];

        empty($params['mobile']) || $condition['mobile'] = $params['mobile'];
        empty($params['group_code']) || $condition['group_code'] = $params['group_code'];

        empty($params['name']) || $condition['nameLike'] = $params['name'];
        empty($params['nickname']) || $condition['nicknameLike'] = $params['nickname'];

        $page = $params['page'];
        $limit = $params['limit'];
        $page = !empty($page) ? intval($page) : 1;
        $limit = !empty($limit) ? intval($limit) : 10;

        $data =  IoC()->User_model->find($condition, $count, $page, $limit);
        $totalPage = ceil($count / $limit);
        $totalPage = $totalPage ? $totalPage : 1;
        return [
            'list'       => $data,
            'total'      => $count,
            'total_page' => $totalPage
        ];
    }

    /**
     * @param array $params
     * @return int
     * @throws Exception
     */
    public function toggle(array $params)
    {
        /** 1. check user info column */
        $necessaryParamArr = ['account_id',];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);

        /** toggle user state */
        $user = UserInfoService::getInstance()->checkByAccountId($filter['account_id']);
        $condition = [
            'state' => $user['state'] == Constants::YES_VALUE ? Constants::NO_VALUE : Constants::YES_VALUE
        ];
        return IoC()->User_model->_update(['account_id' => $filter['account_id']], $condition);
    }

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
     * @throws Exception
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
        $asset = AssetService::getInstance()->checkByUniqueCode($accountId, null, Constants::NO_VALUE);
        $userInfo['asset_num'] = ($asset && $asset['remaining']) ? $asset['remaining'] : '0.00';
        setlocale(LC_TIME, 'en_US');
        $userInfo['register_time'] = gmstrftime("%d %b %Y", strtotime($userInfo['register_time']));
        return $userInfo;
    }

    /**
     * @param string $openId
     * @param int $isThrowError
     *
     * @return array
     * @throws Exception
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
