<?php
/**
 * PrizeService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/23/21 9:59 AM
 */

namespace Service\PrizeContest;

use Lib\Constants;

use Service\BaseTrait;
use Service\BaseService;
use Service\Asset\AssetService;
use Service\User\UserInfoService;
use Service\Knowledge\TopicServices;

use Exception;

/**
 * Class PrizeService
 * @package Service\PrizeContest
 */
class PrizeService extends BaseService
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

#region prize
    /**
     * @param $accountId
     * @param $date
     *
     * @return mixed
     * @throws Exception
     */
    public function getConfig($accountId, $date='')
    {
        $currentConfig = PrizeContestService::getInstance()->getCurrentConfig();
        $totalNum = PrizeContestRecordService::getInstance()->getPrizeContestRecordNum(
            $accountId, $currentConfig['id'], $date
        );
        return  [
            'prize_num'   => 0,
            // 'prize_num'   => intval($totalNum),
            'prize_total' => intval($currentConfig['entry_num']),
            'is_prize_exist' => $currentConfig['state'] == Constants::YES_VALUE ? Constants::YES_VALUE  : Constants::NO_VALUE ,
        ];
    }

    /**
     * @param $accountId
     * @param $date
     *
     * @return mixed
     * @throws Exception
     */
    public function init($accountId, $date)
    {
        $prizeContest = PrizeContestService::getInstance()->getCurrentConfig();
        $prizeContestId = $prizeContest['id'];
        $params = [
            'account_id'        => $accountId,
            'prize_contest_id'  => $prizeContestId,
            'date'              => $date,
        ];
        $id = PrizeContestRecordService::getInstance()->save($params);
        return ['id' => $id];
    }

    /**
     * @param $params
     *
     * @return mixed
     * @throws Exception
     */
    public function getProblem(array $params)
    {
        /** 1. check base params */
        $necessaryParamArr = [
            'account_id', 'prize_contest_record_id'
        ];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);
        $checkLenLimitList = [
            'account_id' => 50,
        ];
        $this->checkApiInvalidArgumentLenOverLimit($checkLenLimitList, $params);

        /** 2. getProblem */
        $condition = [
            'orderBy'               => 'sort asc',
            'account_id'            => $filter['account_id'],
            'prize_contest_record_id' => $filter['prize_contest_record_id'],
            'page'                  => 1,
            'limit'                 => 100,
        ];
        $itemRes = PrizeContestRecordItemService::getInstance()->find($condition);
        if (empty($itemRes['list']) && empty($itemRes['list'][0])) {
            return [];
        }
        $topicIds = array_column($itemRes['list'], 'topic_id');
        $topicListRes = TopicServices::getInstance()->find(['ids' => $topicIds]);
        $topicList = array_column( $topicListRes['list'], null, 'id');

        foreach ($itemRes['list'] as &$item) {
            $topicId = $item['topic_id'];
            if (!array_key_exists($topicId, $topicList)) {
                continue;
            }
            $topic = $topicList[$topicId];
            $topic['content']['list'] = array_filter($topic['content']['list']);
            $item['topic'] = $topic;
        }
        return $itemRes;
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws Exception
     */
    public function answer(array $params)
    {
        /** 1. check base params */
        $necessaryParamArr = [
            'account_id', 'item_id', 'answer'
        ];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);
        $checkLenLimitList = [
            'account_id' => 50,
        ];
        $this->checkApiInvalidArgumentLenOverLimit($checkLenLimitList, $params);

        /** 2. get prize test record item info */
        UserInfoService::getInstance()->checkByAccountId($filter['account_id']);
        $item = PrizeContestRecordItemService::getInstance()->checkPrizeContentRecordItemById($filter['item_id']);
        if ($item['state'] == Constants::NO_VALUE) {
            throw new Exception('当前题目已经提交过了！', 3001);
        }

        /** 3. get related topic &  info */
        $topic = TopicServices::getInstance()->checkById($item['topic_id']);
        $prizeContest = PrizeContestService::getInstance()->checkPrizeContentById($item['prize_contest_id']);

        /** 4. check is correct */
        $correctChoice = $topic['content']['answer_num'];
        if ($topic['answer_type'] == 'dupChoice') {
            sort($filter['answer']);
            sort($correctChoice);

            $filter['answer'] = json_encode($filter['answer']);
            $correctChoice = json_encode($correctChoice);
        } else {
            $filter['answer'] = $filter['answer'][0];
        }
        $isCorrect = ($filter['answer'] == $correctChoice) ? Constants::YES_VALUE : Constants::NO_VALUE;

        /** 5. check prize schedule && prize */
        $condition = [
            'prize_contest_id' => $item['prize_contest_id'],
            'sort'             => $item['sort']
        ];
        $schedule = IoC()->Prize_contest_schedule_model->get($condition);
        if ($isCorrect == Constants::YES_VALUE && !empty($schedule) && isset($schedule['id'])) {
            $source = '冲顶答题-轮次'. $schedule['sort'];
            $this->refreshPrizeContestScheduleRecordAssetChangeLog($filter['account_id'], $schedule['asset_num'], $source);
        }

        /** 6. refresh current prize contest record item */
        $where = ['id' => $filter['item_id']];
        $condition = [
            'state'     => Constants::NO_VALUE,
            'draft'     => $filter['answer'],
            'answer'    => $correctChoice,
            'is_correct'=> $isCorrect,
            'is_asset_award' => ($schedule && isset($schedule['is_asset_award'])) ? $schedule['is_asset_award'] : Constants::NO_VALUE,
            'asset_num' => ($schedule && isset($schedule['is_asset_award'])) ? $schedule['asset_num'] : 0,
        ];
        IoC()->Prize_contest_record_item_model->_update($where, $condition);


        /** 7. if is_next == NO_VALUE; add rank info */
        $isNext = Constants::YES_VALUE;
        if (($item['sort'] >= $prizeContest['topic_num'])) {
            $isNext = Constants::NO_VALUE;

            $this->completePrizeContentRecord(
                $filter['account_id'], $item['prize_contest_record_id'], $prizeContest
            );
        }

        return [
            'status'        => $isCorrect,
            'is_next'       => $isNext,
            'asset_num'     => $isCorrect ? ($schedule['asset_num'] ? : 0) : 0,
            'is_asset_award' => $isCorrect ? ($schedule['is_asset_award']?: Constants::NO_VALUE) : Constants::NO_VALUE,
        ];
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws Exception
     */
    public function checkPrizeStatus(array $params)
    {
        /** 1. check base params */
        $necessaryParamArr = ['id'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);

        /** 2. get current record */
        $prizeContestRecord = PrizeContestRecordService::getInstance()->checkPrizeContestRecordById($filter['id']);
        $prizeContestRecordId = $prizeContestRecord['id'];

        $correctNum = PrizeContestRecordService::getInstance()->getPrizeContestRecordCorrectNum($prizeContestRecordId);

        $bestRank = PrizeContestRecordService::getInstance()->getBestRank($prizeContestRecordId);

        /** 3. get content record result */
        return [
            'correct_num' => intval($correctNum),
            'asset_num'   => $prizeContestRecord['asset_num']?intval($prizeContestRecord['asset_num']):0,
            'bestRank'    => $bestRank['sort']?intval($bestRank['sort']):0
        ];
    }

    /**
     * 排行榜
     *
     * @param array $params
     *
     * @return mixed
     * @throws Exception
     */
    public function rank(array $params)
    {
        $condition = [];

        empty($params['account_id']) || $condition['account_id'] = $params['account_id'];

        $page = $params['page'];
        $limit = $params['limit'];
        $page = !empty($page) ? intval($page) : 1;
        $limit = !empty($limit) ? intval($limit) : 10;

        $condition['orderBy'] = ['asset_num' => 'desc'];
        $data = IoC()->Prize_contest_rank_model->find($condition, $count, $page, $limit);

        $accountIds = array_column($data, 'account_id');
        $userInfoRes = UserInfoService::getInstance()->find(['account_id' => $accountIds]);

        $userInfoList = array_column($userInfoRes['list'], null, 'account_id');
        foreach ($data as &$item) {
            $accountId = $item['account_id'];
            if (!array_key_exists($accountId, $userInfoList)) {
                continue;
            }
            $userInfo = $userInfoList[$accountId];
            $item['name'] = $userInfo['name'];
            $item['nickname'] = $userInfo['nickname'];
            $item['avatar'] = $userInfo['avatar'];
        }


        $totalPage = ceil($count / $limit);
        $totalPage = $totalPage ? $totalPage : 1;
        return [
            'list'        => $data,
            'total'      => $count,
            'total_page' => $totalPage
        ];
    }
