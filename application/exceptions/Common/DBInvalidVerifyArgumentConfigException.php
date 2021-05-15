<?php
/**
 * DBInvalidVerifyArgumentConfigException.php
 * 数据入参校验配置错误
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 2020-08-12 11:10
 */

namespace Exception\Common;

use Exception\Base\DBParamException;
use Exception\Base\ExceptionConstConfig;

/**
 * Class DBInvalidVerifyArgumentConfigException
 * @package Exception\Common
 */
class DBInvalidVerifyArgumentConfigException extends DBParamException
{
    const ERROR_CODE = ExceptionConstConfig::DB_INVALID_VERIFY_ARGUMENT_CONFIG_ERROR_CODE;
    const ERROR_MESSAGE = ExceptionConstConfig::DB_INVALID_VERIFY_ARGUMENT_CONFIG_ERROR_MSG;

    /**
     * DBInvalidVerifyArgumentConfigException constructor.
     * @param string $objName
     * @param string $funcName
     * @param string $verifyName
     */
    public function __construct($verifyName='', $objName='', $funcName='')
    {
        $code = self::ERROR_CODE;;
        $message = sprintf(self::ERROR_MESSAGE, $verifyName, $objName, $funcName);
        parent::__construct($message, $code);
    }

}