<?php
/**
 * TagRelationService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/7/21 2:53 PM
 */

namespace Service\Tag;

use Exception;

use Lib\Constants;

use Service\BaseTrait;
use Service\BaseService;

use Exception\Common\DBInvalidObjectException;
use Exception\Common\ApiInvalidArgumentException;

/**
 * Class TagRelationService
 * @package Service
 */
class TagRelationService extends BaseService
{
    use BaseTrait;

#region init
    const USER_TAG_LENGTH_LIMIT = 20;

    public static $instance;

    public static function getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        parent::__construct();
    }
#endregion

#region func save

    /**
     * 更新tag relation
     *
     * @param array $params
     *
     * @return int
     * @throws Exception
     */
    public function save(array $params)
    {
        $filter = $this->checkBaseTagRelationEntryApiArgument($params);

        if ($params['act'] === Constants::ACT_ADD) {
            $addCondition = [
                'unique_code'   =>  $filter['unique_code'],
                'type'          =>  $filter['type'],
                'tag_id'        =>  $filter['tag_id'],
                'desc'          =>  $filter['desc'],
            ];
            $id = IoC()->Tag_relation_model->_insert($addCondition);
            IoC()->Tag_relation_model->_update(['id' => $id], ['sort' => $id]);
        } else {
            $id = intval($params['id']);
            if (empty($id)) {
                throw new ApiInvalidArgumentException('id');
            }
            $oldTagRelation = IoC()->Tag_relation_model->getById($id);
            if (empty($oldTagRelation)) {
                throw new DBInvalidObjectException('TagRelation', 'id=' . $id);
            }
            TagService::getInstance()->checkById($filter['tag_id']);
            $updateCondition = [
                'unique_code'   =>  $filter['unique_code'],
                'type'          =>  $filter['type'],
                'tag_id'        =>  $filter['tag_id'],
                'desc'          =>  $filter['desc'],
            ];
            IoC()->Tag_relation_model->_update(['id' => $id], $updateCondition);
        }

        return $id;
    }


    /**
     * @param array $params
     *
     * @return bool
     * @throws Exception
     */
    public function batchSave(array $params)
    {
        /** 1. check base params & activity_code */
        $filter = $this->checkTagRelationEntryApiArgument($params);

        /** toDo: default 60 */
        TagService::getInstance()->checkById($filter['tag_id']);

        /** 2. get update & insert & delete data list */
        $uniqueCodes = array_column($filter['tag_relation_list'], 'unique_code');
        $delCondition = [
            'no_unique_codes'   => $uniqueCodes,
            'tag_id'            => $filter['tag_id'],
            'isAll'             => Constants::YES_VALUE
        ];
        $deleteItemList =  IoC()->Tag_relation_model->find($delCondition, $count);
        $deleteIds = array_column($deleteItemList, 'unique_code');

        $oldCondition = [
            'unique_codes'      => $uniqueCodes,
            'tag_id'            => $filter['tag_id'],
            'isAll'             => Constants::YES_VALUE
        ];
        $oldItemList = IoC()->Tag_relation_model->find($oldCondition, $count);
        $oldItemList = array_column($oldItemList, null, 'unique_code');

        $addList = [];
        $updateList = [];
        foreach ($filter['tag_relation_list'] as $item) {
            if (array_key_exists($item['unique_code'], $oldItemList)) {
                $item['id'] = $oldItemList[$item['unique_code']]['id'];
                $updateList[] = $item;
            } else {
                $item['tag_id'] = $params['tag_id'];
                $addList[] = $item;
            }
        }

        /** 3. update & insert & delete data */
        if (is_array($deleteIds) && count($deleteIds) > 0) {
            IoC()->Tag_relation_model->batchDelete($deleteIds);
        }
        if (is_array($updateList) && count($updateList) > 0) {
            IoC()->Tag_relation_model->batchUpdate($updateList);
        }
        if (is_array($addList) && count($addList) > 0) {
            IoC()->Tag_relation_model->batchAdd($addList);
        }

        return true;
    }
