<?php
/**
 * UserUcenterAccountInitialErrorException.php
 * Ucenter账户初始化失败
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 2020-07-16 15:38
 */

namespace Exception\User;

use Exception\Base\ApiParamException;
use Exception\Base\ExceptionConstConfig;

class UserUcenterAccountInitialErrorException extends ApiParamException
{
    const ERROR_CODE = ExceptionConstConfig::API_USER_UCENTER_ACCOUNT_INITIAL_ERROR_CODE;
    const ERROR_MESSAGE = ExceptionConstConfig::API_USER_UCENTER_ACCOUNT_INITIAL_ERROR_MSG;

    /**
     * UserEmailExistException constructor.
     */
    public function __construct()
    {
        $code = self::ERROR_CODE;
        $message = self::ERROR_MESSAGE;
        parent::__construct($message, $code);
    }

}
