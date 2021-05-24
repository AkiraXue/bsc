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
use Model\TagModel;
use Model\TagRelationModel;

use Exception\Common\DBInvalidObjectException;
use Exception\Common\DBInvalidArgumentException;
use Exception\Common\ApiInvalidArgumentException;
use Service\BaseService;

/**
 * Class TagRelationService
 * @package Service
 */
class TagRelationService extends BaseService
{
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
        $accountId = $params['account_id'];
        $tagId = $params['tag_id'];
        $desc = $params['desc'];

        if ($params['act'] === Constants::ACT_ADD) {
            $addCondition = [
                'account_id' =>  $accountId,
                'tag_id'     =>  $tagId,
                'desc'       =>  $desc
            ];
            $id = TagRelationModel::getIns()->add($addCondition);
        } else {
            $id = intval($params['id']);
            if (empty($id)) {
                throw new ApiInvalidArgumentException('id');
            }
            $oldUserTagRelation = TagRelationModel::getIns()->getById($id);
            if (empty($oldUserTagRelation)) {
                throw new DBInvalidObjectException('HrUserTagRelation', 'id=' . $id);
            }
            $tag = TagModel::getIns()->getById($tagId);
            if (empty($tag)) {
                throw new DBInvalidObjectException('HrUserTag', 'tagId=' . $tagId);
            }
            $updateCondition = [
                'account_id' =>  $accountId,
                'tag_id'     =>  $tagId,
                'desc'       =>  $desc
            ];
            TagRelationModel::getIns()->update(['id' => $id], $updateCondition);
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
        $filter = $this->checkActivityScheduleEntryApiArgument($params);

        ActivityService::getInstance()->checkActivityByCode($filter['activity_code']);

        /** 2. get update & insert & delete data list */
        $days = array_column($filter['schedule_list'], 'day');
        $delCondition = [
            'no_days'       => $days,
            'activity_code' => $params['activity_code'],
            'isAll'         => Constants::YES_VALUE
        ];
        $deleteItemList =  IoC()->Activity_schedule_model->find($delCondition, $count);
        $deleteIds = array_column($deleteItemList, 'id');

        $oldCondition = [
            'days'          => $days,
            'activity_code' => $params['activity_code'],
            'isAll'         => Constants::YES_VALUE
        ];
        $oldItemList = IoC()->Activity_schedule_model->find($oldCondition, $count);
        $oldItemList = array_column($oldItemList, null, 'day');

        $addList = [];
        $updateList = [];
        foreach ($filter['schedule_list'] as $item) {
            if (array_key_exists($item['day'], $oldItemList)) {
                $item['id'] = $oldItemList[$item['day']]['id'];
                $updateList[] = $item;
            } else {
                $item['activity_code'] = $params['activity_code'];
                $addList[] = $item;
            }
        }

        /** 3. update & insert & delete data */
        if (is_array($deleteIds) && count($deleteIds) > 0) {
            IoC()->Activity_schedule_model->batchDelete($deleteIds);
        }
        if (is_array($updateList) && count($updateList) > 0) {
            IoC()->Activity_schedule_model->batchUpdate($updateList);
        }

        if (is_array($addList) && count($addList) > 0) {
            IoC()->Activity_schedule_model->batchAdd($addList);
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
        $oldUserTagRelation = TagRelationModel::getIns()->getById($id);
        if (empty($oldUserTagRelation)) {
            throw new DBInvalidObjectException('HrUserTagRelation','id');
        }
        TagRelationModel::getIns()->del(['id' => $id]);
        return true;
    }

    /**
     * 根据accountId删除关联关系
     *
     * @param string $accountId
     *
     * @return bool
     * @throws Exception
     */
    public function deleteRelationByAccountId(string $accountId)
    {
        UserService::getInstance()->checkUserInfoByAccountId($accountId);

        $countCondition = ['account_id' => $accountId];
        $oldTagRelation = TagRelationModel::getIns()->num($countCondition);
        if (empty($oldTagRelation)) {
            throw new DBInvalidObjectException('TagRelation','account_id');
        }
        $delCondition = ['account_id' => $accountId];
        TagRelationModel::getIns()->del($delCondition);
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
        empty($params['account_id']) || $condition['account_id'] = $params['account_id'];
        empty($params['tag_id']) || $condition['tag_id'] = $params['tag_id'];

        $page = $params['page'];
        $limit = $params['limit'];
        $page = !empty($page) ? intval($page) : 1;
        $limit = !empty($limit) ? intval($limit) : 1000;

        $response = TagRelationModel::getIns()->allItems($page, $limit, $condition);
        $count = $response['total'];
        $data = $response['items'];

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
        empty($params['account_id']) || $condition['account_id'] = $params['account_id'];
        empty($params['tag_id']) || $condition['tag_id'] = $params['tag_id'];

        $page = $params['page'];
        $limit = $params['limit'];
        $page = !empty($page) ? intval($page) : 1;
        $limit = !empty($limit) ? intval($limit) : 1000;

        $response = TagRelationModel::getIns()->findRelationLeftJoinTag($condition, $page, $limit);
        $count = $response['total'];
        $data = $response['items'];

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
     * @param $params
     * @return array|bool
     *
     * @throws Exception
     */
    public function checkTagRelationEntryApiArgument($params)
    {
        $necessaryParamArr = ['account_id', 'tag_relation_list'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);

        $necessaryParamArr = ['tag_id', 'desc'];
        $checkLenLimitList = [
            'desc' => 254
        ];
        $this->checkArrayParamArgItem($params['tag_relation_list'], $necessaryParamArr, $checkLenLimitList);

        return $filter;
    }
#endregion
}