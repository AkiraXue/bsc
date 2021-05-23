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

use Service\Asset\AssetService;
use Service\BaseTrait;
use Service\BaseService;

use Exception;
use Exception\Common\DBInvalidObjectException;
use Service\Knowledge\TopicServices;
use Service\User\UserInfoService;

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
    public function init($accountId, $date)
    {
        $prizeContest = PrizeContestService::getInstance()->getCurrentConfig();
        $prizeContestId = $prizeContest['id'];
        $params = [
            'account_id' => $accountId,
            'prize_contest_id' => $prizeContestId,
            'date' => $date,
        ];
        return PrizeContestRecordService::getInstance()->save($params);
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
     * @return bool
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

        /** 3. get related topic info */
        $topic = TopicServices::getInstance()->checkById($item['topic_id']);

        /** 4. check is correct */
        $correctChoice = $topic['content']['answer_num'];
        $isCorrect = ($filter['answer'] == $correctChoice) ? Constants::YES_VALUE : Constants::NO_VALUE;

        /** 5. check prize schedule && prize */
        $condition = [
            'prize_contest_id' => $item['prize_contest_id'],
            'sort'             => $item['sort']
        ];
        $schedule = IoC()->Prize_contest_schedule_model->get($condition);
        if (empty($schedule) || !isset($schedule['id'])) {
            throw new DBInvalidObjectException('PrizeContestScheduleObj', 3001);
        }

        /** 6. check is close current record */
        $where = ['id' => $filter['item_id']];
        $condition = [
            'draft'     => $filter['answer'],
            'answer'    => $correctChoice,
            'is_correct'=> $isCorrect,
            'is_asset_award' => $schedule['is_asset_award'],
            'asset_num' => $schedule['asset_num'],
        ];
        IoC()->Prize_contest_record_item_model->_update($where, $condition);

        /** 7. asset update */
        if ($isCorrect == Constants::YES_VALUE) {
            $type = 'jifen';
            AssetService::getInstance()->storage(
                $filter['account_id'], $schedule['asset_num'], $type, '冲顶答题'
            );
        }

        return true;
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


    }
#endregion

}