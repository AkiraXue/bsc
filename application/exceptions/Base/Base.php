<?php
/**
 * Base.php
 * 基础异常拼接抛错
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 2020-07-13 15:56
 */

namespace Exception\Base;

use Exception;
use Throwable;

/**
 * Class Base
 *
 * 异常底层类
 *  1. 定义错误的级别
 *  2. 组装返回正式的错误code
 *  e.g.> 举例返回错误码 ：1001022001 => 1001 用户 02 应用错误  2接口参数错误  001 => 001 错误
 *        目前只有 2001 此类的四位错误
 *
 *  状态码， 说明如下：
 *     1 接口正常，
 *     1xxx 网关验证出错信息
 *     2xxx 接口的参数验证失败
 *     3xxx 接口的参数经过数据库验证时非法的
 *     4xxx 接口的对数据库的操作出现错误
 *
 * @package Exception\Base
 */
class Base
{
    /** 错误码长度限定 */
    const EXCEPTION_ERROR_CODE_LEN_LIMIT = 4;

    /**
     * 错误级别：网关验证错误 - 1 gateway error
     */
    const EXCEPTION_LEVEL_GATEWAY_AUTH_ERROR = 1;

    /**
     * 错误级别：接口的参数验证错误 - 2  api check params error
     */
    const EXCEPTION_LEVEL_API_PARAM_ERROR = 2;

    /**
     * 错误级别：接口的参数经过数据库验证时, 非法错误 - 3 db check params error
     */
    const EXCEPTION_LEVEL_DB_RECHECK_API_PARAM_ERROR = 3;

    /**
     * 错误级别：接口的对数据库的操作错误 - 4  db work fail
     */
    const EXCEPTION_LEVEL_DB_OPERATION_ERROR = 4;

    /**
     * 错误级别：未知错误位置 - 5  unknown error
     */
    const EXCEPTION_LEVEL_UNKNOWN_ERROR = 5;

    /**
     * 缺省错误码
     */
    const DEFAULT_ERROR_CODE = 999;

    /**
     * 为各类一场生成指定的code
     *
     * @param Exception \Throwable | Error $e
     *
     * @return string
     */
    public static function getErrorCode(Throwable $e)
    {
        $strCode = strval($e->getCode());

        /** old code compatibility && length >=4 return code */
        if (strlen($strCode) >= self::EXCEPTION_ERROR_CODE_LEN_LIMIT) {
            return $strCode;
        }

        /** code check */
        if ($strCode === '0' || $strCode === '') {
            $strCode = strval(self::DEFAULT_ERROR_CODE);
        }

        /**
         * 各业务的错误注册码
         */
        if ($e instanceof GatewayAuthException) {
            $errorLevel = self::EXCEPTION_LEVEL_GATEWAY_AUTH_ERROR;
        } else if ($e instanceof ApiParamException) {
            $errorLevel = self::EXCEPTION_LEVEL_API_PARAM_ERROR;
        } else if ($e instanceof DBParamException) {
            $errorLevel = self::EXCEPTION_LEVEL_DB_RECHECK_API_PARAM_ERROR;
        } else if($e instanceof  DBOperationException) {
            $errorLevel = self::EXCEPTION_LEVEL_DB_OPERATION_ERROR;
        } else {
            $errorLevel = self::EXCEPTION_LEVEL_UNKNOWN_ERROR;
        }

        /** 3xxx 默认填充3位数*/
        $strCode = str_pad($strCode, 3, '0', STR_PAD_LEFT);

        $strBaseErrorCode = static::splitJointErrorLevel($errorLevel, $strCode);

        return intval($strBaseErrorCode);
    }

    /**
     * 拼接异常级别
     *
     * @param integer    $errorLevel
     * @param string     $strCode
     *
     * @return string
     */
    private static function splitJointErrorLevel($errorLevel, $strCode)
    {
        return strlen($strCode) ?  $errorLevel . $strCode :  '';
    }
}
