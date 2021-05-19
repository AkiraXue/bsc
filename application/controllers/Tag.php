<?php
/**
 * Tag.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/25/21 2:05 AM
 */

use Lib\Constants;

use Service\Tag\TagService;
use Service\Tag\TagRelationService;


/**
 * Class Tag
 */
class Tag Extends MY_Controller
{
#region init
    public function __construct()
    {
        parent::__construct();
    }
#endregion

#region origin data
    /**
     * @throws Exception
     */
    public function save()
    {
        $data = $this->input->post(null, true);
        $result = TagService::getInstance()->save($data);
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
        $result = TagService::getInstance()->checkById($filter['id'], Constants::NO_VALUE);
        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function find()
    {
        $data = $this->input->post(null, true);
        $result = TagService::getInstance()->find($data);
        $this->_success($result);
    }
#endregion

#region relation

    /**
     * @throws Exception
     */
    public function saveRelation()
    {
        $data = $this->input->post(null, true);
        $result = TagRelationService::getInstance()->save($data);
        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function batchSaveRelation()
    {
        $data = $this->input->post(null, true);
        $result = TagRelationService::getInstance()->batchSave($data);
        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function getRelation()
    {
        $data = $this->input->post(null, true);
        $necessaryParamArr = ['id'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $data, true);
        $result = TagRelationService::getInstance()->checkById($filter['id'], Constants::NO_VALUE);
        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function findRelation()
    {
        $data = $this->input->post(null, true);
        $result = TagRelationService::getInstance()->find($data);
        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function findRelationInfo()
    {
        $data = $this->input->post(null, true);
        $result = TagRelationService::getInstance()->findRelationLeftJoinTag($data);
        $this->_success($result);
    }
#endregion
}