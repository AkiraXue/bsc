<?php
/**
 * Knowledge.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/16/21 11:46 PM
 */

use Lib\Constants;

use Service\Knowledge\KnowledgeService;

/**
 * Class Knowledge
 */
class Knowledge extends MY_Controller
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

        $result = KnowledgeService::getInstance()->save($data);

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
        $result = KnowledgeService::getInstance()->checkById($filter['id'], Constants::NO_VALUE);
        $this->_success($result);
    }

    /**
     * 搜索
     */
    public function find()
    {
        $data = $this->input->post(null, true);
        $result = KnowledgeService::getInstance()->find($data);
        $this->_success($result);
    }
#endregion

}