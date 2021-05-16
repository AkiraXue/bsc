<?php
/**
 * ActivityCodeNotMatchSchedule.php
 * 活动唯一码不匹配排期
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 2020-07-15 18:30
 */

namespace Exception\Activity;

use Exception\Base\ApiParamException;
use Exception\Base\ExceptionConstConfig;

class ActivityCodeNotMatchSchedule extends ApiParamException
{
    const ERROR_CODE = ExceptionConstConfig::DB_ACTIVITY_CODE_NOT_MATCH_SCHEDULE_ERROR_CODE;
    const ERROR_MESSAGE = ExceptionConstConfig::DB_ACTIVITY_CODE_NOT_MATCH_SCHEDULE_ERROR_MSG;

    /**
     * ActivityCodeNotMatchSchedule constructor.
     */
    public function __construct()
    {
        $code = self::ERROR_CODE;
        $message = sprintf(self::ERROR_MESSAGE);
        parent::__construct($message, $code);
    }

}
