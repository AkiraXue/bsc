<?php
/**
 * PrizeContestRecordService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/22/21 9:40 PM
 */

namespace Service\PrizeContest;

use Lib\Constants;

use Service\BaseTrait;
use Service\BaseService;

use Exception;
use Exception\Common\DBInvalidObjectException;
use Service\User\UserInfoService;

/**
 * Class PrizeContestRecordService
 * @package Service\PrizeContest
 */
class PrizeContestRecordService extends BaseService
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

#region func
    public function find(array $params)
    {
        $condition = [];

        empty($params['prize_contest_id']) || $condition['prize_contest_id'] = $params['prize_contest_id'];

        empty($params['start_date']) || $condition['start_date'] = $params['start_date'];
        empty($params['end_date']) || $condition['end_date'] = $params['end_date'];

        empty($params['date']) || $condition['date'] = $params['date'];
        empty($params['is_through']) || $condition['is_through'] = $params['is_through'];

        empty($params['state']) || $condition['state'] = $params['state'];

        $page = $params['page'];
        $limit = $params['limit'];
        $page = !empty($page) ? intval($page) : 1;
        $limit = !empty($limit) ? intval($limit) : 10;

        $data =  IoC()->Prize_contest_record_model->find($condition, $count, $page, $limit);
        $totalPage = ceil($count / $limit);
        $totalPage = $totalPage ? $totalPage : 1;
        return [
            'list'       => $data,
            'total'      => $count,
            'total_page' => $totalPage
        ];
    }


    /**
     * 保存用户冲顶记录
     *
     * @param array $params
     *
     * @return mixed
     * @throws Exception
     */
    public function save(array $params)
    {
        $date = date('Y-m-d');
        /** 1. check base params */
        $necessaryParamArr = [
            'account_id', 'prize_contest_id'
        ];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);
        $checkLenLimitList = [
            'account_id' => 50,
        ];
        $this->checkApiInvalidArgumentLenOverLimit($checkLenLimitList, $params);
        $filter['date'] = $params['date'] ? : date('Y-m-d');

        /** 2. check prize_contest_id & get current prize_contest config  */
        UserInfoService::getInstance()->checkByAccountId($filter['account_id']);
        $prizeContestConfig = PrizeContestService::getInstance()->checkPrizeContentById($filter['prize_contest_id']);

        /** 3. check prize contest num */
        $limitNum = $prizeContestConfig['entry_num'] ?: 0;
        $totalNum = $this->getPrizeContestRecordNum(
            $filter['account_id'], $filter['prize_contest_id'], $filter['date']
        );

        if ($totalNum >= $limitNum) {
            // throw new  Exception('已达到当日冲顶的次数限制：' . $limitNum . '次', 3001);
        }

        /** 4. add record  */
        $condition = [
            'prize_contest_id'  => $filter['prize_contest_id'],
            'account_id'        => $filter['account_id'],
            'date'              => $filter['date'],
            'is_through'        => $filter['is_through']?:Constants::NO_VALUE,
            'asset_num'         => $filter['asset_num']?:'0',
            'state'             => Constants::YES_VALUE,
            'problem_set'       => ''
        ];
        $id = IoC()->Prize_contest_record_model->_insert($condition);

        /** 5. batch insert prize_contest_record_item  & prize_contest_record_item related schedule */
        $topicNum = $prizeContestConfig['topic_num'] ?: 0;
        $topicList = PrizeContestRecordItemService::getInstance()->refreshProblemSet($topicNum);

        $prizeContestRecordItems = [];
        foreach ($topicList as $key => $topic) {
            $key = $key  + 1;
            $item = [
                'prize_contest_record_id' => $id,
                'prize_contest_id'        => $filter['prize_contest_id'],
                'account_id'              => $filter['account_id'],
                'date'                    => $date,
                'knowledge_id'            => $topic['knowledge_id'],
                'topic_id'                => $topic['id'],
                'sort'                    => $key,
                'draft'                   => '',
                'answer'                  => '',
                'is_correct'              => Constants::NO_VALUE,
                'is_asset_award'          => Constants::NO_VALUE,
                'asset_num'               => 0,
                'state'                   => Constants::YES_VALUE,
            ];

            $prizeContestRecordItems[] = $item;
        }
        if (count($prizeContestRecordItems)> 0) {
            IoC()->Prize_contest_record_item_model->batchAdd($prizeContestRecordItems);
        }

        /** 6. update problem_set  */
        $topicIds = array_column($topicList, 'id');
        $problemSetJsonStr = implode( ',', $topicIds);
        IoC()->Prize_contest_record_model->_update(['id' => $id], ['problem_set' => $problemSetJsonStr]);

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
    public function checkPrizeContentRecordById(int $id, $isThrowError=Constants::YES_VALUE)
    {
        $condition = ['id'  => $id];
        $prizeContestRecord = IoC()->Prize_contest_record_model->get($condition);
        if (empty($prizeContestRecord)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('PrizeContestRecordObj', 'id');
        }
        return $prizeContestRecord;
    }

    /**
     * 校验当前人的打卡记录
     *
     * @param $params
     *
     * @param int $isThrowError
     *
     * @return mixed
     * @throws Exception
     */
    public function checkPrizeContestRecord(
        array $params,
        $isThrowError=Constants::YES_VALUE
    ) {
        $accountId = $params['account_id'];
        $date  = $params['date'];
        $prizeContestRecordId = $params['prize_contest_id']?:null;
        $condition = [
            'account_id'        => $accountId,
            'date'              => $date,
            'iaAll'             => Constants::YES_VALUE
        ];

        if ($prizeContestRecordId) {
            $condition['prize_contest_id'] = $prizeContestRecordId;
        }
        $prizeContestRecords = IoC()->Prize_contest_record_model->find($condition, $count);
        if (empty($prizeContestRecords)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('PrizeContestRecordObjList', 'id');
        }
        return $prizeContestRecords;
    }


    /**
     * 校验当前人的打卡记录
     *
     * @param $accountId
     * @param $prizeContestRecordId
     * @param $date
     * @param int $isThrowError
     *
     * @return mixed
     * @throws Exception
     */
    public function checkPrizeContestRecordById($id, $isThrowError=Constants::YES_VALUE) {
        $condition = [
            'id'  => $id,
        ];
        $prizeContestRecord = IoC()->Prize_contest_record_model->find($condition, $count);
        if (empty($prizeContestRecord)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('PrizeContestRecordObjList', 'id');
        }
        return $prizeContestRecord;
    }


    /**
     * 校验当前人的打卡记录数量
     *
     * @param $accountId
     * @param $prizeContestRecordId
     * @param $date
     *
     * @return mixed
     * @throws Exception
     */
    public function getPrizeContestRecordNum(
        $accountId,
        $prizeContestRecordId,
        $date
    ) {
        $condition = [
            'account_id'        => $accountId,
            'prize_contest_id'  => $prizeContestRecordId,
            'date'              => $date,
        ];
        $totalNum = IoC()->Prize_contest_record_model->getTotal($condition);
        return $totalNum;
    }
#endregion
}

