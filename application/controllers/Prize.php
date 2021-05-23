<?php
/**
 * Prize.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/23/21 9:52 AM
 */

use Service\PrizeContest\PrizeService;


class Prize extends MY_Controller
{
#region init
    public function __construct()
    {
        parent::__construct();
    }
#endregion

#region func
    /**
     * @throws Exception
     */
    public function getConfig()
    {
        $result = [
            'prize_num'   => 1,
            'prize_total' => 3,
            'is_prize_exsit' => 1,
        ];

        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function initConfig()
    {
        $date = date('Y-m-d');
        $accountId = $this->accountId;
        $result = PrizeService::getInstance()->init($accountId, $date);
        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function getProblem()
    {
        $data = $this->input->post(null, true);
        $data['account_id'] = $data['account_id'] ?:$this->accountId;
        $result = PrizeService::getInstance()->getProblem($data);
        $this->_success($result);
    }

    public function submit()
    {
        $data = $this->input->post(null, true);
        $result = PrizeService::getInstance()->answer($data);
        $this->_success($result);
    }
}