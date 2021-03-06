<?php
/**
 * Topic.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/17/21 12:47 AM
 */

use Lib\Constants;

use Service\Knowledge\TopicServices;

/**
 * Class Topic
 */
class Topic extends MY_Controller
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
    public function delete()
    {
        $data = $this->input->post(null, true);
        $result = TopicServices::getInstance()->delete($data);
        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function save()
    {
        $data = $this->input->post(null, true);
        $result = TopicServices::getInstance()->save($data);
        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function get()
    {
        $data = $this->input->post(null, true);
        $necessaryParamArr = ['id'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $data, true);
        $result = TopicServices::getInstance()->checkById($filter['id'], Constants::NO_VALUE);
        $this->_success($result);
    }

    /**
     * 搜索
     */
    public function find()
    {
        $data = $this->input->post(null, true);
        $result = TopicServices::getInstance()->find($data);
        $this->_success($result);
    }
#endregion

}