<?php
/**
 * AdminUser.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 6/14/21 10:19 PM
 */

use Lib\Constants;

use Service\Plugin\AdminUserService;

/**
 * Class AdminUser
 */
class AdminUser extends MY_Controller
{
#region init
    public function __construct()
    {
        parent::__construct();
    }
#endregion

#region common
    /**
     * @throws Exception
     */
    public function login()
    {
        $data = $this->input->post(null, true);
        $result = AdminUserService::getInstance()->login($data);
        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function logout()
    {
        $data = $this->input->post(null, true);
        $result = AdminUserService::getInstance()->logout($this->accountId);
        $this->_success($result);

    }
#endregion

#region func
    /**
     * @throws Exception
     */
    public function save()
    {
        $data = $this->input->post(null, true);
        $result = AdminUserService::getInstance()->save($data);
        $this->_success($result);
    }

    /**
     * 搜索
     */
    public function find()
    {
        $data = $this->input->post(null, true);
        $result = AdminUserService::getInstance()->find($data);
        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function get()
    {
        $data = $this->input->post(null, true);
        $necessaryParamArr = ['account_id'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $data, true);
        $result = AdminUserService::getInstance()->checkByAccountId($filter['account_id'], Constants::NO_VALUE);
        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function getCurrentUserInfo()
    {
        $data = $this->input->post(null, true);
        $result = AdminUserService::getInstance()->checkByAccountId($this->accountId, Constants::NO_VALUE);
        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function delete()
    {
        $data = $this->input->post(null, true);
        $result = AdminUserService::getInstance()->toggle($data, Constants::YES_VALUE);
        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function toggle()
    {
        $data = $this->input->post(null, true);
        $result = AdminUserService::getInstance()->toggle($data);
        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function validateUserId()
    {
        $data = $this->input->post(null, true);
        $necessaryParamArr = ['account_id'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $data, true);
        $result = AdminUserService::getInstance()->checkByAccountId($filter['account_id'], Constants::NO_VALUE);
        $this->_success($result);
    }

#endregion

}
