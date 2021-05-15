<?php
/**
 * DBInvalidArgumentException.php
 * 数据库操作的参数验证缺失
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 2020-07-14 10:21
 */

namespace Exception\Common;

use Exception\Base\DBParamException;
use Exception\Base\ExceptionConstConfig;

/**
 * Class DBInvalidArgumentException
 * @package Exception\Common
 */
class DBInvalidArgumentException extends DBParamException
{
    const ERROR_CODE = ExceptionConstConfig::DB_ARGUMENT_INVALID_ERROR_CODE;
    const ERROR_MESSAGE = ExceptionConstConfig::DB_ARGUMENT_INVALID_ERROR_MSG;

    /**
     * DBInvalidArgumentException constructor.
     * @param string $argumentName
     */
    /**
     * DBInvalidArgumentException constructor.
     * @param string $objName
     * @param string $argumentName
     * @param string $funcName
     */
    public function __construct($argumentName='', $objName='', $funcName='')
    {
        $code = self::ERROR_CODE;
        $message = sprintf(self::ERROR_MESSAGE, $argumentName, $objName, $funcName);
        parent::__construct($message, $code);
    }

}
