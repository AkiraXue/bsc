<?php
/**
 * DBObjectHasExistException.php
 * 当前数据记录已存在
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 2020-07-14 10:21
 */

namespace Exception\Common;

use Exception\Base\DBParamException;
use Exception\Base\ExceptionConstConfig;

/**
 * Class DBObjectHasExistException
 * @package Exception\Common
 */
class DBObjectHasExistException extends DBParamException
{
    const ERROR_CODE = ExceptionConstConfig::DB_OBJECT_HAS_EXIST_ERROR_CODE;
    const ERROR_MESSAGE = ExceptionConstConfig::DB_OBJECT_HAS_EXIST_ERROR_MSG;

    /**
     * DBObjectHasExistException constructor.
     *
     * @param string $argumentName
     * @param string $objName
     * @param string $funcName
     */
    public function __construct($argumentName='', $objName='', $funcName='')
    {
        $code = self::ERROR_CODE;
        $message = sprintf(self::ERROR_MESSAGE, $argumentName, $objName, $funcName);
        parent::__construct($message, $code);
    }

}
