<?php
/**
 * ActivityScheduleService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/16/21 11:27 AM
 */
namespace Service\Activity;

use Exception;

use Lib\Constants;

use Service\BaseTrait;
use Service\BaseService;

use Service\Knowledge\KnowledgeService;

use Exception\Common\DBInvalidObjectException;
use Exception\Common\ApiVerifyArgumentTypeErrorException;

/**
 * Class ActivityScheduleService
 * @package Service
 */
class ActivityScheduleService extends BaseService
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
        if (!self::$instance instanceof self){
            self::$instance = new self() ;
        }
        return self::$instance;
    }
#endregion

#region func
    public function find(array $params)
    {
        $condition = [];

        empty($params['activity_code']) || $condition['activity_code'] = $params['activity_code'];
        empty($params['day']) || $condition['day'] = $params['day'];

        empty($params['is_related_knowledge']) || $condition['is_related_knowledge'] = $params['is_related_knowledge'];
        empty($params['knowledge_id']) || $condition['knowledge_id'] = $params['knowledge_id'];

        empty($params['is_asset_award']) || $condition['is_asset_award'] = $params['is_asset_award'];

        empty($params['state']) || $condition['state'] = $params['state'];

        empty($params['isAll']) || $condition['isAll'] = $params['isAll'];

        $page = $params['page'];
        $limit = $params['limit'];
        $page = !empty($page) ? intval($page) : 1;
        $limit = !empty($limit) ? intval($limit) : 10;

        $data =  IoC()->Activity_schedule_model->find($condition, $count, $page, $limit);
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
        $necessaryParamArr = [
            'activity_code', 'day',  'is_asset_award', 'asset_num',
            'is_related_knowledge', 'knowledge_id', 'is_knowledge_asset_award', 'knowledge_asset_num'
        ];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);
        $checkLenLimitList = [
            'activity_code' => 50,
            'asset_num' => 50
        ];
        $this->checkApiInvalidArgumentLenOverLimit($checkLenLimitList, $params);

        /** 2. check activity_code & knowledge && current day schedule */
        ActivityService::getInstance()->checkActivityByCode($filter['activity_code']);
        if ($params['state'] && !in_array($params['state'], [Constants::YES_VALUE, Constants::NO_VALUE])) {
            throw new ApiVerifyArgumentTypeErrorException(
                'state', 'enum', __CLASS__, __FUNCTION__
            );
        }
        if ($params['is_related_knowledge'] == Constants::YES_VALUE && $params['knowledge_id']) {
            KnowledgeService::getInstance()->checkById($params['knowledge_id']);
        }

        /** 3. check activity schedule => delete or close old schedule setting  */
        $condition = [
            'day'           => $params['day'],
            'activity_code' => $params['activity_code']
        ];
        $oldActivitySchedule =  IoC()->Activity_schedule_model->findOne($condition);
        if ($oldActivitySchedule['id'] != $params['id']) {
            if (empty($params['id'])) {
                $params['id'] = $oldActivitySchedule['id'];
            } else {
                IoC()->Activity_schedule_model->delByWhere(['id' => $oldActivitySchedule['id']]);
            }
        }

        /** 4. save schedule data */
        if ($params['id']) {
            $filter['id'] = $params['id'];
            return $this->update($filter);
        } else {
            return $this->add($filter);
        }
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
        $oldActivitySchedule = IoC()->Activity_schedule_model->getByID($params['id']);
        if ($oldActivitySchedule['activity_code'] != $params['activity_code']) {
            throw new DBInvalidObjectException('ActivityScheduleObj', 'id match activity_code');
        }

        /** update time */
        $where = ['id' => $params['id']];
        $update = [
            'day'                       => $params['day'],
            'is_related_knowledge'      => $params['is_related_knowledge'],
            'knowledge_id'              => $params['knowledge_id'],
            'is_knowledge_asset_award'  => $params['is_knowledge_asset_award'],
            'knowledge_asset_num'       => $params['knowledge_asset_num'],
            'is_asset_award'            => $params['is_asset_award'],
            'asset_num'                 => $params['asset_num'],
            'state'                     => $params['state'] ?: Constants::YES_VALUE
        ];
        return IoC()->Activity_schedule_model->_update($where, $update);
    }

    /**
     * @param array $params
     *
     * @return int
     */
    public function add(array $params)
    {
        $insert = [
            'activity_code'             => $params['activity_code'],
            'day'                       => $params['day'],
            'is_related_knowledge'      => $params['is_related_knowledge'],
            'knowledge_id'              => $params['knowledge_id'],
            'is_knowledge_asset_award'  => $params['is_knowledge_asset_award'],
            'knowledge_asset_num'       => $params['knowledge_asset_num'],
            'is_asset_award'            => $params['is_asset_award'],
            'asset_num'                 => $params['asset_num'],
            'state'                     => $params['state'] ?: Constants::YES_VALUE
        ];
        return IoC()->Activity_schedule_model->_insert($insert);
    }
#endregion

#region base function

    /**
     * @param integer  $id
     * @param integer $isThrowError
     *
     * @return array
     * @throws Exception
     */
    public function checkById(int $id, $isThrowError=Constants::YES_VALUE)
    {
        $activitySchedule = IoC()->Activity_schedule_model->getByID($id);
        if (empty($activitySchedule)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('ActivityScheduleObj', 'id');
        }
        return $activitySchedule;
    }

    /**
     * @param $params
     * @return array|bool
     *
     * @throws Exception
     */
    public function checkActivityScheduleEntryApiArgument($params)
    {
        $necessaryParamArr = ['activity_code', 'schedule_list'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);

        $necessaryParamArr = [
            'day', 'is_asset_award', 'asset_num',
            'is_related_knowledge', 'knowledge_id',
            'is_knowledge_asset_award', 'knowledge_asset_num'
        ];
        $checkLenLimitList = [
            'asset_num' => 50
        ];
        $this->checkArrayParamArgItem($params['schedule_list'], $necessaryParamArr, $checkLenLimitList);

        return $filter;
    }
#endregion
}