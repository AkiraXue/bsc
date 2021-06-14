<?php
/**
 * AdminUserService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 6/14/21 10:26 PM
 */

namespace Service\Plugin;

use Exception;

use Lib\Helper;
use Lib\Constants;

use Service\BaseTrait;
use Service\BaseService;

use Exception\Common\DBInvalidObjectException;
use Exception\Common\ApiInvalidArgumentException;
use Service\Wechat\TokenService;

/**
 * Class AdminUserService
 * @package Service\Plugin
 */
class AdminUserService extends BaseService
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

#region base login
    /**
     * @param array $params
     *
     * @return array
     * @throws Exception
     */
    public function login(array $params)
    {
        /** 1. check user info column */
        $necessaryParamArr = [
            'name', 'password'
        ];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);

        /** 2. check old data */
        $oldCondition = [
            'name'  => $filter['name'],
        ];
        $oldData = IoC()->Admin_user_model->get($oldCondition);
        if (empty($oldData) || !isset($oldData['id'])) {
            throw new Exception('current old data not exist which name is => ' . $filter['name'], 3001);
        }

        /** 3. check admin user password */
        $currentPass = Helper::encryptPass($filter['password']);
        if ($currentPass != $oldData['password']) {
            throw new Exception('current pass not correct', 3001);
        }

        /** 4. check token expire_at => return old data */
        $accessToken = json_decode($oldData['token'], true);
        if ($accessToken['expired_at'] && $accessToken['expired_at'] >= time()) {
            return [
                'account_id'         => $oldData['account_id'],
                'name'               => $oldData['name'],
                'role'               => $oldData['role'],
                'description'        => $oldData['description'],
                'token'              => $accessToken
            ];
        }

        /** 5. make token => save session key */
        $claim = [
            'account_id'         => $oldData['account_id'],
            'name'               => $oldData['name'],
            'role'               => $oldData['role'],
            'description'        => $oldData['description']
        ];
        $accessToken = TokenService::makeToken($claim, $oldData['account_id']);

        /** 6. update admin user item */
        $condition = ['account_id' => $oldData['account_id']];
        $update = [
            'login_time' => date('Y-m-d H:i:s'),
            'token'      => json_encode($accessToken, JSON_FORCE_OBJECT)
        ];
        IoC()->Admin_user_model->_update($condition, $update);

        /** 7. return origin data */
        return [
            'account_id'         => $oldData['account_id'],
            'name'               => $oldData['name'],
            'role'               => $oldData['role'],
            'description'        => $oldData['description'],
            'token'              => $accessToken
        ];
    }

    /**
     * @param string $accountId
     *
     * @return mixed
     * @throws Exception
     */
    public function logout(string $accountId)
    {
        /** 1. check old account_id */
        $adminUser = $this->checkByAccountId($accountId);
        if (empty($adminUser['token'])) {
            return true;
        }
        $accessToken = json_decode($adminUser['token'], true);

        /** toDo: 2. check token */
        $token = TokenService::parseToken($accessToken['access_token']);

        /** 3. reset token data */
        $condition = ['account_id' => $accountId];
        $update = ['token' => ''];
        IoC()->Admin_user_model->_update($condition, $update);

        return true;
    }
#endregion

#region admin user
    /**
     * @param array $params
     *
     * @return array
     * @throws Exception
     */
    public function save(array $params)
    {
        /** 1. check user info column */
        $necessaryParamArr = [
            'name', 'role', 'description'
        ];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);
        $condition = $filter;


        /** 2. check old data */
        $oldCondition = [
            'name'  => $filter['name'],
        ];
        $oldData = IoC()->Admin_user_model->get($oldCondition);
        if ($oldData && isset($oldData['id'])) {
           throw new Exception('current old data has exist which name is => ' . $filter['name'], 3001);
        }

        /** 3. account_id && update */
        if (isset($params['account_id'])) {
            AdminUserService::getInstance()->checkByAccountId(
                $params['account_id'], Constants::NO_VALUE
            );
            $res = IoC()->Admin_user_model->_update(['account_id' => $filter['account_id']], $condition);
            return $res;
        }

        /** 4. account_id && add */
        if (empty($params['password'])) {
            throw new ApiInvalidArgumentException('password');
        }
        $condition['account_id'] = Helper::gen_uuid();
        $condition['password'] = Helper::encryptPass($params['password']);
        $condition['register_time'] = date("Y-m-d H:i:s");
        $res = IoC()->Admin_user_model->_insert($condition);
        return $res;
    }

    /**
     * @param array $params
     * @return array
     */
    public function find(array $params)
    {
        $condition = [];

        empty($params['name']) || $condition['nameLike'] = $params['name'];
        empty($params['account_id']) || $condition['account_id'] = $params['account_id'];
        empty($params['state']) || $condition['state'] = $params['state'];

        $page = $params['page'];
        $limit = $params['limit'];
        $page = !empty($page) ? intval($page) : 1;
        $limit = !empty($limit) ? intval($limit) : 10;

        $data =  IoC()->Admin_user_model->find($condition, $count, $page, $limit);
        $totalPage = ceil($count / $limit);
        $totalPage = $totalPage ? $totalPage : 1;
        return [
            'list'       => $data,
            'total'      => $count,
            'total_page' => $totalPage
        ];
    }

    /**
     * @param array   $params
     * @param integer $isDelete
     *
     * @return array
     * @throws Exception
     */
    public function toggle(array $params, $isDelete=Constants::NO_VALUE)
    {
        /** 1. check base params */
        $necessaryParamArr = ['account_id'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);

        /** 2. check admin_user */
        $adminUser = $this->checkByAccountId($filter['account_id']);

        $state = Constants::NO_VALUE;
        if ($isDelete == Constants::NO_VALUE) {
            $state = $adminUser['state'] == Constants::YES_VALUE ? Constants::NO_VALUE : Constants::YES_VALUE;
        }

        return IoC()->Admin_user_model->_update(['account_id' => $filter['account_id']], ['state' => $state]);
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
    public function checkByAccountId(
        string $accountId,
        $isThrowError=Constants::YES_VALUE
    ) {
        $condition = [
            'account_id'     => $accountId,
            // 'state'          => Constants::YES_VALUE,
        ];
        $adminUser = IoC()->Admin_user_model->get($condition);
        if (empty($adminUser)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('AdminUserObj', 'account_id');
        }
        return $adminUser;
    }
#endregion
}