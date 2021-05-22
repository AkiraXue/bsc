<?php
/**
 * UserInfo.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/22/21 10:49 AM
 */

use Service\User\UserInfoService;

class UserInfo extends MY_Controller
{
#region  init
    public function __construct()
    {
        parent::__construct();
    }
#endregion

#region common
    /**
     * @throws Exception
     */
    public function get()
    {
        $userInfo = UserInfoService::getInstance()->checkByAccountId($this->accountId);

        $this->_success($userInfo);
    }

    /**
     * 搜索
     */
    public function find()
    {
        $data = $this->input->post(null, true);
        $result = UserInfoService::getInstance()->find($data);
        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function toggle()
    {
        $data = $this->input->post(null, true);
        $result = UserInfoService::getInstance()->toggle($data);
        $this->_success($result);
    }
#endregion

}