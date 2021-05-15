<?php
/**
 * ApiInvalidArgumentLenOverLimitException.php
 * api校验参数超过长度限制
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 2020-07-15 14:54
 */

namespace Exception\Common;

use Exception\Base\ApiParamException;
use Exception\Base\ExceptionConstConfig;

/**
 * Class ApiInvalidArgumentLenOverLimitException
 * @package Exception\Common
 */
class ApiInvalidArgumentLenOverLimitException extends ApiParamException
{
    const ERROR_CODE = ExceptionConstConfig::API_ARGUMENT_LENGTH_OVER_LIMIT_ERROR_CODE;
    const ERROR_MESSAGE = ExceptionConstConfig::API_ARGUMENT_LENGTH_OVER_LIMIT_ERROR_MSG;

    /**
     * ApiInvalidArgumentLenOverLimitException constructor.
     * @param array $params
     */
    public function __construct(array $params=array())
    {
        $code = self::ERROR_CODE;
        $message = sprintf(self::ERROR_MESSAGE, $params['name'], $params['limit']);
        parent::__construct($message, $code);
    }
}
