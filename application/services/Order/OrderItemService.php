<?php
/**
 * OrderItemService.php
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
 * Class OrderItemService
 * @package Service\Order
 */
class OrderItemService extends BaseService
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

        empty($params['sku']) || $condition['sku'] = $params['sku'];
        empty($params['unique_code']) || $condition['unique_code'] = $params['unique_code'];
        empty($params['trade_no']) || $condition['trade_no'] = $params['trade_no'];

        empty($params['type']) || $condition['type'] = $params['type'];

        empty($params['name']) || $condition['name'] = $params['name'];

        empty($params['state']) || $condition['state'] = $params['state'];

        $page = $params['page'];
        $limit = $params['limit'];
        $page = !empty($page) ? intval($page) : 1;
        $limit = !empty($limit) ? intval($limit) : 10;

        $data =  IoC()->Order_item_model->find($condition, $count, $page, $limit);
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
        $necessaryParamArr = [
            'sku', 'unique_code', 'trade_no', 'type', 'price', 'name', 'pic', 'detail', 'remark'
        ];
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
            'sku'           => $filter['sku'],
            'unique_code'   => $filter['unique_code'],
            'trade_no'      => $filter['trade_no'],
            'type'          => $filter['type'],
            'price'         => $filter['price'],
            'name'          => $filter['name'],
            'pic'           => $filter['pic'],
            'detail'        => $filter['detail'],
            'remark'        => $filter['remark'],
            'state'         => $state,
        ];
        if ($params['id']) {
            $this->checkById($params['id']);
            $where = ['id' => $params['id']];
            $update = $condition;
            return IoC()->Order_item_model->_update($where, $update);
        } else {
            $insert = $condition;
            return IoC()->Order_item_model->_insert($insert);
        }
    }

    /**
     * @param array $params
     *
     * @return bool
     * @throws Exception
     */
    public function batchSave(array $params)
    {
        /** 1. check base params & group_code */
        $filter = $this->checkOrderItemEntryApiArgument($params);

        OrderService::getInstance()->checkOrderByTradeNo($filter['trade_no']);

        /** 2. get update & insert & delete data list */
        $skus = array_column($filter['item_list'], 'sku');
        $delCondition = [
            'no_skus' => $skus,
            'isAll'   => Constants::YES_VALUE
        ];
        $deleteOrderItemList =  IoC()->Order_item_model->find($delCondition, $count);
        $deleteIds = array_column($deleteOrderItemList, 'id');

        $oldCondition = [
            'skus'  => $skus,
            'isAll'         => Constants::YES_VALUE
        ];
        $oldOrderItemList = IoC()->Order_item_model->find($oldCondition, $count);
        $oldOrderItemList = array_column($oldOrderItemList, null, 'sku');

        $addList = [];
        $updateList = [];
        foreach ($filter['item_list'] as $orderItem) {
            if (array_key_exists($orderItem['sku'], $oldOrderItemList)) {
                $orderItem['id'] = $oldOrderItemList[$orderItem['sku']]['id'];
                $updateList[] = $orderItem;
            } else {
                $orderItem['trade_no'] = $params['trade_no'];
                $addList[] = $orderItem;
            }
        }

        /** 3. update & insert & delete data */
        if (is_array($deleteIds) && count($deleteIds) > 0) {
            IoC()->Order_item_model->batchDelete($deleteIds);
        }
        if (is_array($updateList) && count($updateList) > 0) {
            IoC()->Order_item_model->batchUpdate($updateList);
        }

        if (is_array($addList) && count($addList) > 0) {
            IoC()->Order_item_model->batchAdd($addList);
        }

        return true;
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
        $knowledge = IoC()->Knowledge_model->getByID($id);
        if (empty($knowledge)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('OrderItemObj', 'id');
        }
        return $knowledge;
    }

    /**
     * @param string  $tradeNo
     * @param string  $sku
     * @param integer $isThrowError
     *
     * @return array
     * @throws Exception
     */
    public function checkOrderByTradeNoAndSku(
        string $tradeNo,
        string $sku,
        $isThrowError=Constants::YES_VALUE
    ) {
        $condition = [
            'trade_no'  => $tradeNo,
            'sku'       => $sku,
        ];
        $orderItem = IoC()->Order_item_model->get($condition);
        if (empty($orderItem)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('OrderItemObj', 'trade_no & sku');
        }
        return $orderItem;
    }

    /**
     * @param $params
     * @return array|bool
     *
     * @throws Exception
     */
    public function checkOrderItemEntryApiArgument($params)
    {
        $necessaryParamArr = ['trade_no', 'item_list'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);

        $necessaryParamArr = ['sku', 'unique_code', 'type', 'price', 'name', 'pic', 'detail', 'remark'];
        $checkLenLimitList = [
            'sku'         => 50,
            'unique_code' => 50,
            'name'        => 50,
        ];
        $this->checkArrayParamArgItem($params['item_list'], $necessaryParamArr, $checkLenLimitList);

        return $filter;
    }
#endregion
}