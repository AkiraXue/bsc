<?php
/**
 * GroupService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/16/21 11:18 PM
 */

namespace Service\Group;

use Exception;

use Lib\Helper;
use Lib\Constants;

use Service\BaseTrait;
use Service\BaseService;

use Exception\Common\DBInvalidObjectException;

/**
 * Class GroupService
 * @package Service
 */
class GroupService extends BaseService
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

#region group module
    public function find(array $params)
    {
        $condition = [];

        empty($params['code']) || $condition['code'] = $params['code'];
        empty($params['name']) || $condition['name'] = $params['name'];

        empty($params['state']) || $condition['state'] = $params['state'];

        $page = $params['page'];
        $limit = $params['limit'];
        $page = !empty($page) ? intval($page) : 1;
        $limit = !empty($limit) ? intval($limit) : 10;

        $data =  IoC()->Group_model->find($condition, $count, $page, $limit);
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
     * @return int
     * @throws Exception
     */
    public function save(array $params)
    {
        /** 1. check base params */
        $necessaryParamArr = ['name'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);
        $checkLenLimitList = [
            'name' => 50,
        ];
        $this->checkApiInvalidArgumentLenOverLimit($checkLenLimitList, $params);

        /** 2. check data */
        $code = $params['code'] ? $params['code'] : null;
        $state = Constants::YES_VALUE;
        if ($params['state'] && in_array($params['state'], [Constants::YES_VALUE, Constants::NO_VALUE])) {
            $state = $params['state'];
        }

        /** 3. save group info */
        if ($code) {
            $group = $this->checkGroupByCode($code);
            $where = ['code' => $group['code']];
            $update = [
                'name'          => $filter['name'],
                'state'         => $state
            ];
            return IoC()->Group_model->_update($where, $update);
        } else {
            $code = Helper::gen_uuid();
            $insert = [
                'code'          => $code,
                'name'          => $filter['name'],
                'state'         => $state
            ];
            return IoC()->Group_model->_insert($insert);
        }

    }
#endregion

#region base func
    /**
     * @param string  $code
     * @param integer $isThrowError
     *
     * @return array
     * @throws Exception
     */
    public function checkGroupByCode(string $code, $isThrowError=Constants::YES_VALUE)
    {
        $condition = [
            'code'  => $code,
        ];
        $group = IoC()->Group_model->get($condition);
        if (empty($group)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('GroupObj', 'code');
        }
        return $group;
    }
#endregion
}
