<?php
/**
 * GatewayAuthRequestMethodException.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 2020-08-12 09:24
 */

namespace Exception\Common;

use Exception\Base\GatewayAuthException;
use Exception\Base\ExceptionConstConfig;

/**
 * Class GatewayAuthRequestMethodException
 * @package Exception\Common
 */
class GatewayAuthRequestMethodException extends GatewayAuthException
{
    const ERROR_CODE = ExceptionConstConfig::GATEWAY_AUTH_REQUEST_METHOD_ERROR_CODE;
    const ERROR_MESSAGE = ExceptionConstConfig::GATEWAY_AUTH_REQUEST_METHOD_ERROR_MSG;

    /**
     * GatewayAuthInvalidArgumentException constructor.
     * @param string $argumentName
     */
    public function __construct($argumentName='')
    {
        $code = self::ERROR_CODE;
        $message = sprintf(self::ERROR_MESSAGE, $argumentName);
        parent::__construct($message, $code);
    }
}