#endregion

#region func delete
    /**
     * @param integer $id
     *
     * @return bool
     * @throws Exception
     */
    public function deleteRelationById(int $id)
    {
        $oldTagRelation = IoC()->Tag_relation_model->getById($id);
        if (empty($oldTagRelation)) {
            throw new DBInvalidObjectException('TagRelation','id');
        }
        IoC()->Tag_relation_model->delByWhere(['id' => $id]);
        return true;
    }
#endregion

#region func find

    /**
     * @param array $params
     *
     * @return array
     * @throws Exception
     */
    public function find(array $params)
    {
        $condition = [];
        empty($params['tag_id']) || $condition['tag_id'] = $params['tag_id'];
        empty($params['unique_code']) || $condition['unique_code'] = $params['unique_code'];
        empty($params['type']) || $condition['type'] = $params['type'];


        $page = $params['page'];
        $limit = $params['limit'];
        $page = !empty($page) ? intval($page) : 1;
        $limit = !empty($limit) ? intval($limit) : 10;

        $data =  IoC()->Tag_relation_model->find($condition, $count, $page, $limit);
        $totalPage = ceil($count / $limit);
        $totalPage = $totalPage ? $totalPage : 1;

        return [
            'list'       => $data,
            'total'      => $count,
            'total_page' => $totalPage
        ];
    }

    /**
     * @param array $params
     *
     * @return array
     * @throws Exception
     */
    public function findRelationLeftJoinTag(array $params)
    {
        $condition = [];

        empty($params['state']) || $condition['state'] = $params['state'];
        empty($params['type']) || $condition['type'] = $params['type'];
        empty($params['tag_id']) || $condition['tag_id'] = $params['tag_id'];
        empty($params['unique_code']) || $condition['unique_code'] = $params['unique_code'];

        $page = $params['page'];
        $limit = $params['limit'];
        $page = !empty($page) ? intval($page) : 1;
        $limit = !empty($limit) ? intval($limit) : 1000;

        $data =  IoC()->Tag_relation_model->findRelationLeftJoinTag($condition, $count, $page, $limit);
        $totalPage = ceil($count / $limit);
        $totalPage = $totalPage ? $totalPage : 1;

        return [
            'list'       => $data,
            'total'      => $count,
            'total_page' => $totalPage
        ];
    }
#endregion

#region base
    /**
     * @param integer  $id
     * @param integer $isThrowError
     *
     * @return array
     * @throws Exception
     */
    public function checkById(int $id, $isThrowError=Constants::YES_VALUE)
    {
        $tagRelRation = IoC()->Tag_model->getByID($id);
        if (empty($tagRelRation)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('TagRelationObj', 'id');
        }
        return $tagRelRation;
    }

    /**
     * @param $params
     * @return array|bool
     *
     * @throws Exception
     */
    public function checkTagRelationEntryApiArgument($params)
    {
        $necessaryParamArr = ['tag_id', 'tag_relation_list'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);

        $necessaryParamArr = ['desc', 'unique_code', 'type'];
        $checkLenLimitList = [
            'desc' => 254
        ];
        $this->checkArrayParamArgItem($params['tag_relation_list'], $necessaryParamArr, $checkLenLimitList);

        return $filter;
    }

    /**
     * @param $params
     *
     * @return array|bool
     * @throws Exception
     */
    public function checkBaseTagRelationEntryApiArgument($params)
    {
        $necessaryParamArr = ['unique_code', 'type', 'tag_id', 'desc'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);

        $checkLenLimitList = [
            'unique_code'      => 32,
            'desc'      => 254,
        ];
        $this->checkApiInvalidArgumentLenOverLimit($checkLenLimitList, $params);

        $filter['sort'] = empty($params['sort']) ? 0 : intval($params['sort']);
        return $filter;
    }
#endregion
}