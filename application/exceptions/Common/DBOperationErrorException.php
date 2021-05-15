<?php
/**
 * DBOperationErrorException.php
 * 数据库操作错误
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 2020-07-14 10:30
 */

namespace Exception\Common;

use Exception\Base\DBOperationException;
use Exception\Base\ExceptionConstConfig;

class DBOperationErrorException extends DBOperationException
{
    const ERROR_CODE = ExceptionConstConfig::DB_OPERATION_EXIST_ERROR_CODE;
    const ERROR_MESSAGE = ExceptionConstConfig::DB_OPERATION_EXIST_ERROR_MSG;

    /**
     * DBOperationErrorException constructor.
     * @param string $dbName
     */
    public function __construct($dbName='')
    {
        $code = self::ERROR_CODE;
        $message = sprintf(self::ERROR_MESSAGE, $dbName);
        parent::__construct($message, $code);
    }

}
