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
        /** 1. check base params */
        $necessaryParamArr = [
            'account_id', 'prize_contest_id', 'date'
        ];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);
        $checkLenLimitList = [
            'account_id' => 50,
            'date' => 50,
        ];
        $this->checkApiInvalidArgumentLenOverLimit($checkLenLimitList, $params);

        /** 2. check prize_contest_id & get current prize_contest config  */
        UserInfoService::getInstance()->checkByAccountId($filter['account_id']);
        $prizeContestConfig = PrizeContestService::getInstance()->checkPrizeContentById($filter['prize_contest_id']);

        /** 3. check prize contest num */
        $limitNum = $prizeContestConfig['entry_num'] ?: 0;
        $totalNum = $this->getPrizeContestRecordNum(
            $filter['account_id'], $filter['prize_contest_id'], $filter['date']
        );

        if ($totalNum >= $limitNum) {
            throw new  Exception('已达到当日冲顶的次数限制：' . $limitNum . '次', 3001);
        }

        /** 4. add record  */
        $condition = [
            'prize_contest_id'  => $filter['prize_contest_id'],
            'account_id'        => $filter['account_id'],
            'date'              => $filter['date'],
            'is_through'        => $filter['is_through'],
            'asset_num'         => $filter['asset_num'],
            'state'             => Constants::YES_VALUE,
            'problem_set'       => ''
        ];
        $id = IoC()->Prize_contest_record_model->_insert($condition);

        /** 5. batch insert prize_contest_record_item  & update problem_set  */
//        $topicNum = $prizeContestConfig['topic_num'] ?: 0;
//        $problemSet = PrizeContestRecordItemService::getInstance()->refreshProblemSet($topicNum);
//        IoC()->Prize_contest_record_model->_update(['id' => $id], ['problem_set' => $problemSet]);

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
     * @param $accountId
     * @param $prizeContestRecordId
     * @param $date
     * @param int $isThrowError
     *
     * @return mixed
     * @throws Exception
     */
    public function checkPrizeContestRecord(
        $accountId,
        $prizeContestRecordId,
        $date,
        $isThrowError=Constants::YES_VALUE
    ) {
        $condition = [
            'account_id'        => $accountId,
            'prize_contest_id'  => $prizeContestRecordId,
            'date'              => $date,
            'iaAll'             => Constants::YES_VALUE
        ];
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

