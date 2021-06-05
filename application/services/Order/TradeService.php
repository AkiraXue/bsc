<?php
/**
 * TradeService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 6/6/21 12:44 AM
 */

namespace Service\Order;

use Exception;
use Lib\Constants;

use Service\BaseTrait;
use Service\BaseService;
use Service\Asset\AssetService;
use Service\Product\WmsService;
use Service\Product\ProductService;

/**
 * Class TradeService
 * @package Service\Order
 */
class TradeService extends BaseService
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
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }
#endregion

#region purchase
    /**
     * @param array $params
     *
     * @return array
     * @throws Exception
     */
    public function purchase(array $params)
    {
        /** 1. check base params */
        $necessaryParamArr = ['sku', 'accountId'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);
        $checkLenLimitList = ['sku' => 50];
        $this->checkApiInvalidArgumentLenOverLimit($checkLenLimitList, $params);

        /** 2. check sku state & storage */
        $product = ProductService::getInstance()->checkBySku($filter['sku']);
        if ($product['state'] == Constants::NO_VALUE) {
            throw new Exception('商品已下架', 3001);
        }
        if ($product['storage'] < 1) {
            throw new Exception('商品库存数量不足', 3001);
        }

        /** 3. check sku asset_num & user asset  */
        $userAsset = AssetService::getInstance()->checkByUniqueCode($filter['accountId'], Constants::ASSET_TYPE_JIFEN);
        if ($userAsset['remaining'] < $product['price']) {
            throw new Exception('个人积分不足', 3001);
        }

        /** 4. get product type => virtual or physical */
        /** 4.1 physical => check address  */
        if ($product['type'] == Constants::PRODUCT_TYPE_PHYSICAL) {
            if (empty($params['address'])) {
                throw new Exception('实体商品下单需要填报地址信息', 3001);
            }
        }

        /** 4.2 virtual => inventory status => 1 */
        /** toDo: 购物车 */
        $item = [
            'sku'           => $filter['sku'],
            'name'          => $product['name'],
            'unique_code'   => '',
            'unique_pass'   => ''
        ];
        if ($product['type'] == Constants::PRODUCT_TYPE_VIRTUAL) {
            $item = WmsService::getInstance()->checkBySku($filter['sku']);
            if (empty($item) || !isset($item['id'])) {
                throw new Exception('当前商品可兑换库存量为空', 3001);
            }
        }

        /** 5. create order & order item */
        $tradeNo = OrderService::getInstance()->makeTradeNo($product['id'], $product['type']);

        /** toDo: 购物车 */
        $skuList = [
            $filter['sku'] => [$item['id'] ? $item : []]
        ];
        $orderCondition = [
            'unique_code'   =>  $filter['accountId'],
            'trade_no'      =>  $tradeNo,
            'status'        =>  Constants::ORDER_STATUS_PROCESSING,
            'remark'        =>  json_encode(['address' => $params['address']]),
            'sku_list'      =>  $skuList
        ];
        OrderService::getInstance()->order($orderCondition);
        $order = OrderService::getInstance()->checkOrderByTradeNo($tradeNo);

        /** 6. 锁库出库 => lock storage => product storage - 1 && inventory item num - 1 */
        WmsService::getInstance()->delivery(['item_list' => [$item]]);

        /** 7. 积分变迁 asset change log && asset_num update */
        AssetService::getInstance()->purchase(
            $filter['accountId'], $order['price'], Constants::ASSET_TYPE_JIFEN, '商品兑换 - ' . $product['name']
        );

        /** 8. 发货 deliver =>  update order detail & remark */
        if ($product['type'] == Constants::PRODUCT_TYPE_VIRTUAL) {
            $orderCondition = [
                'trade_no'  => $order['trade_no'],
                'item_list' => $skuList
            ];
            OrderService::getInstance()->deliver($orderCondition);
        }

        /** 9. return order info */
        return $order;
    }
#endregion
}