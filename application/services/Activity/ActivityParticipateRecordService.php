<?php
/**
 * ActivityParticipateRecordService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/16/21 3:18 PM
 */

namespace Service\Activity;

use Exception;

use Lib\Constants;

use Service\BaseTrait;
use Service\BaseService;

use Service\User\UserInfoService;

use Exception\Common\DBInvalidObjectException;
use Exception\Activity\AccountIdNotMatchSchedule;
use Exception\Activity\ActivityCodeNotMatchSchedule;

/**
 * Class ActivityParticipateRecordService
 * @package Service
 */
class ActivityParticipateRecordService extends BaseService
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

#region common func
    /**
     * @param array $params
     * @return array
     */
    public function find(array $params)
    {
        $condition = [];

        empty($params['activity_code']) || $condition['activity_code'] = $params['activity_code'];
        empty($params['account_id']) || $condition['account_id'] = $params['account_id'];

        empty($params['day']) || $condition['day'] = $params['day'];
        empty($params['is_related_knowledge']) || $condition['is_related_knowledge'] = $params['is_related_knowledge'];
        empty($params['is_knowledge']) || $condition['is_knowledge'] = $params['is_knowledge'];
        empty($params['is_punch']) || $condition['is_punch'] = $params['is_punch'];

        empty($params['knowledge_id']) || $condition['knowledge_id'] = $params['knowledge_id'];
        empty($params['punch_date']) || $condition['punch_date'] = $params['punch_date'];
        empty($params['punch_date_start']) || $condition['punch_date_start'] = $params['punch_date_start'];
        empty($params['punch_date_end']) || $condition['punch_date_end'] = $params['punch_date_end'];

        empty($params['state']) || $condition['state'] = $params['state'];

        $page = $params['page'];
        $limit = $params['limit'];
        $page = !empty($page) ? intval($page) : 1;
        $limit = !empty($limit) ? intval($limit) : 10;

        $data =  IoC()->Activity_participate_record_model->findRecordLeftJoinUser($condition, $count, $page, $limit);
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
     * @return mixed
     * @throws Exception
     */
    public function save(array $params)
    {
        /** 1. check base params & activity_code */
        $necessaryParamArr = ['activity_code', 'account_id', 'day', 'activity_schedule_id'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);

        /** 2. check activity_code & account_id & day & activity_schedule_id */
        $activity = ActivityService::getInstance()->checkActivityByCode($filter['activity_code']);

        $userInfo = UserInfoService::getInstance()->checkByAccountId($filter['account_id']);

        $activitySchedule = [];
        if( $filter['activity_schedule_id']) {
            $activitySchedule = ActivityScheduleService::getInstance()->checkById(
                $filter['activity_schedule_id'], Constants::NO_VALUE
            );

            $activityParticipateSchedule = ActivityParticipateScheduleService::getInstance()->checkByAccountId($userInfo['account_id']);

            if ($activityParticipateSchedule['account_id'] != $userInfo['account_id']) {
                throw new AccountIdNotMatchSchedule();
            }
        }

        /** 3. check data match logic */
        if (!empty($activitySchedule) && isset($activitySchedule['id'])) {
            if ($activity['code'] != $activitySchedule['activity_code']) {
                throw new ActivityCodeNotMatchSchedule();
            }
        }

        /** 4. save activity participate record */
        if ($params['id']) {
            $this->checkById($params['id']);
            $where = ['id' => $params['code']];
            $update = [
                'is_knowledge'      => $params['is_knowledge'],
                'knowledge_time'    => $params['knowledge_time'],
                'is_punch'          => $params['is_punch'],
                'punch_time'        => $params['punch_time'],
                'punch_date'        => $params['punch_date'],
                'recent_punch_date' => $params['recent_punch_date'],
                'next_punch_date'   => $params['next_punch_date'],
            ];
            return IoC()->Activity_participate_record_model->_update($where, $update);
        } else {
            $insert = [
                'activity_code'         => $filter['activity_code'],
                'account_id'            => $filter['account_id'],
                'activity_schedule_id'  => $filter['activity_schedule_id'],
                'day'                   => $filter['day'],
                'is_related_knowledge'  => $activitySchedule['is_related_knowledge']?:Constants::NO_VALUE,
                'knowledge_id'          => $activitySchedule['knowledge_id']?:'',
                'is_knowledge_asset_award' => $activitySchedule['is_knowledge_asset_award']?:Constants::NO_VALUE,
                'knowledge_asset_num'   => $activitySchedule['knowledge_asset_num']?:'0',
                'is_asset_award'        => $activitySchedule['is_asset_award']?:Constants::NO_VALUE,
                'asset_num'             => $activitySchedule['asset_num']?:'',
                'punch_date'            => $params['punch_date'],
                'knowledge_time'        => '',
                'punch_time'            => '',
                'recent_punch_date'     => '',
                'next_punch_date'       => ''
            ];
            return IoC()->Activity_participate_record_model->_insert($insert);
        }
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
        $activityParticipateRecord = IoC()->Activity_participate_record_model->getByID($id);
        if (empty($activityParticipateRecord)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('ActivityParticipateRecordObj', 'id');
        }
        return $activityParticipateRecord;
    }

    /**
     * @param string $activityCode
     * @param string $accountId
     * @param string $date
     * @param int $isThrowError
     *
     * @return array
     * @throws DBInvalidObjectException
     */
    public function checkByActivityCodeAndAccountIdAndDate(
        string $activityCode,
        string $accountId,
        $date,
        $isThrowError=Constants::YES_VALUE
    ) {
        $condition = [
            'activity_code'  => $activityCode,
            'account_id'     => $accountId,
            'punch_date'     => $date,
        ];
        $activityParticipateRecord = IoC()->Activity_participate_record_model->get($condition);
        if (empty($activityParticipateRecord)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('ActivityParticipateRecordObj', 'activity_code && account_id');
        }
        return $activityParticipateRecord;
    }


    /**
     * @param string $activityCode
     * @param string $accountId
     *
     * @return array
     * @throws Exception
     */
    public function checkTotalNumByActivityCodeAndAccountId(
        string $activityCode,
        string $accountId
    ) {
        $condition = [
            'activity_code'  => $activityCode,
            'account_id'     => $accountId
        ];
        return  IoC()->Activity_participate_record_model->getTotal($condition);
    }
#endregion

}