#endregion

#region prize answer
    /**
     * @param $accountId
     * @param $prizeContestRecordId
     * @param $prizeContest
     *
     * @return mixed
     * @throws Exception
     */
    public function completePrizeContentRecord($accountId, $prizeContestRecordId, $prizeContest)
    {
        /** 1. get current correct num & record asset num */
        $correctNum = PrizeContestRecordService::getInstance()->getPrizeContestRecordCorrectNum($prizeContestRecordId);
        $prizeContestRecordAssetNum = PrizeContestRecordService::getInstance()->getPrizeContestRecordTotalAssetNum($prizeContestRecordId);

        /** 2. check is through */
        $isThrough = Constants::NO_VALUE;
        if ($correctNum == $prizeContest['topic_num']) {
            $isThrough = Constants::YES_VALUE;
        }

        /** 3. update asset_num change log */
        if ($isThrough == Constants::YES_VALUE && $prizeContest['is_asset_award'] == Constants::YES_VALUE) {
            $source = '冲顶答题通关';
            PrizeService::getInstance()->refreshPrizeContestScheduleRecordAssetChangeLog(
                $accountId, $prizeContest['asset_num'], $source
            );
            $prizeContestRecordAssetNum += $prizeContest['asset_num'];
        }

        /** 4. update prize contest record */
        $condition = [
            'is_through' => $isThrough,
            'asset_num'  => $prizeContestRecordAssetNum
        ];
        IoC()->Prize_contest_record_model->_update(['id' => $prizeContestRecordId], $condition);

        return true;
    }

    /**
     * 更新档次打卡轮次
     * @param $accountId
     * @param $assetNum
     * @param $source
     *
     * @return bool
     * @throws Exception
     */
    public function refreshPrizeContestScheduleRecordAssetChangeLog($accountId, $assetNum, $source)
    {
        AssetService::getInstance()->storage(
            $accountId, $assetNum, Constants::ASSET_TYPE_JIFEN, $source
        );
        PrizeContestRankService::getInstance()->save($accountId, $assetNum);
        return true;
    }
#endregion
}