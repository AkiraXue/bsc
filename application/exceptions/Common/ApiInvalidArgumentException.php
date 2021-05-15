<?php
/**
 * ApiInvalidArgumentException.php
 * 接口的参数验证缺失
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 2020-07-14 10:03
 */

namespace Exception\Common;

use Exception\Base\ApiParamException;
use Exception\Base\ExceptionConstConfig;

/**
 * Class ApiInvalidArgumentException
 * @package Exception\Common
 */
class ApiInvalidArgumentException extends ApiParamException
{
    const ERROR_CODE = ExceptionConstConfig::API_ARGUMENT_INVALID_ERROR_CODE;
    const ERROR_MESSAGE = ExceptionConstConfig::API_ARGUMENT_INVALID_ERROR_MSG;

    /**
     * ApiInvalidArgumentException constructor.
     * @param string $argumentName
     */
    public function __construct($argumentName='')
    {
        $code = self::ERROR_CODE;
        $message = sprintf(self::ERROR_MESSAGE, $argumentName);
        parent::__construct($message, $code);
    }
}
