<?php
/**
 * PrizeService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/23/21 9:59 AM
 */

namespace Service\PrizeContest;

use Lib\Constants;

use Service\BaseTrait;
use Service\BaseService;

use Exception;
use Exception\Common\DBInvalidObjectException;

/**
 * Class PrizeService
 * @package Service\PrizeContest
 */
class PrizeService extends BaseService
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

#region prize
    /**
     * @param $accountId
     * @param $date
     *
     * @return mixed
     * @throws Exception
     */
    public function init($accountId, $date)
    {
        $prizeContest = PrizeContestService::getInstance()->getCurrentConfig();
        $prizeContestId = $prizeContest['id'];
        $params = [
            'account_id' => $accountId,
            'prize_contest_id' => $prizeContestId,
            'date' => $date,
        ];
        return PrizeContestRecordService::getInstance()->save($params);
    }

    /**
     * @param $params
     * @return mixed
     * @throws Exception
     */
    public function getProblem($params)
    {
        /** 1. check base params */
        $necessaryParamArr = [
            'account_id', 'prize_contest_record_id'
        ];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);
        $checkLenLimitList = [
            'account_id' => 50,
        ];
        $this->checkApiInvalidArgumentLenOverLimit($checkLenLimitList, $params);

        /** 2. getProblem */
        $condition = [
            'orderBy'               => 'sort asc',
            'account_id'            => $filter['account_id'],
            'prize_contest_record_id' => $filter['prize_contest_record_id'],
            'page'                  => 1,
            'limit'                 => 1,
        ];
        $items = PrizeContestRecordItemService::getInstance()->find($condition);
        if ($items['list'] && $items['list'][0]) {
            return  $items['list'][0];
        }
        return [];
    }
#endregion

}