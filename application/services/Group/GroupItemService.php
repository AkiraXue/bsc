<?php
/**
 * GroupItemService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/16/21 11:28 PM
 */

namespace Service\Group;

use Exception;

use Lib\Constants;

use Service\BaseTrait;
use Service\BaseService;

use Exception\Common\DBInvalidObjectException;
use Exception\Common\ApiVerifyArgumentTypeErrorException;

/**
 * Class GroupItemService
 * @package Service
 */
class GroupItemService extends BaseService
{
    use BaseTrait;

#region initial info
    public static $instance;

    public function __construct()
    {
        parent::__construct();
    }

    public static function getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }
#endregion

#region group item module
    public function find(array $params)
    {
        $condition = [];

        empty($params['group_code']) || $condition['group_code'] = $params['group_code'];
        empty($params['unique_code']) || $condition['unique_code'] = $params['unique_code'];

        empty($params['state']) || $condition['state'] = $params['state'];

        $page = $params['page'];
        $limit = $params['limit'];
        $page = !empty($page) ? intval($page) : 1;
        $limit = !empty($limit) ? intval($limit) : 10;

        $data = IoC()->Group_item_model->find($condition, $count, $page, $limit);
        $totalPage = ceil($count / $limit);
        $totalPage = $totalPage ? $totalPage : 1;
        return [
            'list' => $data,
            'total' => $count,
            'total_page' => $totalPage
        ];
    }

    /**
     * 保存子项信息
     * @param array $params
     *
     * @return int
     * @throws Exception
     */
    public function save(array $params)
    {
        /** 1. check base params & activity_code */
        $necessaryParamArr = ['group_code', 'unique_code'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);
        $checkLenLimitList = [
            'group_code'  => 50,
            'unique_code' => 50
        ];
        $this->checkApiInvalidArgumentLenOverLimit($checkLenLimitList, $params);

        /** 2. check activity_code & knowledge && current day schedule */
        GroupService::getInstance()->checkGroupByCode($filter['group_code']);
        if ($params['state'] && !in_array($params['state'], [Constants::YES_VALUE, Constants::NO_VALUE])) {
            throw new ApiVerifyArgumentTypeErrorException(
                'state', 'enum', __CLASS__, __FUNCTION__
            );
        }
        /** 3. check activity schedule => delete or close old schedule setting  */
        $condition = [
            'unique_code'   => $filter['unique_code'],
            'group_code'    => $filter['group_code']
        ];
        $oldGroupItem =  IoC()->Group_item_model->findOne($condition);
        if (empty($params['id'])) {
            $params['id'] = $oldGroupItem['id'];
        }

        /** 4. save schedule data */
        if ($params['id']) {
            $filter['id'] = $params['id'];
            return $this->update($params);
        } else {
            return $this->add($params);
        }
    }

    /**
     * @param array $params
     *
     * @return bool
     * @throws Exception
     */
    public function batchSave(array $params)
    {
        /** 1. check base params & group_code */
        $filter = $this->checkGroupItemEntryApiArgument($params);

        GroupService::getInstance()->checkGroupByCode($filter['group_code']);

        /** 2. get update & insert & delete data list */
        $uniqueCodes = array_column($filter['item_list'], 'unique_code');
        $delCondition = [
            'no_unique_codes' => $uniqueCodes,
            'isAll'           => Constants::YES_VALUE
        ];
        $deleteGroupItemList =  IoC()->Group_item_model->find($delCondition, $count);
        $deleteIds = array_column($deleteGroupItemList, 'id');

        $oldCondition = [
            'unique_codes'  => $uniqueCodes,
            'isAll'         => Constants::YES_VALUE
        ];
        $oldGroupItemList = IoC()->Group_item_model->find($oldCondition, $count);
        $oldGroupItemList = array_column($oldGroupItemList, null, 'unique_code');

        $addList = [];
        $updateList = [];
        foreach ($filter['item_list'] as $groupItem) {
            if (array_key_exists($groupItem['unique_code'], $oldGroupItemList)) {
                $groupItem['id'] = $oldGroupItemList[$groupItem['unique_code']]['id'];
                $updateList[] = $groupItem;
            } else {
                $groupItem['group_code'] = $params['group_code'];
                $addList[] = $groupItem;
            }
        }

        /** 3. update & insert & delete data */
        if (is_array($deleteIds) && count($deleteIds) > 0) {
            IoC()->Group_item_model->batchDelete($deleteIds);
        }
        if (is_array($updateList) && count($updateList) > 0) {
            IoC()->Group_item_model->batchUpdate($updateList);
        }

        if (is_array($addList) && count($addList) > 0) {
            IoC()->Group_item_model->batchAdd($addList);
        }

        return true;
    }
#endregion

#region common func
    /**
     * @param array $params
     * @return int
     *
     * @throws Exception
     */
    public function update(array $params)
    {
        /** check old activity by id */
        $this->checkById($params['id']);

        /** update time */
        $where = ['id' => $params['id']];
        $update = [
            'group_code'    => $params['group_code'],
            'unique_code'   => $params['unique_code'],
            'state'         => $params['state'] ?: Constants::YES_VALUE
        ];
        return IoC()->Group_item_model->_update($where, $update);
    }

    /**
     * @param array $params
     *
     * @return int
     */
    public function add(array $params)
    {
        $insert = [
            'group_code'    => $params['group_code'],
            'unique_code'   => $params['unique_code'],
            'state'         => $params['state'] ?: Constants::YES_VALUE
        ];
        return IoC()->Group_item_model->_insert($insert);
    }
#endregion

#region base func
    /**
     * @param integer  $id
     * @param integer $isThrowError
     *
     * @return array
     * @throws Exception
     */
    public function checkById(int $id, $isThrowError=Constants::YES_VALUE)
    {
        $groupItem = IoC()->Group_item_model->getByID($id);
        if (empty($groupItem)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('GroupItemObj', 'id');
        }
        return $groupItem;
    }

    /**
     * @param $params
     * @return array|bool
     *
     * @throws Exception
     */
    public function checkGroupItemEntryApiArgument($params)
    {
        $necessaryParamArr = ['group_code', 'item_list'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);

        $necessaryParamArr = ['unique_code'];
        $checkLenLimitList = [
            'unique_code' => 50
        ];
        $this->checkArrayParamArgItem($params['item_list'], $necessaryParamArr, $checkLenLimitList);

        return $filter;
    }
#endregion
}