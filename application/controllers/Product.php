<?php
/**
 * Product.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/17/21 2:03 AM
 */

use Lib\Constants;

use Service\Product\ProductService;

/**
 * Class Topic
 */
class Topic extends MY_Controller
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

        $result = ProductService::getInstance()->save($data);

        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function get()
    {
        $data = $this->input->post(null, true);
        $necessaryParamArr = ['sku'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $data, true);
        $result = ProductService::getInstance()->checkBySku($filter['sku'], Constants::NO_VALUE);
        $this->_success($result);
    }

    /**
     * 搜索
     */
    public function find()
    {
        $data = $this->input->post(null, true);
        $result = ProductService::getInstance()->find($data);
        $this->_success($result);
    }
#endregion

}