<?php
/**
 * Common.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/22/21 11:33 AM
 */

use Service\Common\LoginService;

/**
 * Class Common
 */
class Common extends MY_Controller
{
    public $isNeedLogin = 2;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public function login()
    {
        $data = $this->input->post(null, true);
        $result = LoginService::getInstance()->wxAppLogin($data);
        $this->_success($result);
    }
}
