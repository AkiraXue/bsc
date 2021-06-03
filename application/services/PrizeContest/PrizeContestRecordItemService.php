<?php
/**
 * PrizeContestRecordItemService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/22/21 9:59 PM
 */

namespace Service\PrizeContest;

use Lib\Constants;

use Service\BaseTrait;
use Service\BaseService;
use Service\Knowledge\KnowledgeService;
use Service\Knowledge\TopicServices;
use Service\User\UserInfoService;

use Exception;
use Exception\Common\DBInvalidObjectException;

/**
 * Class PrizeContestRecordItemService
 * @package Service\PrizeContest
 */
class PrizeContestRecordItemService extends BaseService
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

#region
    /**
     * 题集
     *
     * @param int $num
     *
     * @return array
     */
    public function refreshProblemSet(int $num)
    {
        /** toDo: return mock data */
        return [
            ['knowledge_id' => '1', 'topic_id' => '1'],
            ['knowledge_id' => '1', 'topic_id' => '10'],
            ['knowledge_id' => '1', 'topic_id' => '12'],
        ];

        /** 随机抽取题目，并生成当次的题目列表 */
        $topicList = TopicServices::getInstance()->randomTopic($num);
        if (empty($topicList) || !isset($topicList)) {
            return [];
        }
        return $topicList ?: [];
    }

    /**
     * @param array $params
     *
     * @return int
     * @throws Exception
     */
    public function save(array $params)
    {
        /** 1. check base params */
        $necessaryParamArr = [
            'prize_contest_record_id', 'prize_contest_id', 'account_id', 'date', 'knowledge_id', 'topic_id', 'sort',
        ];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);
        $checkLenLimitList = [
            'account_id' => 50,
            'date' => 50,
        ];
        $this->checkApiInvalidArgumentLenOverLimit($checkLenLimitList, $params);

        /** 2. check data */
        TopicServices::getInstance()->checkById($filter['topic_id']);
        KnowledgeService::getInstance()->checkById($filter['knowledge_id']);
        UserInfoService::getInstance()->checkByAccountId($filter['account_id']);
        PrizeContestService::getInstance()->checkPrizeContentById($filter['prize_contest_id']);
        PrizeContestRecordService::getInstance()->checkPrizeContentRecordById($filter['prize_contest_record_id']);

        /** 3. save prize contest schedule info */
        $condition = [
            'prize_contest_record_id'   => $filter['prize_contest_record_id'],
            'prize_contest_id'          => $filter['prize_contest_id'],
            'account_id'                => $filter['account_id'],
            'date'                      => $filter['date'],
            'knowledge_id'              => $filter['knowledge_id'],
            'topic_id'                  => $filter['topic_id'],
            'sort'                      => $filter['sort']
        ];
        return IoC()->Prize_contest_schedule_model->_insert($condition);
    }

    public function find(array $params)
    {
        $condition = [];

        empty($params['prize_contest_record_id']) || $condition['prize_contest_record_id'] = $params['prize_contest_record_id'];
        empty($params['prize_contest_id']) || $condition['prize_contest_id'] = $params['prize_contest_id'];
        empty($params['account_id']) || $condition['account_id'] = $params['account_id'];
        empty($params['date']) || $condition['date'] = $params['date'];
        empty($params['knowledge_id']) || $condition['knowledge_id'] = $params['knowledge_id'];
        empty($params['topic_id']) || $condition['topic_id'] = $params['topic_id'];
        empty($params['is_correct']) || $condition['is_correct'] = $params['is_correct'];
        empty($params['is_asset_award']) || $condition['is_asset_award'] = $params['is_asset_award'];
        empty($params['sort']) || $condition['sort'] = $params['sort'];

        empty($params['state']) || $condition['state'] = $params['state'];

        $page = $params['page'];
        $limit = $params['limit'];
        $page = !empty($page) ? intval($page) : 1;
        $limit = !empty($limit) ? intval($limit) : 10;

        $data =  IoC()->Prize_contest_record_item_model->find($condition, $count, $page, $limit);
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
     * @return mixed
     */
    public function getTotal(array $params)
    {
        $condition = [];

        empty($params['prize_contest_record_id']) || $condition['prize_contest_record_id'] = $params['prize_contest_record_id'];
        empty($params['prize_contest_id']) || $condition['prize_contest_id'] = $params['prize_contest_id'];
        empty($params['account_id']) || $condition['account_id'] = $params['account_id'];
        empty($params['date']) || $condition['date'] = $params['date'];
        empty($params['knowledge_id']) || $condition['knowledge_id'] = $params['knowledge_id'];
        empty($params['topic_id']) || $condition['topic_id'] = $params['topic_id'];
        empty($params['is_correct']) || $condition['is_correct'] = $params['is_correct'];
        empty($params['is_asset_award']) || $condition['is_asset_award'] = $params['is_asset_award'];
        empty($params['sort']) || $condition['sort'] = $params['sort'];
        empty($params['state']) || $condition['state'] = $params['state'];

        return   IoC()->Prize_contest_record_item_model->getTotal($condition);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function getBest(array $params)
    {
        $condition = [];

        empty($params['prize_contest_record_id']) || $condition['prize_contest_record_id'] = $params['prize_contest_record_id'];
        empty($params['prize_contest_id']) || $condition['prize_contest_id'] = $params['prize_contest_id'];
        empty($params['account_id']) || $condition['account_id'] = $params['account_id'];
        empty($params['date']) || $condition['date'] = $params['date'];
        empty($params['knowledge_id']) || $condition['knowledge_id'] = $params['knowledge_id'];
        empty($params['topic_id']) || $condition['topic_id'] = $params['topic_id'];
        empty($params['is_correct']) || $condition['is_correct'] = $params['is_correct'];
        empty($params['is_asset_award']) || $condition['is_asset_award'] = $params['is_asset_award'];
        empty($params['sort']) || $condition['sort'] = $params['sort'];
        empty($params['state']) || $condition['state'] = $params['state'];

        $condition['orderBy'] = ['sort' => 'desc'];

        return   IoC()->Prize_contest_record_item_model->findOne($condition);
    }
#endregion

#region base func
    /**
     * @param integer  $id
     * @param integer  $isThrowError
     *
     * @return array
     * @throws Exception
     */
    public function checkPrizeContentRecordItemById(int $id, $isThrowError=Constants::YES_VALUE)
    {
        $condition = ['id'  => $id];
        $prizeContestRecordItem = IoC()->Prize_contest_record_item_model->get($condition);
        if (empty($prizeContestRecordItem)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('PrizeContestRecordItemObj', 'id');
        }
        return $prizeContestRecordItem;
    }
#endregion
}