<?php
/**
 * DBInvalidArgumentLenOverLimitException.php
 * db校验参数超过长度限制
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 2020-07-16 19:44
 */

namespace Exception\Common;

use Exception\Base\DBParamException;
use Exception\Base\ExceptionConstConfig;

/**
 * Class DBInvalidArgumentLenOverLimitException
 * @package Exception\Common
 */
class DBInvalidArgumentLenOverLimitException extends DBParamException
{
    const ERROR_CODE = ExceptionConstConfig::DB_ARGUMENT_LENGTH_OVER_LIMIT_ERROR_CODE;
    const ERROR_MESSAGE = ExceptionConstConfig::DB_ARGUMENT_LENGTH_OVER_LIMIT_ERROR_MSG;

    /**
     * DBInvalidArgumentLenOverLimitException constructor.
     * @param $argumentName
     * @param $limit
     * @param string $className
     * @param string $funcName
     */
    public function __construct($argumentName, $limit, $className='', $funcName='')
    {
        $code = self::ERROR_CODE;
        $message = sprintf(self::ERROR_MESSAGE, $argumentName, $limit, $className, $funcName);
        parent::__construct($message, $code);
    }
}
