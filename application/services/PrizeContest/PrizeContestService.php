<?php
/**
 * PrizeContestService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/22/21 8:26 PM
 */

namespace Service\PrizeContest;

use Lib\Constants;

use Service\BaseTrait;
use Service\BaseService;

use Exception;
use Exception\Common\DBInvalidObjectException;

/**
 * Class PrizeContestService
 * @package Service\PrizeContest
 */
class PrizeContestService extends BaseService
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

#region test init
    /**
     * @param array $params
     * @return bool
     * @throws Exception
     */
    public function delete(array $params)
    {
        $this->checkPrizeContentById($params['id']);
        IoC()->Prize_contest_model->_update(['id' => $params['id']],  ['state' => Constants::NO_VALUE]);
        return true;
    }

    public function find(array $params)
    {
        $condition = [];

        empty($params['name']) || $condition['name'] = $params['name'];

        empty($params['start_date']) || $condition['start_date'] = $params['start_date'];
        empty($params['end_date']) || $condition['end_date'] = $params['end_date'];

        empty($params['state']) || $condition['state'] = $params['state'];

        empty($params['is_asset_award_section']) || $condition['is_asset_award_section'] = $params['is_asset_award_section'];

        $page = $params['page'];
        $limit = $params['limit'];
        $page = !empty($page) ? intval($page) : 1;
        $limit = !empty($limit) ? intval($limit) : 10;

        $data =  IoC()->Prize_contest_model->find($condition, $count, $page, $limit);
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
        $necessaryParamArr = [
            'name', 'entry_num', 'topic_num', 'pic', 'remark', 'is_asset_award_section',
            'is_asset_award', 'asset_num', 'start_date', 'end_date'
        ];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);
        $checkLenLimitList = [
            'start_date' => 50,
            'end_date'   => 50
        ];
        $this->checkApiInvalidArgumentLenOverLimit($checkLenLimitList, $params);

        /** 2. check data */
        $condition = [
            'name'          => $filter['name'],
            'entry_num'     => $filter['entry_num'],
            'topic_num'     => $filter['topic_num'],
            'pic'           => $filter['pic'],
            'remark'        => $filter['remark'],
            'is_asset_award' => $filter['is_asset_award'],
            'asset_num'     => $filter['asset_num'],
            'is_asset_award_section' => $filter['is_asset_award_section'],
            'start_date'    => $filter['start_date'],
            'end_date'      => $filter['end_date'],
            'state'         => Constants::YES_VALUE
        ];

        /** 3. save prize contest info && and add new config */
        IoC()->Prize_contest_model->_update(['state' => Constants::YES_VALUE], ['state' => Constants::NO_VALUE]);
        return IoC()->Prize_contest_model->_insert($condition);
    }
#endregion

#region base func
    /**
     * @param integer  $id
     * @param integer  $isThrowError
     *
     * @return array
     * @throws Exception
     */
    public function checkPrizeContentById(int $id, $isThrowError=Constants::YES_VALUE)
    {
        $condition = ['id'  => $id];
        $prizeContest = IoC()->Prize_contest_model->get($condition);
        if (empty($prizeContest)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('PrizeContestObj', 'id');
        }
        return $prizeContest;
    }

    /**
     * @param int $isThrowError
     *
     * @return array
     * @throws Exception
     */
    public function getCurrentConfig($isThrowError=Constants::YES_VALUE)
    {
        $condition = ['state'  => Constants::YES_VALUE];
        $prizeContest = IoC()->Prize_contest_model->get($condition);
        if (empty($prizeContest)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('PrizeContestObj', 'id');
        }
        return $prizeContest;
    }
#endregion

}