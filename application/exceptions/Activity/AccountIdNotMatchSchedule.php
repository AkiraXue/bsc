<?php
/**
 * AccountIdNotMatchSchedule.php
 * 用户accountId不匹配排期
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 2020-07-15 18:30
 */

namespace Exception\Activity;

use Exception\Base\ApiParamException;
use Exception\Base\ExceptionConstConfig;

class AccountIdNotMatchSchedule extends ApiParamException
{
    const ERROR_CODE = ExceptionConstConfig::DB_ACCOUNT_ID_NOT_MATCH_SCHEDULE_ERROR_CODE;
    const ERROR_MESSAGE = ExceptionConstConfig::DB_ACCOUNT_ID_NOT_MATCH_SCHEDULE_ERROR_MSG;

    /**
     * AccountIdNotMatchSchedule constructor.
     */
    public function __construct()
    {
        $code = self::ERROR_CODE;
        $message = sprintf(self::ERROR_MESSAGE);
        parent::__construct($message, $code);
    }

}
