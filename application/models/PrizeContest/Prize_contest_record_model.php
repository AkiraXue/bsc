<?php
/**
 * Prize_contest_record_model.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/22/21 8:54 PM
 */

use Service\BaseModelTrait;

/**
 * Class Prize_contest_record_model
 */
class Prize_contest_record_model extends MY_Model
{
    use BaseModelTrait;

    public $table = 'prize_contest_record';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取单项
     *
     * @param array    $params
     *
     * @return array
     */
    public function get(array $params)
    {
        $arr = $this->_where($params)->_limit(1)->_select();
        if ($arr) {
            return $arr[0];
        }
        return [];
    }

    /**
     * 入库资金
     * @param $id
     * @param $assetNum
     *
     * @return array
     */
    public function storage($id, $assetNum)
    {
        $sql = "update " . $this->table . " set asset_num  = asset_num + " . $assetNum . " where id = '{$id}'";
        $result = $this->db->query($sql);
        if (!count($result)) {
            return [];
        }
        return $result;
    }

    /**
     * 查询
     *
     * @param array $params
     *  @params  boolean    isAll           是否取全部
     *
     * @param $count
     * @param integer $page
     * @param integer $limit
     *
     * @return array
     */
    public function find(array $params, &$count, $page=1, $limit=100)
    {
        $selectStr = '*';
        !empty($params['selectStr']) && $selectStr=$params['selectStr'];

        $query = $this->db->select($selectStr)->from($this->myTable() );

        $orderBy = ['id' => 'asc'];
        !empty($params['orderBy']) && $orderBy = $params['orderBy'];
        is_array($orderBy) ? $query->order_by(key($orderBy), current($orderBy)) : $query->order_by($orderBy);

        $query = $this->filterQuery($query, $params);

        $count = $query->count_all_results('',false);
        if ($count == 0) {
            return [];
        }

        /** 是否单次取全部 */
        $limit = !empty($params['isAll']) ? $count : $limit;

        $offset = ($page - 1) * $limit;
        $query->limit($limit, $offset);

        $result = $query->get()->result_array();

        if (!count($result)) {
            return [];
        }
        return $result;
    }

    /**
     * 统计
     *
     * @param array $params
     * @return mixed
     */
    public function getTotal(array $params)
    {
        $query = $this->db->select('COUNT(*) as totalNum')->from($this->table);

        /** initialize where,group,having,order **/
        $query = $this->filterQuery($query, $params);

        $totalNum  = $query->get()->row();

        return isset($totalNum->totalNum) ? $totalNum->totalNum : 0;
    }

    /**
     * @param CI_DB_query_builder $query
     * @param array $params
     *
     * @return CI_DB_query_builder
     */
    private function filterQuery(CI_DB_query_builder $query, array $params)
    {
        /** initialize where,group,having,order **/
        !empty($params['prize_contest_id']) && $query->where('prize_contest_id', $params['prize_contest_id']);
        !empty($params['account_id']) && $query->where('account_id', $params['account_id']);
        !empty($params['date']) && $query->where('date', $params['date']);
        !empty($params['start_date']) && $query->where('date>=', $params['start_date']);
        !empty($params['end_date']) && $query->where('date<=', $params['end_date']);
        !empty($params['is_through']) && $query->where('is_through', $params['is_through']);
        !empty($params['asset_num']) && $query->where('asset_num', $params['asset_num']);

        !empty($params['state']) && $query->where('state', $params['state']);

        return $query;
    }

    /**
     * @param array $params
     * @param $count
     * @param int $page
     * @param int $limit
     *
     * @return array
     */
    public function findRecordLeftJoinItem(array $params, &$count, $page=1, $limit=100)
    {
        $query = $this->db->select('record.*, setting.name as setting_name, setting.entry_num, setting.topic_num,
        setting.is_through as setting_is_through, setting.is_asset_award_section, setting.is_asset_award, 
        setting.asset_num as setting_asset_num, user.name as username, user.avatar')
            ->from($this->myTable() . ' record')
            ->join(IoC()->Prize_contest_model->myTable() . ' setting', 'setting.id=record.prize_contest_id', 'left')
            ->join(IoC()->User_model->myTable() . ' user', 'record.account_id=user.account_id','left')
            ->order_by('record.id asc');

        !empty($params['username']) && $query->like('user.name', $params['username']);

        !empty($params['account_id']) && $query->where('record.account_id', $params['account_id']);

        !empty($params['is_through']) && $query->where('record.is_through', $params['is_through']);

        !empty($params['date']) && $query->where('record.date', $params['date']);
        !empty($params['start_date']) && $query->where('record.date>=', $params['start_date']);
        !empty($params['end_date']) && $query->where('record.date<=', $params['end_date']);
        !empty($params['asset_num']) && $query->where('record.asset_num', $params['asset_num']);

        !empty($params['state']) && $query->where('record.state', $params['state']);

        $count = $query->count_all_results('',false);
        if ($count == 0) {
            return [];
        }

        /** 是否单次取全部 */
        $limit = !empty($params['isAll']) ? $count : $limit;

        $offset = ($page - 1) * $limit;
        $query->limit($limit , $offset);

        $result = $query->get()->result_array();
        if (!count($result)) {
            return [];
        }

        return $result;
    }
}
