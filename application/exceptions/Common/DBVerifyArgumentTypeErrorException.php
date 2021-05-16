<?php
/**
 * DBVerifyArgumentTypeErrorException.php
 * 参数校验类型错误
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 2020-08-12 11:09
 */

namespace Exception\Common;

use Exception\Base\DBParamException;
use Exception\Base\ExceptionConstConfig;

/**
 * Class DBVerifyArgumentTypeErrorException
 * @package Exception\Common
 */
class DBVerifyArgumentTypeErrorException extends DBParamException
{
    const ERROR_CODE = ExceptionConstConfig::DB_VERIFY_ARGUMENT_TYPE_ERROR_CODE;
    const ERROR_MESSAGE = ExceptionConstConfig::DB_VERIFY_ARGUMENT_TYPE_ERROR_MSG;

    /**
     * DBVerifyArgumentTypeErrorException constructor.
     * @param string $argumentName
     * @param string $type
     * @param string $objName
     * @param string $funcName
     */
    public function __construct($argumentName = '', $type = '', $objName = '', $funcName = '')
    {
        $code = self::ERROR_CODE;;
        $message = sprintf(self::ERROR_MESSAGE, $argumentName, $type, $objName, $funcName);
        parent::__construct($message, $code);
    }
}

