<?php
/**
 * Punch.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/16/21 3:16 PM
 */

use Lib\Constants;

use Service\Punch\PunchService;

/**
 * Class Punch
 */
class Punch extends MY_Controller
{
#region init
    public function __construct()
    {
        parent::__construct();
    }
#endregion

#punch punch info
    /**
     * @throws Exception
     */
    public function getConfig()
    {
        $data = $this->input->post(null, true);
        $date = date('Y-m-d');
        $result = PunchService::getInstance()->getConfig($this->accountId);
        $this->_success($result);
    }


    public function record()
    {

    }

    public function knowledge()
    {

    }

#endregion
}
