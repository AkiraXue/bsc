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
use Service\BaseTrait;
use Service\BaseService;

use Service\Activity\ActivityParticipateScheduleService;

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
        $activity = ActivityService::getInstance()->checkActivityByCode($participateSchedule['activity_code']);

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
        echo json_encode($totalNum);

//        if (!empty($record) && isset($record['id'])) {
//            $condition = [
//                'activity_code' => $participateSchedule['activity_code'],
//                'account_id'    => $accountId,
//                'activity_schedule_id' => $participateSchedule['id'],
//                'day'           => $participateSchedule['id'],
//                'is_related_knowledge' => $participateSchedule['id'],
//                'knowledge_id' => $participateSchedule['id'],
//                'is_asset_award' => $participateSchedule['id'],
//                'asset_num' => $participateSchedule['id'],
//                'is_knowledge' => $participateSchedule['id'],
//                'knowledge_time' => $participateSchedule['id'],
//                'is_punch'      => $participateSchedule['id'],
//                'punch_time'    => $participateSchedule['id'],
//                'punch_date'    => $participateSchedule['id'],
//                'recent_punch_date' => $participateSchedule['id'],
//                'next_punch_date' => $participateSchedule['id'],
//
//            ];
//        }


        /** 3. is_knowledge && current_day  */
        $currentDay = 1;
        $isKnowledge = Constants::NO_VALUE;
        $isPunch = Constants::NO_VALUE;
        $response = [
            'is_punch'     => $isPunch,
            'is_knowledge' => $isKnowledge,
            'activity'     => $activity,
            'current_day'  => $currentDay
        ];
        if (!empty($record) && isset($record['id'])) {
            $response['is_punch'] = $record['is_punch'];
            $response['is_knowledge'] = $record['is_knowledge'];
            $response['current_day'] = $record['current_day'];
        }
        return $response;
    }
#endregion

#region punch setting
#endregion
}

