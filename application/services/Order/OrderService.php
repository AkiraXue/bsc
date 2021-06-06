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

use Lib\Helper;
use Service\Asset\AssetService;
use Service\BaseTrait;
use Service\BaseService;

use Exception\Common\DBInvalidObjectException;
use Service\Product\ProductService;
use Service\Product\WmsService;

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

#region && purchase order
    /**
     * 下单
     *
     * @param array $params
     * @return int
     * @throws Exception
     */
    public function order(array $params)
    {
        /** 1. check base params */
        $necessaryParamArr = ['unique_code', 'trade_no', 'status', 'remark', 'sku_list'];
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

        $price = 0;
        $productList = [];
        foreach ($filter['sku_list'] as $sku => $itemList) {
            $product = ProductService::getInstance()->checkBySku($sku);
            // $product['itemList'] = $itemList;
            $productList[] = $product;
            $price += $product['price'];
        }

        /** 3. save topic info */
        $condition = [
            'unique_code'   => $filter['unique_code'],
            'trade_no'      => $filter['trade_no'],
            'status'        => $filter['status'],
            'remark'        => $filter['remark'],
            'state'         => $state,
            'price'         => $price,
            'purchase_time' => '',
        ];
        $orderId = IoC()->Order_model->_insert($condition);

        /** 4. save order item */
        $orderItemList = [];
        foreach ($productList as $product) {
            $orderItemList[] = [
                'trade_no'      => $filter['trade_no'],
                'unique_code'   => $filter['unique_code'],
                'sku'           => $product['sku'],
                'type'          => $product['type'],
                'name'          => $product['name'],
                'pic'           => $product['pic'],
                'price'         => $product['price'],
                'detail'        => $product['detail'],
                'remark'        => '',
                'state'         => Constants::YES_VALUE,
            ];
        }
        IoC()->Order_item_model->batchAdd($orderItemList);

        return $orderId;
    }

    /**
     * 发货
     *
     * @param array $params
     * @return mixed
     * @throws Exception
     */
    public function deliver(array $params)
    {
        /** 1. check base params */
        $necessaryParamArr = ['trade_no', 'item_list'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);
        $checkLenLimitList = [
            'trade_no'    => 50
        ];
        $this->checkApiInvalidArgumentLenOverLimit($checkLenLimitList, $params);

        /** 2. check trade_no */
        OrderService::getInstance()->checkOrderByTradeNo($filter['trade_no']);

        $itemList = [];
        foreach ($filter['item_list'] as $sku => $list) {
            $itemList[$sku] = $list;
        }

        /** 3. update order item info */
        $condition = [
            'trade_no'  => $filter['trade_no'],
            'isAll'     => Constants::YES_VALUE
        ];
        $orderItemList = IoC()->Order_item_model->find($condition, $count);

        $updateList = [];
        foreach ($orderItemList as $orderItem) {
            $sku = $orderItem['sku'];
            if (empty($sku) || empty($itemList[$sku])) {
                throw new Exception('订单关联的商品仓储信息缺失', 3001);
            }
            $itemInfo = $itemList[$sku];
            $updateList[] = [
                'id'     => $orderItem['id'],
                'remark' => json_encode($itemInfo),
            ];
        }
        if (count($updateList) > 0) {
            IoC()->Order_item_model->batchUpdate($updateList);
        }

        return true;
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

        $condition = [
            'trade_nos' => array_column($data, 'trade_no'),
            'isAll'      => Constants::YES_VALUE
        ];
        $orderItems = IoC()->Order_item_model->find($condition, $itemCount);
        $orderItemList = [];
        foreach ($orderItems as $orderItem) {
            $orderItem['remark'] = json_decode($orderItem['remark'], true);
            $orderItemList[$orderItem['trade_no']][] = $orderItem;
        }

        foreach ($data as &$order) {
            setlocale(LC_TIME, 'en_US');
            $order['change_time'] = gmstrftime("%d %b %Y", strtotime($order['created_at']));

            $itemList = $orderItemList[$order['trade_no']];

            /** toDo: update is_show && product_info */
            $order['name'] = '积分兑换';
            $order['is_show'] = Constants::NO_VALUE;
            $order['product_info'] = '';
            if ($itemList[0]['type'] == Constants::PRODUCT_TYPE_VIRTUAL) {
                $order['is_show'] = Constants::YES_VALUE;
                /** cycle remark info */
                $remarkList = array_column($itemList, 'remark');
                $productInfo = '';
                foreach ($remarkList as $remarks) {
                    foreach ($remarks as $remark) {
                        if (empty($remark['unique_code'])) {
                            continue;
                        }
                        $productInfo .= "卡号：" . $remark['unique_code'] . "\n 密码：" . $remark['unique_pass'] . "\n";
                    }
                }
                $order['product_info'] = $productInfo;
            }
            $order['item_list'] = $itemList;
        }

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

    /**
     * @param $skuId
     * @param $type
     *
     * @return string
     * @throws Exception
     */
    public function makeTradeNo($skuId, $type)
    {

        return date('YmdHis') .'_'. $type . '_' . $skuId . '_' . Helper::random_string('alnum', '8');
    }
#endregion
}