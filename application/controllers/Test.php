<?php
/**
 * Test.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/15/21 11:59 AM
 */

use Service\PrizeContest\PrizeContestRecordItemService;

/**
 * Class Test
 */
class Test extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $list = PrizeContestRecordItemService::getInstance()->refreshProblemSet(7);
        $this->_success($list);
    }
}