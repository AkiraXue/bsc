<?php
/**
 * Group.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/16/21 11:17 PM
 */

use Lib\Constants;
use Service\Group\GroupService;
use Service\Group\GroupItemService;

/**
 * Class Group
 */
class Group extends MY_Controller
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

        $result = GroupService::getInstance()->save($data);

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
        $result = GroupService::getInstance()->checkGroupByCode($filter['code'], Constants::NO_VALUE);
        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function find()
    {
        $data = $this->input->post(null, true);
        $result = GroupService::getInstance()->find($data);
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

        $result = GroupItemService::getInstance()->save($data);

        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function batchSaveSchedule()
    {
        $data = $this->input->post(null, true);

        $result = GroupItemService::getInstance()->batchSave($data);

        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function findSchedule()
    {
        $data = $this->input->post(null, true);

        $result = GroupItemService::getInstance()->find($data);

        $this->_success($result);
    }
#region
}
