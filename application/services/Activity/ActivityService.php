<?php
/**
 * ActivityService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/16/21 9:56 AM
 */

namespace Service\Activity;

use Exception;

use Lib\Helper;
use Lib\Constants;

use Service\BaseTrait;
use Service\BaseService;

use Exception\Common\DBInvalidObjectException;

/**
 * Class ActivityService
 * @package Service
 */
class ActivityService extends BaseService
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

        empty($params['code']) || $condition['code'] = $params['code'];
        empty($params['name']) || $condition['name'] = $params['name'];

        empty($params['start_date']) || $condition['start_date'] = $params['start_date'];
        empty($params['end_date']) || $condition['end_date'] = $params['end_date'];

        empty($params['state']) || $condition['state'] = $params['state'];

        $page = $params['page'];
        $limit = $params['limit'];
        $page = !empty($page) ? intval($page) : 1;
        $limit = !empty($limit) ? intval($limit) : 10;

        $data =  IoC()->Activity_model->find($condition, $count, $page, $limit);
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
        $necessaryParamArr = ['name', 'start_date', 'end_date', 'days'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);
        $checkLenLimitList = [
            'start_date' => 50,
            'end_date' => 50
        ];
        $this->checkApiInvalidArgumentLenOverLimit($checkLenLimitList, $params);

        /** 2. check data */
        $code = $params['code'] ? $params['code'] : null;
        $state = Constants::YES_VALUE;
        if ($params['state'] && in_array($params['state'], [Constants::YES_VALUE, Constants::NO_VALUE])) {
            $state = $params['state'];
        }

        /** 3. save activity info */
        if ($code) {
            $activity = $this->checkActivityByCode($code);
            $where = ['code' => $activity['code']];
            $update = [
                'name'          => $filter['name'],
                'days'          => $filter['days'],
                'start_date'    => $filter['start_date'],
                'end_date'      => $filter['end_date'],
                'state'         => $state
            ];
            return IoC()->Activity_model->_update($where, $update);
        } else {
            $code = Helper::gen_uuid();
            $insert = [
                'code'          => $code,
                'name'          => $filter['name'],
                'days'          => $filter['days'],
                'start_date'    => $filter['start_date'],
                'end_date'      => $filter['end_date'],
                'state'         => $state
            ];
            return IoC()->Activity_model->_insert($insert);
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
    public function checkActivityByCode(string $code, $isThrowError=Constants::YES_VALUE)
    {
        $condition = [
            'code'  => $code,
        ];
        $activity = IoC()->Activity_model->get($condition);
        if (empty($activity)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('ActivityObj', 'activity code');
        }
        return $activity;
    }

    /**
     * @param $params
     * @return array|bool
     *
     * @throws Exception
     */
    public function checkActivityEntryApiArgument($params)
    {
        $necessaryParamArr = ['code', 'punch_cycle_list'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);

        $necessaryParamArr = ['day',  'is_knowledge',  'knowledge_id', 'is_asset', 'asset_num'];
        $checkLenLimitList = [
            'asset_num' => 50,
        ];
        $this->checkArrayParamArgItem($params['punch_cycle_list'], $necessaryParamArr, $checkLenLimitList);

        return $filter;
    }
#endregion
}
