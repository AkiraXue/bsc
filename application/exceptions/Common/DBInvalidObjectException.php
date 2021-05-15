<?php
/**
 * DBInvalidObjectException.php
 * 参数对应搜索的对象不存在
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 2020-07-14 11:48
 */

namespace Exception\Common;

use Exception\Base\DBParamException;
use Exception\Base\ExceptionConstConfig;

/**
 * Class DBInvalidUserObjectException
 * @package Exception\Common
 */
class DBInvalidObjectException extends DBParamException
{
    const ERROR_CODE = ExceptionConstConfig::DB_INVALID_OBJECT_ERROR_CODE;
    const ERROR_MESSAGE = ExceptionConstConfig::DB_INVALID_OBJECT_ERROR_MSG;

    /**
     * DBInvalidUserObjectException constructor.
     * @param string $objName
     * @param string $argumentStr
     */
    public function __construct($objName='', $argumentStr='')
    {
        $code = self::ERROR_CODE;
        $message = sprintf(self::ERROR_MESSAGE, $objName, $argumentStr);
        parent::__construct($message, $code);
    }

}

