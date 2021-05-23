<?php
/**
 * PrizeContestRankService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/23/21 2:04 PM
 */

namespace Service\PrizeContest;

use Lib\Constants;

use Service\BaseTrait;
use Service\BaseService;

use Exception;
use Exception\Common\DBInvalidObjectException;

/**
 * Class PrizeContestRankService
 * @package Service\PrizeContest
 */
class PrizeContestRankService extends BaseService
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

#region base
    /**
     * @param $accountId
     * @param $num
     *
     * @return mixed
     * @throws Exception
     */
    public function save($accountId, $num)
    {
        /**  check account */
        $prizeContestRecord = $this->checkByAccountId($accountId, Constants::NO_VALUE);
        if (empty($prizeContestRecord) || !isset($prizeContestRecord['id'])) {
            $condition = [
                'account_id'  => $accountId,
                'asset_num'   => 0
            ];
            IoC()->Prize_contest_rank_model->_insert($condition);
        }

        /** 2. asset change record */
        $prizeContestRecord = $this->checkByAccountId($accountId, Constants::NO_VALUE);

        //$where = ['unique_code'=> $uniqueCode, 'type' => $type];
        $where = ['id' => $prizeContestRecord['id']];
        $condition = [
            'asset_num' => $prizeContestRecord['asset_num']  + $num,
        ];
        IoC()->Prize_contest_rank_model->_update($where, $condition);

        return true;
    }
#endregion

#region base func
    /**
     * @param string  $accountId
     * @param integer $isThrowError
     *
     * @return array
     * @throws Exception
     */
    public function checkByAccountId(string $accountId, $isThrowError = Constants::YES_VALUE)
    {
        $prizeContestRank = IoC()->Prize_contest_rank_model->findOne(['account_id' => $accountId]);
        if (empty($prizeContestRank)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('PrizeContestRankObj', 'id');
        }
        return $prizeContestRank;
    }
#endregion

}