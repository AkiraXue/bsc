<?php
/**
 * ApiVerifyArgumentTypeErrorException.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 2020-08-18 11:23
 */

namespace Exception\Common;

use Exception\Base\ApiParamException;
use Exception\Base\ExceptionConstConfig;

/**
 * Class ApiVerifyArgumentTypeErrorException
 * @package Exception\Common
 */
class ApiVerifyArgumentTypeErrorException extends ApiParamException
{
    const ERROR_CODE = ExceptionConstConfig::API_VERIFY_ARGUMENT_TYPE_ERROR_CODE;
    const ERROR_MESSAGE = ExceptionConstConfig::API_VERIFY_ARGUMENT_TYPE_ERROR_MSG;

    /**
     * ApiVerifyArgumentTypeErrorException constructor.
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
