<?php
/**
 * ActivityParticipateScheduleService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/16/21 2:33 PM
 */

namespace Service\Activity;

use Exception;

use Lib\Constants;

use Service\BaseTrait;
use Service\BaseService;
use Service\User\UserInfoService;

use Exception\Common\DBInvalidObjectException;
use Exception\Common\DBObjectHasExistException;

/**
 * Class ActivityParticipateScheduleService
 * @package Service
 */
class ActivityParticipateScheduleService extends BaseService
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
     * @param array $params
     * @return array
     */
    public function find(array $params)
    {
        $condition = [];

        empty($params['activity_code']) || $condition['activity_code'] = $params['activity_code'];
        empty($params['account_id']) || $condition['account_id'] = $params['account_id'];

        empty($params['state']) || $condition['state'] = $params['state'];

        $page = $params['page'];
        $limit = $params['limit'];
        $page = !empty($page) ? intval($page) : 1;
        $limit = !empty($limit) ? intval($limit) : 10;

        $data =  IoC()->Activity_participate_schedule_model->find($condition, $count, $page, $limit);
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
        $necessaryParamArr = ['activity_code', 'account_id'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);
        $checkLenLimitList = [
            'activity_code' => 50,
            'account_id' => 50
        ];
        $this->checkApiInvalidArgumentLenOverLimit($checkLenLimitList, $params);

        /** 2. check activity_code && account_id */
        ActivityService::getInstance()->checkActivityByCode($filter['activity_code']);
        UserInfoService::getInstance()->checkByAccountId($filter['account_id']);

        /** 3. check old data */
        $oldAccountIdDataRecord = $this->checkByAccountId($filter['account_id'], Constants::NO_VALUE);
        if (!empty($oldAccountIdDataRecord) && isset($oldAccountIdDataRecord['id'])) {
            throw new DBObjectHasExistException('account_id', 'activityParticipateScheduleObj');
        }

        /** 4. add related info */
        $insert = [
            'activity_code' => $filter['activity_code'],
            'account_id'    => $filter['account_id'],
            'state'         => Constants::YES_VALUE
        ];
        return IoC()->Activity_participate_schedule_model->_insert($insert);
    }

    /**
     * @param string $accountId
     *
     * @return array
     * @throws Exception
     */
    public function getByAccountId(string $accountId)
    {
        UserInfoService::getInstance()->checkByAccountId($accountId);

        $record =  $this->checkByAccountId($accountId, Constants::NO_VALUE);
        if (empty($record) || !isset($record['activity_code'])) {
            return $record;
        }

        $condition = [
            'activity_code' => $record['activity_code'],
            'isAll'         => Constants::YES_VALUE
        ];
        $data = ActivityScheduleService::getInstance()->find($condition);

        $record['schedule_list'] = $data['list'] ?: [];

        return $record;
    }
#endregion

#region base func
    /**
     * @param string $activityCode
     * @param string $accountId
     * @param int $isThrowError
     *
     * @return array
     * @throws DBInvalidObjectException
     */
    public function checkByActivityCodeAndAccountId(
        string $activityCode,
        string $accountId,
        $isThrowError=Constants::YES_VALUE
    ) {
        $condition = [
            'activity_code'  => $activityCode,
            'account_id'     => $accountId,
        ];
        $activityParticipateSchedule = IoC()->Activity_participate_schedule_model->get($condition);
        if (empty($activityParticipateSchedule)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('ActivityParticipateScheduleObj', 'activity_code && account_id');
        }
        return $activityParticipateSchedule;
    }

    /**
     * @param string $accountId
     * @param int $isThrowError
     *
     * @return array
     * @throws DBInvalidObjectException
     */
    public function checkByAccountId(
        string $accountId,
        $isThrowError=Constants::YES_VALUE
    ) {
        $condition = [
            'account_id'     => $accountId,
            'state'          => Constants::YES_VALUE,
        ];
        $activityParticipateSchedule = IoC()->Activity_participate_schedule_model->get($condition);
        if (empty($activityParticipateSchedule)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('ActivityParticipateScheduleObj', 'account_id');
        }
        return $activityParticipateSchedule;
    }


    /**
     * @param string $accountId
     *
     * @return mixed
     * @throws Exception
     */
    public function initByAccountId(string $accountId) {
        $condition = [
            'account_id'     => $accountId,
            'state'          => Constants::YES_VALUE
        ];
        $activityParticipateSchedule = IoC()->Activity_participate_schedule_model->get($condition);
        if (!empty($activityParticipateSchedule)) {
            return [];
           // throw new Exception('current schedule has exist', 3001);
        }

        $activity = ActivityService::getInstance()->getCurrentActivity();

        return $this->save(['activity_code' => $activity['code'], 'account_id' => $accountId]);
    }
#endregion

}