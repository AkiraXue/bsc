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
            //'prize_num'   => intval($totalNum),
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
            'account_id' => $accountId,
            'prize_contest_id' => $prizeContestId,
            'date' => $date,
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
            $item['topic'] = $topicList[$topicId];
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
        }
        $isCorrect = ($filter['answer'] == $correctChoice) ? Constants::YES_VALUE : Constants::NO_VALUE;

        /** 5. check prize schedule && prize */
        $condition = [
            'prize_contest_id' => $item['prize_contest_id'],
            'sort'             => $item['sort']
        ];
        $schedule = IoC()->Prize_contest_schedule_model->get($condition);
        if (!empty($schedule) && isset($schedule['id'])) {
            /** 7. asset update */
            if ($isCorrect == Constants::YES_VALUE) {
                $type = 'jifen';
                AssetService::getInstance()->storage(
                    $filter['account_id'], $schedule['asset_num'], $type, '冲顶答题'
                );
                PrizeContestRankService::getInstance()->save($filter['account_id'], $schedule['asset_num']);
            }
        }

        /** 6. check is close current record */
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

        $isNext = Constants::NO_VALUE;
        if (($item['sort'] < $prizeContest['topic_num']) && $isCorrect) {
            $isNext = Constants::YES_VALUE;
        }
        return [
            'status' => $isCorrect,
            'is_next' => $isNext,
            'is_asset_award' => $isCorrect ? ($schedule['is_asset_award']?: Constants::NO_VALUE) : Constants::NO_VALUE,
            'asset_num' => $isCorrect ? ($schedule['asset_num'] ?:0 ) : 0,
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

        $condition = [
            'prize_contest_record_id' => $filter['id'],
            'is_correct'              => Constants::YES_VALUE
        ];
        $correctNum = PrizeContestRecordItemService::getInstance()->getTotal($condition);

        $bestRank = PrizeContestRecordItemService::getInstance()->getBest($condition);

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

}