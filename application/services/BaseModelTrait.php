<?php
/**
 * BaseModelTrait.php
 * toDo : 目前在api端做限定，db层先不做复查
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 2020-07-16 19:51
 */

namespace Service;

use Exception\Common\DBInvalidArgumentException;
use Exception\Common\DBVerifyArgumentTypeErrorException;
use Exception\Common\DBInvalidArgumentLenOverLimitException;
use Exception\Common\DBInvalidVerifyArgumentConfigException;

/**
 * Trait BaseModelTrait
 * @package Service
 */
trait BaseModelTrait
{

    /**
     * api 校验参数invalid
     * @param array $argments
     * @param array $params
     * @param bool $isReturn
     *
     * @return array|bool
     * @throws DbInvalidArgumentException
     */
    protected function checkDbInvalidArgument(array $argments, array $params, $isReturn=false)
    {
        if (
            !is_array($argments) || !count($argments) ||
            !is_array($params) || !count($params)
        ) {
            return false;
        }

        $list = [];
        foreach ($argments as $key) {
            $this->checkDbInvalidArgumentException($key, $params);
            $list[$key] = $params[$key];
        }

        if ($isReturn) {
            return $list;
        }
        return true;
    }

    /**
     * api 校验参数长度限制
     * @param array $argments
     * @param array $params
     * @param bool  $isReturn
     *
     * @return array|bool
     * @throws DBInvalidArgumentLenOverLimitException
     */
    protected function checkDbInvalidArgumentLenOverLimit(array $argments, array $params, $isReturn=false)
    {
        if (
            !is_array($argments) || !count($argments) ||
            !is_array($params) || !count($params)
        ) {
            return false;
        }

        $list = [];
        foreach ($argments as $key => $len) {
            $this->checkDbInvalidArgumentLenOverLimitException($key, $params, $len);
            $list[$key] = $params[$key];
        }

        if ($isReturn) {
            return $list;
        }
        return true;
    }
    /**
     * api 检测参数是否存在
     *
     * @param string $argument
     * @param array  $data
     *
     * @return bool
     * @throws DBInvalidArgumentException
     */
    protected function checkDbInvalidArgumentException($argument='', $data=array())
    {
        if (empty($data[$argument])) {
            throw new DBInvalidArgumentException($argument);
        }

        return true;
    }

    /**
     * db 检测参数超长
     *
     * @param string $argument
     * @param array  $data
     * @param int    $limit
     *
     * @return bool
     * @throws DBInvalidArgumentLenOverLimitException
     */
    protected function checkDbInvalidArgumentLenOverLimitException($argument='', $data=array(), $limit=20)
    {
        if (strlen($data[$argument]) > $limit) {
            throw new DBInvalidArgumentLenOverLimitException($argument, $limit);
        }
        return true;
    }

