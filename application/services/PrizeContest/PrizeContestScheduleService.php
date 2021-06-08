<?php
/**
 * PrizeContestScheduleService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/22/21 9:10 PM
 */

namespace Service\PrizeContest;

use Lib\Constants;

use Service\BaseTrait;
use Service\BaseService;

use Exception;
use Exception\Common\DBInvalidObjectException;

/**
 * Class PrizeContestScheduleService
 * @package Service\PrizeContest
 */
class PrizeContestScheduleService extends BaseService
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

#region start
    /**
     * 搜索
     *
     * @param array $params
     *
     * @return array
     */
    public function find(array $params)
    {
        $condition = [];

        empty($params['prize_contest_id']) || $condition['prize_contest_id'] = $params['prize_contest_id'];

        empty($params['sort']) || $condition['sort'] = $params['sort'];
        empty($params['is_asset_award']) || $condition['is_asset_award'] = $params['is_asset_award'];

        empty($params['state']) || $condition['state'] = $params['state'];

        $page = $params['page'];
        $limit = $params['limit'];
        $page = !empty($page) ? intval($page) : 1;
        $limit = !empty($limit) ? intval($limit) : 10;

        $data =  IoC()->Prize_contest_schedule_model->find($condition, $count, $page, $limit);
        $totalPage = ceil($count / $limit);
        $totalPage = $totalPage ? $totalPage : 1;
        return [
            'list'       => $data,
            'total'      => $count,
            'total_page' => $totalPage
        ];
    }

    /**
     * 保存冲顶配置
     *
     * @param array $params
     *
     * @return int
     * @throws Exception
     */
    public function save(array $params)
    {
        /** 1. check base params */
        $necessaryParamArr = [
            'sort', 'is_asset_award', 'asset_num'
        ];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);
        $checkLenLimitList = [
            'asset_num' => 50,
        ];
        $this->checkApiInvalidArgumentLenOverLimit($checkLenLimitList, $params);

        /** check prize contest id */
        $filter['prize_contest_id'] = $params['prize_contest_id'];
        if (empty($filter['prize_contest_id'])) {
            $currentPrizeContest = PrizeContestService::getInstance()->getCurrentConfig();
            $filter['prize_contest_id'] = $currentPrizeContest['id'];
        }

        /** 2. check data */
        $id = $params['id'];
        if ($id) {
            $this->checkScheduleById($id);
        } else {
            $condition = [
                'prize_contest_id'  => $filter['prize_contest_id'],
                'sort'              => $filter['sort'],
            ];
            $oldSchedule = IoC()->Prize_contest_schedule_model->get($condition);
            if ($oldSchedule && isset($oldSchedule['id'])) {
                $id = $oldSchedule['id'];
            }
        }

        PrizeContestService::getInstance()->checkPrizeContentById($filter['prize_contest_id']);

        /** 3. save prize contest schedule info */
        $condition = [
            'sort'              => $filter['sort'],
            'prize_contest_id'  => $filter['prize_contest_id'],
            'is_asset_award'    => $filter['is_asset_award'],
            'asset_num'         => $filter['asset_num']
        ];
        if ($id) {
            return IoC()->Prize_contest_schedule_model->_update(['id' => $id], $condition);
        } else {
            return IoC()->Prize_contest_schedule_model->_insert($condition);
        }
    }

    /**
     * 批量保存
     *
     * @param array $params
     *
     * @return mixed
     * @throws Exception
     */
    public function batchSave(array $params)
    {
        /** 1. check base params & activity_code */
        $filter = $this->checkPrizeContestScheduleEntryApiArgument($params);

        PrizeContestService::getInstance()->checkPrizeContentById($filter['prize_contest_id']);

        /** 2. get update & insert & delete data list */
        $sorts = array_column($filter['schedule_list'], 'sort');
        $delCondition = [
            'no_sorts'          => $sorts,
            'prize_contest_id'  => $params['prize_contest_id'],
            'isAll'             => Constants::YES_VALUE
        ];
        $deleteItemList =  IoC()->Prize_contest_schedule_model->find($delCondition, $count);
        $deleteIds = array_column($deleteItemList, 'id');

        $oldCondition = [
            'sorts'             => $sorts,
            'prize_contest_id'  => $params['prize_contest_id'],
            'isAll'             => Constants::YES_VALUE
        ];
        $oldItemList = IoC()->Prize_contest_schedule_model->find($oldCondition, $count);
        $oldItemList = array_column($oldItemList, null, 'sort');

        $addList = [];
        $updateList = [];
        foreach ($filter['schedule_list'] as $item) {
            if (array_key_exists($item['sort'], $oldItemList)) {
                $item['id'] = $oldItemList[$item['sort']]['id'];
                $updateList[] = $item;
            } else {
                $item['prize_contest_id'] = $params['prize_contest_id'];
                $addList[] = $item;
            }
        }

        /** 3. update & insert & delete data */
        if (is_array($deleteIds) && count($deleteIds) > 0) {
            IoC()->Prize_contest_schedule_model->batchDelete($deleteIds);
        }
        if (is_array($updateList) && count($updateList) > 0) {
            IoC()->Prize_contest_schedule_model->batchUpdate($updateList);
        }

        if (is_array($addList) && count($addList) > 0) {
            IoC()->Prize_contest_schedule_model->batchAdd($addList);
        }

        return true;
    }
#endregion

#region base
    /**
     * @param integer  $id
     * @param integer  $isThrowError
     *
     * @return array
     * @throws Exception
     */
    public function checkScheduleById(int $id, $isThrowError=Constants::YES_VALUE)
    {
        $condition = ['id'  => $id];
        $prizeContestSchedule = IoC()->Prize_contest_schedule_model->get($condition);
        if (empty($prizeContestSchedule)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('PrizeContestScheduleObj', 'id');
        }
        return $prizeContestSchedule;
    }

    /**
     * @param $params
     * @return array|bool
     *
     * @throws Exception
     */
    public function checkPrizeContestScheduleEntryApiArgument($params)
    {
        $necessaryParamArr = ['prize_contest_id', 'schedule_list'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);

        $necessaryParamArr = ['sort', 'is_asset_award', 'asset_num'];
        $checkLenLimitList = [
            'asset_num' => 50
        ];
        $this->checkArrayParamArgItem($params['schedule_list'], $necessaryParamArr, $checkLenLimitList);

        return $filter;
    }
#endregion

}