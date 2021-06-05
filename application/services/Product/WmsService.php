<?php
/**
 * WmsService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 6/5/21 4:30 PM
 */

namespace Service\Product;

use Exception;

use Lib\Helper;
use Lib\Constants;

use Service\BaseTrait;
use Service\BaseService;

use Exception\Common\DBInvalidObjectException;

/**
 * Class WmsService
 * @package Service\Product
 */
class WmsService extends BaseService
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

#region base func
    /**
     * 库存
     *
     * @param array $params
     *
     * @return mixed
     * @throws Exception
     */
    public function inventory(array $params)
    {
        $condition = [];

        empty($params['sku']) || $condition['sku'] = $params['sku'];

        empty($params['status']) || $condition['status'] = $params['status'];
        empty($params['state']) || $condition['state'] = $params['state'];

        $page = $params['page'];
        $limit = $params['limit'];
        $page = !empty($page) ? intval($page) : 1;
        $limit = !empty($limit) ? intval($limit) : 10;

        $data = IoC()->Inventory_model->find($condition, $count, $page, $limit);
        $totalPage = ceil($count / $limit);
        $totalPage = $totalPage ? $totalPage : 1;
        return [
            'list' => $data,
            'total' => $count,
            'total_page' => $totalPage
        ];
    }

    /**
     * 入库
     *
     * @param array $params
     *
     * @return mixed
     * @throws Exception
     */
    public function storage(array $params)
    {
        /** 1. check base params */
        $filter = $this->checkEntryApiArgument($params);
        $itemList = $filter['item_list'];

        /** 2. check old inventory  */

        /** 2.1 format data */
        $skuList = $this->formatSkuList($itemList);

        /** 2.2 filter old exist data */
        $this->checkOldStorage($skuList);

        /** 3. add sku list */
        foreach ($skuList as $sku => $itemList) {
            /** 入库 */
            IoC()->Inventory_model->batchAdd($itemList);

            /** 更新库存 */
            $storage = count($itemList);
            IoC()->Product_model->storage($sku, $storage);
        }

        return true;
    }

    /**
     * 出库
     *
     * @param array $params
     *
     * @return mixed
     * @throws Exception
     */
    public function delivery(array $params)
    {
        /** 1. check base params */
        $filter = $this->checkEntryApiArgument($params);
        $itemList = $filter['item_list'];

        /** 2. check old inventory  */
        $skuList = $this->formatSkuList($itemList);

        $productList = [];
        foreach ($skuList as $sku => $itemList) {
            $product = ProductService::getInstance()->checkBySku($sku);

            if ($product['storage'] < count($itemList)) {
                throw new Exception('库存不足, 导出数量：' . count($itemList). ' > 实际库存：' . $product['storage'] , 3001);
            }
            $productList[$sku] = $product;
        }

        /** 3. export sku list */
        foreach ($skuList as $sku => $itemList) {
            /** 3.1 出库 */
            if ($productList[$sku]['type'] == Constants::PRODUCT_TYPE_VIRTUAL) {
                foreach ($itemList as &$item) {
                    $item['status'] = Constants::PRODUCT_STATUS_DELIVER;
                }
                IoC()->Inventory_model->batchUpdate($itemList);
            }
            /** 3.2 更新库存 */
            $storage = count($itemList);
            IoC()->Product_model->delivery($sku, $storage);
        }

        return true;
    }
#endregion

#region format && export
    /**
     * 格式化表单数据
     *
     * @param $itemList
     * @return array
     * @throws Exception
     */
    private function formatSkuList($itemList)
    {
        /** 2.1 format data */
        $skuList = [];
        $errorList = [];
        foreach ($itemList as $item) {
            if (empty($item['sku'])) {
                $item['error_msg'] = 'sku不可为空';
                $errorList[] = $item;
                continue;
            }
            $product =  ProductService::getInstance()->checkBySku($item['sku'], Constants::NO_VALUE);
            if (empty($product) || count($product) == 0) {
                $item['error_msg'] = '当前sku不存在 or 已下架';
                $errorList[] = $item;
                continue;
            }

            $skuList[$item['sku']][] = $item;
        }

        if (count($errorList) > 0) {
            throw new Exception('表单数据存在问题，sku缺失，errorList => ' . json_encode($errorList));
        }

        return $skuList;
    }

    /**
     * @param $skuList
     * @return bool
     * @throws Exception
     */
    private function checkOldStorage($skuList)
    {
        $oldExistList = [];
        foreach ($skuList as $skuItems) {
            $uniqueCodes = array_column($skuItems, 'unique_code');

            /** check is exist effective data */
            $condition = [
                'unique_codes' => $uniqueCodes,
                'state'        => Constants::YES_VALUE,
                'isAll'        => Constants::YES_VALUE,
            ];
            $oldItems = IoC()->Inventory_model->find($condition, $count);
            if (count($oldItems) == 0) {
                continue;
            }
            $oldExistList = array_merge($oldExistList, $oldItems);
        }

        if (count($oldExistList) > 0) {
            throw new Exception('存在未出库清理的老数据，oldList => ' . json_encode($oldExistList));
        }
        return true;
    }
#endregion

#region base func
    /**
     * @param string $sku
     * @param integer $isThrowError
     *
     * @return array
     * @throws Exception
     */
    public function checkBySku(string $sku, $isThrowError = Constants::YES_VALUE)
    {
        $condition = [
            'sku'    => $sku,
            'status' => Constants::PRODUCT_STATUS_STORAGE,
            'state'  => Constants::YES_VALUE
        ];
        $inventory = IoC()->Inventory_model->findOne($condition);
        if (empty($inventory)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new Exception('当前商品可兑换库存量为空', 3001);
        }
        return $inventory;
    }

    /**
     * @param integer $id
     * @param integer $isThrowError
     *
     * @return array
     * @throws Exception
     */
    public function checkById(int $id, $isThrowError = Constants::YES_VALUE)
    {
        $product = IoC()->Product_model->getByID($id);
        if (empty($product)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('ProductObj', 'id');
        }
        return $product;
    }


    /**
     * @param $params
     * @return array|bool
     *
     * @throws Exception
     */
    public function checkEntryApiArgument($params)
    {
        $necessaryParamArr = ['item_list'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);

        $necessaryParamArr = ['sku', 'name', 'unique_code', 'unique_pass'];
        $checkLenLimitList = [
            'name'          => 50,
            'unique_code'   => 254,
            'unique_pass'   => 254,
        ];
        $this->checkArrayParamArgItem($params['item_list'], $necessaryParamArr, $checkLenLimitList);

        return $filter;
    }
#endregion
}