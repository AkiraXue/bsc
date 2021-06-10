<?php
/**
 * Order.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/17/21 1:02 AM
 */

use Lib\Constants;

use Service\Order\OrderService;
use Service\Order\TradeService;

/**
 * Class Order
 */
class Order extends MY_Controller
{
#region init
    public function __construct()
    {
        parent::__construct();
    }
#endregion

#region func
    /**
     * @throws Exception
     */
    public function save()
    {
        $data = $this->input->post(null, true);
        $result = OrderService::getInstance()->save($data);
        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function get()
    {
        $data = $this->input->post(null, true);
        $necessaryParamArr = ['trade_no'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $data, true);
        $result = OrderService::getInstance()->checkOrderByTradeNo(
            $filter['trade_no'], Constants::NO_VALUE
        );
        $this->_success($result);
    }

    /**
     * 搜索
     */
    public function find()
    {
        $data = $this->input->post(null, true);
        $result = OrderService::getInstance()->find($data);
        $this->_success($result);
    }
#endregion

#region purchase
    /**
     * @throws Exception
     */
    public function checkOrderPurchase()
    {
        $data = $this->input->post(null, true);
        $data['accountId'] = $this->accountId;
        $result = TradeService::getInstance()->checkOrderPurchase($data);
        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function purchase()
    {
        $data = $this->input->post(null, true);
        $data['accountId'] = $this->accountId;
        $result = TradeService::getInstance()->purchase($data);
        $this->_success($result);
    }
#region

}