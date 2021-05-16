<?php
/**
 * Activity.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/15/21 1:32 PM
 */

use Lib\Constants;

use Service\Activity\ActivityService;
use Service\Activity\ActivityScheduleService;
use Service\Activity\ActivityParticipateRecordService;
use Service\Activity\ActivityParticipateScheduleService;

/**
 * Class Activity
 */
class Activity extends MY_Controller
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
    public function save()
    {
        $data = $this->input->post(null, true);

        $result = ActivityService::getInstance()->save($data);

        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function get()
    {
        $data = $this->input->post(null, true);
        $necessaryParamArr = ['code'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $data, true);
        $result = ActivityService::getInstance()->checkActivityByCode($filter['code'], Constants::NO_VALUE);
        $this->_success($result);
    }

    /**
     * 搜索
     */
    public function find()
    {
        $data = $this->input->post(null, true);
        $result = ActivityService::getInstance()->find($data);
        $this->_success($result);
    }
#endregion

#region activity schedule
    /**
     * @throws Exception
     */
    public function saveSchedule()
    {
        $data = $this->input->post(null, true);

        $result = ActivityScheduleService::getInstance()->save($data);

        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function batchSaveSchedule()
    {
        $data = $this->input->post(null, true);

        $result = ActivityScheduleService::getInstance()->batchSave($data);

        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function findSchedule()
    {
        $data = $this->input->post(null, true);

        $result = ActivityScheduleService::getInstance()->find($data);

        $this->_success($result);
    }
#region

#region activity participate schedule
    /**
     * @throws Exception
     */
    public function saveParticipateSchedule()
    {
        $data = $this->input->post(null, true);

        $result = ActivityParticipateScheduleService::getInstance()->save($data);

        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function getParticipateSchedule()
    {
        $data = $this->input->post(null, true);

        $result = ActivityParticipateScheduleService::getInstance()->getByAccountId($data['accountId']);

        $this->_success($result);
    }
#region

#region activity participate schedule
    /**
     * @throws Exception
     */
    public function saveParticipateRecord()
    {
        $data = $this->input->post(null, true);

        $result = ActivityParticipateRecordService::getInstance()->save($data);

        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function getParticipateRecordList()
    {
        $data = $this->input->post(null, true);

        $result = [];

        $this->_success($result);
    }
#region
}