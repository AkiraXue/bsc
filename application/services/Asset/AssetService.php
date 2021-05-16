<?php
/**
 * AssetService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/17/21 12:54 AM
 */

namespace Service\Asset;

use Exception;

use Lib\Constants;

use Service\BaseTrait;
use Service\BaseService;

use Exception\Common\DBInvalidObjectException;

/**
 * Class AssetService
 * @package Service\Asset
 */
class AssetService extends BaseService
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

        empty($params['unique_code']) || $condition['unique_code'] = $params['unique_code'];
        empty($params['name']) || $condition['name'] = $params['name'];

        empty($params['source']) || $condition['source'] = $params['source'];
        empty($params['type']) || $condition['type'] = $params['type'];

        empty($params['state']) || $condition['state'] = $params['state'];

        $page = $params['page'];
        $limit = $params['limit'];
        $page = !empty($page) ? intval($page) : 1;
        $limit = !empty($limit) ? intval($limit) : 10;

        $data = IoC()->Asset_model->find($condition, $count, $page, $limit);
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
        $necessaryParamArr = ['unique_code', 'name', 'source', 'type', 'total', 'used', 'remaining'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);
        $checkLenLimitList = [
            'unique_code'   => 50,
            'name'          => 50
        ];
        $this->checkApiInvalidArgumentLenOverLimit($checkLenLimitList, $params);

        /** 2. check data */
        $state = Constants::YES_VALUE;
        if ($params['state'] && in_array($params['state'], [Constants::YES_VALUE, Constants::NO_VALUE])) {
            $state = $params['state'];
        }

        /** 3. save asset info */
        $condition = [
            'unique_code'   => $filter['unique_code'],
            'name'          => $filter['name'],
            'source'        => $filter['source'],
            'type'          => $filter['type'],
            'total'         => $filter['total'],
            'used'          => $filter['used'],
            'remaining'     => $filter['remaining'],
            'state'         => $state
        ];
        if ($params['id']) {
            $this->checkById($params['id']);
            $where = ['id' => $params['id']];
            $update = $condition;
            return IoC()->Asset_model->_update($where, $update);
        } else {
            $insert = $condition;
            return IoC()->Asset_model->_insert($insert);
        }
    }

#endregion

#region base func
    /**
     * @param integer $id
     * @param integer $isThrowError
     *
     * @return array
     * @throws Exception
     */
    public function checkById(int $id, $isThrowError = Constants::YES_VALUE)
    {
        $asset = IoC()->Asset_model->getByID($id);
        if (empty($asset)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('AssetObj', 'id');
        }
        return $asset;
    }

    /**
     * @param string  $uniqueCode
     * @param integer $isThrowError
     *
     * @return array
     * @throws Exception
     */
    public function checkByUniqueCode(string $uniqueCode, $isThrowError = Constants::YES_VALUE)
    {
        $asset = IoC()->Asset_model->findOne(['unique_code' => $uniqueCode]);
        if (empty($asset)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('AssetObj', 'id');
        }
        return $asset;
    }
#endregion
}
