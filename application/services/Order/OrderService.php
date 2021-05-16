<?php
/**
 * OrderService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/17/21 1:02 AM
 */

namespace Service\Order;

use Exception;

use Lib\Constants;

use Service\BaseTrait;
use Service\BaseService;

use Exception\Common\DBInvalidObjectException;

/**
 * Class OrderService
 * @package Service\Order
 */
class OrderService extends BaseService
{
    use BaseTrait;

#region initial info
    public static $instance;

    public function __construct()
    {
        parent::__construct();
    }

    public static function getInstance()
    {
        if (!self::$instance instanceof self){
            self::$instance = new self() ;
        }
        return self::$instance;
    }
#endregion

#region func
    public function find(array $params)
    {
        $condition = [];

        empty($params['unique_code']) || $condition['unique_code'] = $params['unique_code'];
        empty($params['trade_no']) || $condition['trade_no'] = $params['trade_no'];

        empty($params['status']) || $condition['status'] = $params['status'];

        empty($params['state']) || $condition['state'] = $params['state'];

        $page = $params['page'];
        $limit = $params['limit'];
        $page = !empty($page) ? intval($page) : 1;
        $limit = !empty($limit) ? intval($limit) : 10;

        $data =  IoC()->Order_model->find($condition, $count, $page, $limit);
        $totalPage = ceil($count / $limit);
        $totalPage = $totalPage ? $totalPage : 1;
        return [
            'list'       => $data,
            'total'      => $count,
            'total_page' => $totalPage
        ];
    }

    /**
     * @param array $params
     *
     * @return int
     * @throws Exception
     */
    public function save(array $params)
    {
        /** 1. check base params */
        $necessaryParamArr = ['unique_code', 'trade_no', 'status', 'remark'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);
        $checkLenLimitList = [
            'unique_code' => 50,
            'trade_no'    => 50
        ];
        $this->checkApiInvalidArgumentLenOverLimit($checkLenLimitList, $params);

        /** 2. check data */
        $state = Constants::YES_VALUE;
        if ($params['state'] && in_array($params['state'], [Constants::YES_VALUE, Constants::NO_VALUE])) {
            $state = $params['state'];
        }

        /** 3. save topic info */
        $condition = [
            'unique_code'   => $filter['unique_code'],
            'trade_no'      => $filter['trade_no'],
            'status'        => $filter['status'],
            'remark'        => $filter['remark'],
            'state'         => $state,
            'price'         => $params['price'] ?: '',
            'purchase_time' => $params['purchase_time'] ?: '',
        ];
        if ($params['trade_no']) {
            $this->checkOrderByTradeNo($params['trade_no']);
            $where = ['trade_no' => $params['trade_no']];
            $update = $condition;
            return IoC()->Order_model->_update($where, $update);
        } else {
            $insert = $condition;
            return IoC()->Order_model->_insert($insert);
        }
    }

#endregion

#region base func
    /**
     * @param integer  $id
     * @param integer $isThrowError
     *
     * @return array
     * @throws Exception
     */
    public function checkById(int $id, $isThrowError=Constants::YES_VALUE)
    {
        $knowledge = IoC()->Order_model->getByID($id);
        if (empty($knowledge)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('OrderObj', 'id');
        }
        return $knowledge;
    }

    /**
     * @param string  $tradeNo
     * @param integer $isThrowError
     *
     * @return array
     * @throws Exception
     */
    public function checkOrderByTradeNo(string $tradeNo, $isThrowError=Constants::YES_VALUE)
    {
        $condition = [
            'trade_no'  => $tradeNo,
        ];
        $order = IoC()->Order_model->get($condition);
        if (empty($order)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('OrderObj', 'trade_no');
        }

        $condition = [
            'trade_no'  => $tradeNo,
            'isAll'     => Constants::YES_VALUE
        ];
        $itemList = OrderItemService::getInstance()->find($condition);
        $order['item_list'] = $itemList['list'] ?: [];
        return $order;
    }
#endregion
}