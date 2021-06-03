<?php
/**
 * ProductService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/17/21 1:45 AM
 */

namespace Service\Product;

use Exception;

use Lib\Constants;

use Lib\Helper;
use Service\BaseTrait;
use Service\BaseService;

use Exception\Common\DBInvalidObjectException;

/**
 * Class ProductService
 * @package Service\Product
 */
class ProductService extends BaseService
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

#region func
    public function find(array $params)
    {
        $condition = [];

        empty($params['sku']) || $condition['sku'] = $params['sku'];
        empty($params['type']) || $condition['type'] = $params['type'];
        empty($params['name']) || $condition['name'] = $params['name'];
        empty($params['status']) || $condition['status'] = $params['status'];
        empty($params['state']) || $condition['state'] = $params['state'];

        $page = $params['page'];
        $limit = $params['limit'];
        $page = !empty($page) ? intval($page) : 1;
        $limit = !empty($limit) ? intval($limit) : 10;

        $data = IoC()->Product_model->find($condition, $count, $page, $limit);

        foreach ($data as &$item) {
            if (empty($item['pic'])) {
                continue;
            }
            $item['pic'] = strpos($item['pic'], '://') ?  $item['pic'] : CDN_HOST . $item['pic'];
        }

        $totalPage = ceil($count / $limit);
        $totalPage = $totalPage ? $totalPage : 1;
        return [
            'list' => $data,
            'total' => $count,
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
            'type', 'name', 'pic', 'price',  'detail', 'remark'
        ];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);
        $checkLenLimitList = [
            'name' => 50,
        ];
        $this->checkApiInvalidArgumentLenOverLimit($checkLenLimitList, $params);

        /** 2. check data */
        $state = Constants::YES_VALUE;
        if ($params['state'] && in_array($params['state'], [Constants::YES_VALUE, Constants::NO_VALUE])) {
            $state = $params['state'];
        }

        $sku = $filter['sku'] ?: $this->makeSkuNo($filter['type']);

        /** 3. save topic info */
        $condition = [
            'sku'   => $sku,
            'type'  => $filter['type'],
            'name'  => $filter['name'],
            'pic'   => $filter['pic'],
            'price' => $filter['price'],
            'detail' => $filter['detail'],
            'remark' => $filter['remark'],
            'state' => $state,
        ];
        if ($params['id']) {
            $this->checkById($params['id']);
            $where = ['id' => $params['id']];
            $update = $condition;
            return IoC()->Product_model->_update($where, $update);
        } else {
            $insert = $condition;
            return IoC()->Product_model->_insert($insert);
        }
    }

    /**
     * @param array $params
     * @return bool
     * @throws Exception
     */
    public function delete(array $params)
    {
        $this->checkBySku($params['sku']);
        IoC()->Product_model->_update(['sku' => $params['sku']], ['state' => Constants::NO_VALUE]);
        return true;
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
        $filter = $this->checkProductEntryApiArgument($params);

        /** 2. get update & insert & delete data list */
        $skus = array_column($filter['item_list'], 'sku');
        $delCondition = [
            'no_skus' => $skus,
            'isAll' => Constants::YES_VALUE
        ];
        $deleteItemList = IoC()->Product_model->find($delCondition, $count);
        $deleteIds = array_column($deleteItemList, 'id');

        $oldCondition = [
            'skus' => $skus,
            'isAll' => Constants::YES_VALUE
        ];
        $oldItemList = IoC()->Product_model->find($oldCondition, $count);
        $oldItemList = array_column($oldItemList, null, 'sku');

        $addList = [];
        $updateList = [];
        foreach ($filter['item_list'] as $item) {
            if (array_key_exists($item['sku'], $oldItemList)) {
                $item['id'] = $oldItemList[$item['sku']]['id'];
                $updateList[] = $item;
            } else {
                $item['trade_no'] = $params['trade_no'];
                $addList[] = $item;
            }
        }

        /** 3. update & insert & delete data */
        if (is_array($deleteIds) && count($deleteIds) > 0) {
            IoC()->Product_model->batchDelete($deleteIds);
        }
        if (is_array($updateList) && count($updateList) > 0) {
            IoC()->Product_model->batchUpdate($updateList);
        }

        if (is_array($addList) && count($addList) > 0) {
            IoC()->Product_model->batchAdd($addList);
        }

        return true;
    }

#endregion

#region base func
    /**
     * @param $type
     *
     * @return string
     * @throws Exception
     */
    private function makeSkuNo($type)
    {
        $typeList = ['A', 'B', 'C', 'D', 'E'];
        return $typeList[$type - 1] . Helper::random_string('numeric', '6');
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
     * @param string $sku
     * @param integer $isThrowError
     *
     * @return array
     * @throws Exception
     */
    public function checkBySku(
        string $sku,
        $isThrowError = Constants::YES_VALUE
    )
    {
        $condition = [
            'sku' => $sku,
        ];
        $product = IoC()->Product_model->get($condition);
        if (empty($product)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('ProductObj', 'sku');
        }
        return $product;
    }

    /**
     * @param $params
     * @return array|bool
     *
     * @throws Exception
     */
    public function checkProductEntryApiArgument($params)
    {
        $necessaryParamArr = ['item_list'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);

        $necessaryParamArr = ['sku', 'type', 'price', 'name', 'pic', 'detail', 'remark'];
        $checkLenLimitList = [
            'sku'  => 50,
            'pic'  => 254,
            'name' => 50,
        ];
        $this->checkArrayParamArgItem($params['item_list'], $necessaryParamArr, $checkLenLimitList);

        return $filter;
    }
#endregion
}
