<?php
/**
 * BaseControllerTrait.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 2020-10-16 11:47
 */

namespace Service;

use Exception\Common\ApiInvalidArgumentException;
use Exception\Common\ApiInvalidArgumentLenOverLimitException;

/**
 * Trait BaseControllerTrait
 * @package Service
 */
trait BaseControllerTrait
{
    /**
     * 检测数组变量参数 - check => invalid && len limit
     *
     * @param array $list -- 校验的数组参数
     * <ul>
     *    <li>list.argument1 type desc</li>
     *    <li>list.argument2 type desc</li>
     * </ul>
     * @param array $necessaryParamArr
     * <ul>
     *    <li>necessaryParamArr.argumentName string desc</li>
     * </ul>
     * @param array $checkLenLimitList
     * <ul>
     *    <li>checkLenLimitList.argumentName => length limit</li>
     * </ul>
     *
     * @return boolean
     * @throws ApiInvalidArgumentException
     * @throws ApiInvalidArgumentLenOverLimitException
     */
    protected function checkArrayParamArgItem(array $list, array $necessaryParamArr, array $checkLenLimitList)
    {
        if (empty($list) || !is_array($list) || !count($list)) {
            return true;
        }
        $isCheckApiParamArgExist = true;
        if (empty($necessaryParamArr) || !is_array($necessaryParamArr) || !count($necessaryParamArr)) {
            $isCheckApiParamArgExist = false;
        }
        $isCheckApiParamArgLenLimit = true;
        if (empty($checkLenLimitList) || !is_array($checkLenLimitList) || !count($checkLenLimitList)) {
            $isCheckApiParamArgLenLimit = false;
        }
        if (!$isCheckApiParamArgExist && !$isCheckApiParamArgLenLimit) {
            return true;
        }

        foreach ($list as $item) {
            if ($isCheckApiParamArgExist) {
                $this->checkApiInvalidArgument($necessaryParamArr, $item);
            }
            if ($isCheckApiParamArgLenLimit) {
                $this->checkApiInvalidArgumentLenOverLimit($checkLenLimitList, $item);
            }
        }

        return true;
    }

    /**
     * api 校验参数invalid
     * @param array $arguments
     *  @arguments string  name
     *  @arguments boolean isRequired
     *  @arguments string  default
     *  @arguments string  type
     * @param array $params
     * @param bool $isReturn
     *
     * @return array|bool
     * @throws ApiInvalidArgumentException
     */
    protected function checkApiArgument(array $arguments, array $params, $isReturn=false)
    {
        if (!is_array($arguments) || !count($arguments)) {
            return false;
        }

        $list = [];
        foreach ($arguments as $key => $rule) {
            if ($rule['isRequired'] == true) {
                $this->checkApiInvalidArgumentException($key, $params);
            }
            $list[$key] = $params[$key] ? $params[$key] : $rule['default'];
        }

        if ($isReturn) {
            return $list;
        }
        return true;
    }


    /**
     * api 校验参数invalid
     * @param array $argumentList
     * @param array $params
     * @param bool $isReturn
     *
     * @return array|bool
     * @throws ApiInvalidArgumentException
     */
    protected function checkApiInvalidArgument(array $argumentList, array $params, $isReturn=false)
    {
        if (!is_array($argumentList) || !count($argumentList)) {
            return false;
        }

        $list = [];
        foreach ($argumentList as $key) {
            $this->checkApiInvalidArgumentException($key, $params);
            $list[$key] = $params[$key];
        }

        if ($isReturn) {
            return $list;
        }
        return true;
    }

    /**
     * api 校验参数长度限制
     * @param array $argumentList
     * @param array $params
     * @param bool  $isReturn
     *
     * @return array|bool
     * @throws ApiInvalidArgumentLenOverLimitException
     */
    protected function checkApiInvalidArgumentLenOverLimit(array $argumentList, array $params, $isReturn=false)
    {
        if (!is_array($argumentList) || !count($argumentList)) {
            return false;
        }

        $list = [];
        foreach ($argumentList as $key => $len) {
            $this->checkApiInvalidArgumentLenOverLimitException($key, $params, $len);
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
     * @throws ApiInvalidArgumentException
     */
    protected function checkApiInvalidArgumentException($argument='', $data=array())
    {
        if (empty($data[$argument])) {
            throw new ApiInvalidArgumentException($argument);
        }

        return true;
    }

    /**
     * api 检测参数超长
     *
     * @param string $argument
     * @param array  $data
     * @param int    $limit
     *
     * @return bool
     * @throws ApiInvalidArgumentLenOverLimitException
     */
    protected function checkApiInvalidArgumentLenOverLimitException($argument='', $data=array(), $limit=20)
    {
        if (strlen($data[$argument]) > $limit) {
            throw new ApiInvalidArgumentLenOverLimitException(['name' => $argument, 'limit' => $limit]);
        }
        return true;
    }
}