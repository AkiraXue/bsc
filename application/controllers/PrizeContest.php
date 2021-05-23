<?php
/**
 * PrizeContest.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/22/21 8:24 PM
 */

use Lib\Constants;
use Service\PrizeContest\PrizeContestRecordItemService;
use Service\PrizeContest\PrizeContestRecordService;
use Service\PrizeContest\PrizeContestScheduleService;
use Service\PrizeContest\PrizeContestService;

/**
 * Class PrizeContest
 */
class PrizeContest extends MY_Controller
{
#region init
    public function __construct()
    {
        parent::__construct();
    }
#endregion

#region origin func
    /**
     * @throws Exception
     */
    public function save()
    {
        $data = $this->input->post(null, true);
        $result = PrizeContestService::getInstance()->save($data);
        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function get()
    {
        $data = $this->input->post(null, true);
        $result = PrizeContestService::getInstance()->getCurrentConfig();
        $this->_success($result);
    }

    /**
     * 搜索
     */
    public function find()
    {
        $data = $this->input->post(null, true);
        $result = PrizeContestService::getInstance()->find($data);
        $this->_success($result);
    }

#endregion

#region schedule
    /**
     * @throws Exception
     */
    public function saveSchedule()
    {
        $data = $this->input->post(null, true);
        $result = PrizeContestScheduleService::getInstance()->save($data);
        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function batchSaveSchedule()
    {
        $data = $this->input->post(null, true);
        $result = PrizeContestScheduleService::getInstance()->batchSave($data);
        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function getSchedule()
    {
        $data = $this->input->post(null, true);
        $necessaryParamArr = ['id'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $data, true);
        $result = PrizeContestScheduleService::getInstance()->checkScheduleById($filter['id']);
        $this->_success($result);
    }

    /**
     * 搜索
     */
    public function findSchedule()
    {
        $data = $this->input->post(null, true);
        $result = PrizeContestScheduleService::getInstance()->find($data);
        $this->_success($result);
    }
#endregion

#region record
    /**
     * @throws Exception
     */
    public function saveRecord()
    {
        $data = $this->input->post(null, true);
        $result = PrizeContestRecordService::getInstance()->save($data);
        $this->_success($result);
    }


    /**
     * @throws Exception
     */
    public function getRecordById()
    {
        $data = $this->input->post(null, true);
        $necessaryParamArr = ['id'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $data, true);
        $result = PrizeContestRecordService::getInstance()->checkPrizeContentRecordById($filter['id']);
        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function getRecord()
    {
        $data = $this->input->post(null, true);
        $necessaryParamArr = ['account_id', 'date'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $data, true);
        $result = PrizeContestRecordService::getInstance()->checkPrizeContestRecord(
            $filter, Constants::NO_VALUE
        );
        $this->_success($result);
    }

    /**
     * 搜索
     */
    public function findRecord()
    {
        $data = $this->input->post(null, true);
        $result = PrizeContestRecordService::getInstance()->find($data);
        $this->_success($result);
    }
#endregion

}