<?php
/**
 * PunchService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/23/21 2:59 PM
 */

namespace Service\Punch;

use Exception;
use Lib\Constants;

use Service\Activity\ActivityParticipateRecordService;
use Service\Activity\ActivityScheduleService;
use Service\Activity\ActivityService;
use Service\Asset\AssetService;
use Service\BaseTrait;
use Service\BaseService;

use Service\Activity\ActivityParticipateScheduleService;
use Service\Knowledge\KnowledgeService;

/**
 * Class PunchService
 * @package Service\Punch
 */
class PunchService extends BaseService
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
     * @param $accountId
     * @param $date
     *
     * @return mixed
     * @throws Exception
     */
    public function  getConfig($accountId, $date='')
    {
        /** 1. check init info*/
        $response = $this->initPunch($accountId);
        $activity = ActivityService::getInstance()->checkActivityByCode($response['activity_code']);

        /** 2. is_knowledge && current_day  */
        $isKnowledge = Constants::NO_VALUE;
        $isPunch = Constants::NO_VALUE;

        $date = date('Y-m-d');
        $record = ActivityParticipateRecordService::getInstance()->checkByActivityCodeAndAccountIdAndDate(
            $response['activity_code'], $accountId, $date, Constants::NO_VALUE
        );

        $response = [
            'is_punch'     => $record['is_punch'] ?: $isPunch,
            'is_knowledge' => $record['is_knowledge'] ?:$isKnowledge,
            'current_day'  => $record['day'],
            'activity'     => $activity,
        ];
        return $response;
    }

    /**
     * 打卡
     *
     * @param $accountId
     *
     * @return mixed
     * @throws Exception
     */
    public function punch($accountId)
    {
        $date = date('Y-m-d');

        /** 1. check schedule record list */
        $participateSchedule = ActivityParticipateScheduleService::getInstance()->checkByAccountId($accountId);

        $record = ActivityParticipateRecordService::getInstance()->checkByActivityCodeAndAccountIdAndDate(
            $participateSchedule['activity_code'], $accountId, $date
        );

        if ($record['is_punch'] == Constants::YES_VALUE) {
            throw new Exception('今日已打卡', 3001);
        }

        /** 2. check schedule record status */
        $where = ['id' => $record['id']];
        $update = [
            'is_punch'   => Constants::YES_VALUE,
            'punch_time' => date('Y-m-d H:i:s')
        ];
        IoC()->Activity_participate_record_model->_update($where, $update);

        /** 3. update asset  */
        $scheduleId = $record['activity_schedule_id'] ?: 0;
        if (!$scheduleId) {
            return true;
        }

        $schedule = ActivityScheduleService::getInstance()->checkById($scheduleId);
        if ($schedule['is_asset_award'] == Constants::NO_VALUE) {
           return true;
        }

        $where = ['id' => $record['id']];
        $update = [
            'is_asset_award'   => $schedule['is_asset_award'],
            'asset_num'        => $schedule['asset_num']
        ];
        IoC()->Activity_participate_record_model->_update($where, $update);

        /** 4. record asset change */
        AssetService::getInstance()->storage($accountId, $schedule['asset_num'], 'jifen', '每日打卡');

        return true;
    }

    /**
     * @param $accountId
     * @return array|bool
     * @throws Exception
     */
    public function knowledge($accountId)
    {
        $date = date('Y-m-d');

        /** 1. check schedule record list */
        $participateSchedule = ActivityParticipateScheduleService::getInstance()->checkByAccountId($accountId);

        $record = ActivityParticipateRecordService::getInstance()->checkByActivityCodeAndAccountIdAndDate(
            $participateSchedule['activity_code'], $accountId, $date
        );

        /** no related knowledge and no punch */
        if ($record['is_punch'] == Constants::NO_VALUE) {
            throw new Exception('今日未打卡', 3001);
        }
        if ($record['is_related_knowledge'] == Constants::NO_VALUE) {
            return [];
        }
        return $record['knowledge_id'];

        /** 2. get knowledge info */
        $knowledge = KnowledgeService::getInstance()->checkById($record['knowledge_id']);

        /** 3. check schedule record to get knowledge id */
        $where = ['id' => $record['id']];
        $update = [
            'is_knowledge'   => Constants::YES_VALUE,
            'knowledge_time' => date('Y-m-d H:i:s')
        ];
        IoC()->Activity_participate_record_model->_update($where, $update);

        return $knowledge;

    }
#endregion

#region punch setting
    /**
     * @param $accountId
     * @return mixed
     *
     * @throws Exception
     */
    public function initPunch($accountId)
    {
        /** 1. is exist punch record setting */
        $participateSchedule = ActivityParticipateScheduleService::getInstance()->checkByAccountId(
            $accountId, Constants::NO_VALUE
        );

        if (empty($participateSchedule) || !isset($participateSchedule['id'])) {
            ActivityParticipateScheduleService::getInstance()->initByAccountId($accountId);
            $participateSchedule =  ActivityParticipateScheduleService::getInstance()->checkByAccountId($accountId);
        }

        /** 2. check participate schedule record */
        $date = date('Y-m-d');
        $record = ActivityParticipateRecordService::getInstance()->checkByActivityCodeAndAccountIdAndDate(
            $participateSchedule['activity_code'], $accountId, $date, Constants::NO_VALUE
        );

        $condition = [
            'activity_code' => $participateSchedule['activity_code'],
            'isAll'         => Constants::YES_VALUE
        ];
        $activityScheduleRes = ActivityScheduleService::getInstance()->find($condition);
        $activityScheduleList = array_column($activityScheduleRes, null, 'day');

        /** 3. get current schedule list */
        $totalNum = ActivityParticipateRecordService::getInstance()->checkTotalNumByActivityCodeAndAccountId(
            $participateSchedule['activity_code'], $accountId
        );

        $day = $totalNum + 1;
        $schedule = $activityScheduleList[$day] ?:[];
        if (empty($record) && !isset($record['id']) ) {
            $condition = [
                'activity_code' => $participateSchedule['activity_code'],
                'account_id'    => $accountId,
                'activity_schedule_id' => $schedule['id']?:'',
                'day'           => $day,
                'is_related_knowledge' => $schedule['is_related_knowledge']?:Constants::NO_VALUE,
                'knowledge_id'  => $schedule['knowledge_id'],
                'is_asset_award' => $schedule['is_asset_award']?:Constants::NO_VALUE,
                'asset_num'     => $schedule['asset_num']?:'',
                'is_knowledge'  => Constants::NO_VALUE,
                'knowledge_time' => '',
                'is_punch'      => Constants::NO_VALUE,
                'punch_time'    => '',
                'punch_date'    => $date,
                'recent_punch_date' => '',
                'next_punch_date' => '',
            ];
            ActivityParticipateRecordService::getInstance()->save($condition);
        }
        return ['current_day' => $day, 'activity_code' => $participateSchedule['activity_code']];
    }
#endregion
}

