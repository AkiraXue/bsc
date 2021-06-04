<?php
/**
 * RuleService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 6/4/21 4:58 PM
 */

namespace Service\BaseSetting;

use Exception;

use Lib\Constants;

use Service\BaseTrait;
use Service\BaseService;

use Exception\Common\DBInvalidObjectException;

/**
 * Class RuleService
 * @package Service\BaseSetting
 */
class RuleService extends BaseService
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

        empty($params['name']) || $condition['name'] = $params['name'];
        empty($params['type']) || $condition['type'] = $params['type'];
        empty($params['state']) || $condition['state'] = $params['state'];

        $page = $params['page'];
        $limit = $params['limit'];
        $page = !empty($page) ? intval($page) : 1;
        $limit = !empty($limit) ? intval($limit) : 10;

        $data =  IoC()->Rule_model->find($condition, $count, $page, $limit);
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
        $necessaryParamArr = ['name', 'type', 'remark'];
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

        /** 3. save activity info */
        if ($params['id']) {
            $this->checkById($params['id']);
            $where = ['id' => $params['id']];
            $update = [
                'name'          => $filter['name'],
                'remark'        => $filter['remark'],
                'type'          => $filter['type'],
                'state'         => $state
            ];
            return IoC()->Rule_model->_update($where, $update);
        } else {
            $insert = [
                'name'          => $filter['name'],
                'remark'        => $filter['remark'],
                'type'          => $filter['type'],
                'state'         => $state
            ];
            return IoC()->Rule_model->_insert($insert);
        }
    }

    /**
     * @param array $params
     * @return int
     * @throws Exception
     */
    public function toggle(array $params)
    {
        /** 1. check base params */
        $necessaryParamArr = ['id'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);

        /** 2. check activity */
        $rule = $this->checkById($params['id']);

        $state = $rule['state'] == Constants::YES_VALUE ? Constants::NO_VALUE : Constants::YES_VALUE;
        return IoC()->Rule_model->_update(['id' => $filter['id']], ['state' => $state]);
    }

#endregion

#region base func
    /**
     * @param integer  $type
     * @param integer $isThrowError
     *
     * @return array
     * @throws Exception
     */
    public function checkByType(int $type, $isThrowError=Constants::YES_VALUE)
    {
        $condition = [
            'type'  => $type,
            'state' => Constants::YES_VALUE
        ];
        $rule = IoC()->Rule_model->findOne($condition);
        if (empty($rule)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('RuleObj', 'rule type');
        }
        return $rule;
    }

    /**
     * @param integer  $id
     * @param integer $isThrowError
     *
     * @return array
     * @throws Exception
     */
    public function checkById(int $id, $isThrowError=Constants::YES_VALUE)
    {
        $rule = IoC()->Rule_model->getByID($id);
        if (empty($rule)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('RuleObj', 'rule id');
        }
        return $rule;
    }
#endregion
}