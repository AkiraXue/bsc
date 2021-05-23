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

        $activitySchedule = ActivityScheduleService::getInstance()->checkById($filter['activity_schedule_id']);

        $userInfo = UserInfoService::getInstance()->checkByAccountId($filter['account_id']);

        /** 3. check data match logic */
        if ($activity['code'] != $activitySchedule['activity_code']) {
            throw new ActivityCodeNotMatchSchedule();
        }

        if ($userInfo['account_id'] != $activitySchedule['account_id']) {
            throw new AccountIdNotMatchSchedule();
        }

        /** 3. save activity participate record */
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
                'is_related_knowledge'  => $activitySchedule['is_related_knowledge'],
                'knowledge_id'          => $activitySchedule['knowledge_id'],
                'is_asset_award'        => $activitySchedule['is_asset_award'],
                'asset_num'             => $activitySchedule['asset_num'],
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
        return  IoC()->Activity_participate_record_model->count($condition);
    }
#endregion

}