    /**
     * @验证参数数据完整性
     *
     * @param  array    $params
     * @params structure 数组结构
     * @params string name 字段名称
     * @params string value 字段值
     * @params boolean isNecessary 是否为必填项
     * @params string type 数据类型（string int tinyint bigint float date datetime enum）
     * @params array|string range 数据值的允许范围；可以为一个固定值：str，数值型可以是一个范围，如array(100,10000) array(NULL, 100) array(100, NULL)；枚举型是数组的值array(100,200)
     * @params array|int length 数据长度的允许范围；可以为一个固定值：9，也可以是一个范围，如array(100,10000) array(NULL, 100) array(100, NULL)
     * @params array type_flat 扩展属性，暂只对数值型有效
     *              isUnsigned:是否为无符号类型，默认为有符号
     *              length: 仅float有效，浮点数长度,与父级属性length二选一，否则有冲突
     *              precision:仅float有效，小数位数
     * @param string $funcName  当前调用的方法名
     * @param string $className 当前调用的类名
     *
     * @return array 通过验证的值
     * @throws DBInvalidArgumentException
     * @throws DBInvalidArgumentLenOverLimitException
     * @throws DBInvalidVerifyArgumentConfigException
     * @throws DBVerifyArgumentTypeErrorException
     */
    protected function validateParams(array $params, $funcName=__FUNCTION__, $className=__CLASS__)
    {
        $data = array();
        if (!is_array($params)) {
            return $data;
        }

        foreach ($params as $paramInfo) {
            unset($name);unset($value);unset($isNecessary);
            unset($type);unset($range);unset($length);unset($type_flag);

            $name = isset($paramInfo['name']) ? $paramInfo['name'] : '';
            $value = isset($paramInfo['value']) ? $paramInfo['value'] : '';

            isset($paramInfo['isNecessary']) && $isNecessary = $paramInfo['isNecessary'];
            isset($paramInfo['type']) && $type = strtolower($paramInfo['type']);
            isset($paramInfo['range']) && $range = $paramInfo['range'];
            isset($paramInfo['length']) && $length = $paramInfo['length'];
            isset($paramInfo['type_flag']) && $type_flag = $paramInfo['type_flag'];
            isset($paramInfo['value']) && $data[$name] = $value;

            if ('' == $name)  {
                throw new DBInvalidVerifyArgumentConfigException('name', $className, $funcName);
            }

            //是否为空
            if (isset($isNecessary) && '' == strval($value)) {
                if (true === $isNecessary)  {
                    throw new DBInvalidArgumentException($name, $className, $funcName);
                }
                //如果允许为空，则空值时，不再判断其他参数
                continue;
            }

            if (!isset($type)) { //验证数据类型
                throw new DBVerifyArgumentTypeErrorException($name, 'invalid type', $className, $funcName);
            }
            switch($type) {
                case 'int':
                    if (!is_numeric($value) || false !== strpos($value, '.')) {
                        throw new DBVerifyArgumentTypeErrorException($name, 'int', $className, $funcName);
                    } else {
                        //验证类型扩展属性
                        if (true === $type_flag['isUnsigned'] && ($value < 0 || $value > 4294967295)) {
                            throw new DBVerifyArgumentTypeErrorException($name, 'int && unsigned', $className, $funcName);
                        } elseif (
                            (!isset($type_flag['isUnsigned']) || false === $type_flag['isUnsigned']) &&
                            ($value < -2147483648  || $value > 2147483647)
                        ) {
                            throw new DBVerifyArgumentTypeErrorException($name, 'int && signed', $className, $funcName);
                        }
                    }
                    break;
                case 'tinyint':
                    if (!is_numeric($value) || false !== strpos($value, '.')) {
                        throw new DBVerifyArgumentTypeErrorException($name, 'tinyint', $className, $funcName);
                    } else {
                        //验证类型扩展属性
                        if (true === $type_flag['isUnsigned'] && ($value < 0 || $value > 255 )) {
                            throw new DBVerifyArgumentTypeErrorException($name, 'tinyint && unsigned', $className, $funcName);
                        } elseif (
                            (!isset($type_flag['isUnsigned']) || false === $type_flag['isUnsigned']) &&
                            ($value < -128   || $value > 127)
                        ) {
                            throw new DBVerifyArgumentTypeErrorException($name, 'tinyint && signed', $className, $funcName);
                        }

                    }
                    break;
                case 'bigint':
                    if (!is_numeric($value) || false !== strpos($value, '.')) {
                        throw new DBVerifyArgumentTypeErrorException($name, 'bigint', $className, $funcName);
                    } else {
                        //验证类型扩展属性
                        if (
                            true === $type_flag['isUnsigned'] &&
                            ($value < 0 || $value > 18446744073709551615)
                        ) {
                            throw new DBVerifyArgumentTypeErrorException($name, 'bigint  && unsigned', $className, $funcName);
                        } elseif (
                            (!isset($type_flag['isUnsigned']) || false === $type_flag['isUnsigned']) &&
                            ($value < -9223372036854775807  || $value > 9223372036854775807)
                        ) {
                            throw new DBVerifyArgumentTypeErrorException($name, 'bigint  && signed', $className, $funcName);
                        }
                    }
                    break;
                case 'float':
                    if (!is_numeric($value)) {
                        throw new DBVerifyArgumentTypeErrorException($name, 'float', $className, $funcName);
                    } elseif (isset($type_flag)) { //验证类型扩展属性
                        if (true === $type_flag['isUnsigned'] && $value < 0) {
                            throw new DBVerifyArgumentTypeErrorException($name, 'float && unsigned', $className, $funcName);
                        }
                        $f_len = intval($type_flag['length']);
                        $f_precision = intval($type_flag['precision']);
                        $maxValue = pow (10, $f_len) - 1;
                        ($f_precision > 0) && $maxValue = $maxValue / pow (10, $f_precision);
                        if ($value < - $maxValue || $value > $maxValue) {
                            throw new DBVerifyArgumentTypeErrorException($name, 'float && overLimit', $className, $funcName);
                        }
                        if (round($value, $f_precision) != $value) {
                            throw new DBVerifyArgumentTypeErrorException($name, 'float && precision', $className, $funcName);
                        }
                    }
                    break;
                case 'string':
                    if (!is_string($value) && !is_numeric($value)) {
                        throw new DBVerifyArgumentTypeErrorException($name, 'string', $className, $funcName);
                    }
                    break;
                case 'date':
                    $dateArr = preg_split ('/\D/',  $value, -1, PREG_SPLIT_NO_EMPTY);
                    if ((false === checkdate($dateArr[1], $dateArr[2], $dateArr[0]))) {
                        throw new DBVerifyArgumentTypeErrorException($name, 'date', $className, $funcName);
                    }
                    break;
                case 'datetime':
                    $dateArr = preg_split ('/\D/',  $value, -1, PREG_SPLIT_NO_EMPTY);
                    //检查日期是否正确
                    if ((false === checkdate($dateArr[1], $dateArr[2], $dateArr[0]))) {
                        throw new DBVerifyArgumentTypeErrorException($name, 'datetime && date error', $className, $funcName);
                    }
                    //检查时间是否正确
                    if ($dateArr[3] >= 24 || $dateArr[4] >= 60 || $dateArr[5] >= 60){
                        throw new DBVerifyArgumentTypeErrorException($name, 'datetime && time error', $className, $funcName);
                    }
                    break;
                case 'enum':
                    break;
                case 'array':
                    if (!is_array($value)) {
                        throw new DBVerifyArgumentTypeErrorException($name, 'array', $className, $funcName);
                    }
                    break;
                default:
                    throw new DBVerifyArgumentTypeErrorException($name, 'invalid type', $className, $funcName);
                    break;
            }

            //验证数据值范围
            if (isset($range)) {
                if ('enum' == $type) {
                    if (!is_array($range) || !in_array($value, $range)) {
                        throw new DBVerifyArgumentTypeErrorException($name,'enum with range in '. json_encode($range), $className, $funcName);
                    }
                } else if (is_array($range) && is_numeric($value)) {
                    if (NULL !== $range[0] && $value < $range[0]) {
                        throw new DBInvalidArgumentLenOverLimitException($name, $range[0], $className, $funcName);
                    }
                    if (NULL !== $range[1] && $value > $range[1]) {
                        throw new DBInvalidArgumentLenOverLimitException($name, $range[1], $className, $funcName);
                    }
                } elseif (is_string($range)) {
                    if  ($range <> $value) {
                        throw new DBInvalidArgumentLenOverLimitException($name, $range, $className, $funcName);
                    }
                } else {
                    throw new DBVerifyArgumentTypeErrorException($name, 'invalid range', $className, $funcName);
                }
            }

            //验证数据值长度
            if (isset($length)) {
                if (is_array($length)) {
                    $len = mb_strlen($value, 'UTF-8');
                    if (NULL !== $length[0] && $len < $length[0]) {
                        throw new DBInvalidArgumentLenOverLimitException($name, $length[0], $className, $funcName);
                    }
                    if (NULL !== $length[1] && $len > $length[1]) {
                        throw new DBInvalidArgumentLenOverLimitException($name, $length[1], $className, $funcName);
                    }
                } elseif (is_numeric($length) && $length > 0) {
                    if  ($length <> mb_strlen($value, 'UTF-8')) {
                        throw new DBInvalidArgumentLenOverLimitException($name, $length, $className, $funcName);
                    }
                } else {
                    throw new DBVerifyArgumentTypeErrorException($name, 'invalid length', $className, $funcName);
                }
            }
        }

        return $data;
    }

}
