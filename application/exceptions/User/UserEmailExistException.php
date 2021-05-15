<?php
/**
 * UserEmailExistException.php
 * 用户邮箱号已存在
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 2020-07-15 18:30
 */

namespace Exception\User;

use Exception\Base\ApiParamException;
use Exception\Base\ExceptionConstConfig;

class UserEmailExistException extends ApiParamException
{
    const ERROR_CODE = ExceptionConstConfig::DB_USER_EMAIL_EXIST_ERROR_CODE;
    const ERROR_MESSAGE = ExceptionConstConfig::DB_USER_EMAIL_EXIST_ERROR_MSG;

    /**
     * UserEmailExistException constructor.
     * @param string $argumentName
     */
    public function __construct($argumentName='')
    {
        $code = self::ERROR_CODE;
        $message = sprintf(self::ERROR_MESSAGE, $argumentName);
        parent::__construct($message, $code);
    }

}
