<?php
/**
 * Activity_participate_record_model.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/16/21 2:35 PM
 */

use Service\BaseModelTrait;

/**
 * Class Activity_participate_record_model
 */
class Activity_participate_record_model extends MY_Model
{
    use BaseModelTrait;

    public $table = 'activity_participate_record';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取单项
     *
     * @param array $params
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
     * 查询
     *
     * @param array $params
     *  @params  boolean    isAll           是否取全部
     *
     * @return array
     */
    public function findOne(array $params)
    {
        $selectStr = '*';
        !empty($params['selectStr']) && $selectStr=$params['selectStr'];

        $query = $this->db->select($selectStr)->from($this->myTable() );

        $orderBy = ['id' => 'asc'];
        !empty($params['orderBy']) && $orderBy = $params['orderBy'];
        is_array($orderBy) ? $query->order_by(key($orderBy), current($orderBy)) : $query->order_by($orderBy);

        $query = $this->filterQuery($query, $params);

        $query->limit(1);
        $result = $query->get()->result_array();
        if (!count($result)) {
            return [];
        }
        return $result[0];
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
        $query = $this->db->select('COUNT(`id`) as totalNum')->from($this->table);

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
        !empty($params['activity_code']) && $query->where('activity_code', $params['activity_code']);
        !empty($params['account_id']) && $query->where('account_id', $params['account_id']);

        !empty($params['day']) && $query->where('day', $params['day']);
        !empty($params['is_related_knowledge']) && $query->where('is_related_knowledge', $params['is_related_knowledge']);
        !empty($params['is_knowledge']) && $query->where('is_knowledge', $params['is_knowledge']);
        !empty($params['is_punch']) && $query->where('is_punch', $params['is_punch']);

        !empty($params['knowledge_id']) && $query->where('knowledge_id', $params['knowledge_id']);
        !empty($params['punch_date']) && $query->where('punch_date', $params['punch_date']);

        !empty($params['punch_date_start']) && $query->where('punch_date>=', $params['punch_date_start']);
        !empty($params['punch_date_end']) && $query->where('punch_date<=', $params['punch_date_end']);

        !empty($params['recent_punch_date']) && $query->where('recent_punch_date', $params['recent_punch_date']);
        !empty($params['next_punch_date']) && $query->where('next_punch_date', $params['next_punch_date']);

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
    public function findRecordLeftJoinUser(array $params, &$count, $page=1, $limit=100)
    {
        $query = $this->db->select('record.*, user.name as username, user.avatar, activity.start_date, activity.end_date')
            ->from($this->myTable() . ' record')
            ->join(IoC()->User_model->myTable() . ' user', 'record.account_id=user.account_id','left')
            ->join(IoC()->Activity_model->myTable() . ' activity', 'record.activity_code=activity.code', 'left')
            ->order_by('record.day asc');

        !empty($params['activity_code']) && $query->where('record.activity_code', $params['activity_code']);
        !empty($params['account_id']) && $query->where('record.account_id', $params['account_id']);

        !empty($params['day']) && $query->where('record.day', $params['day']);
        !empty($params['is_related_knowledge']) && $query->where('record.is_related_knowledge', $params['is_related_knowledge']);
        !empty($params['is_knowledge']) && $query->where('record.is_knowledge', $params['is_knowledge']);
        !empty($params['is_punch']) && $query->where('record.is_punch', $params['is_punch']);

        !empty($params['knowledge_id']) && $query->where('record.knowledge_id', $params['knowledge_id']);
        !empty($params['punch_date']) && $query->where('record.punch_date', $params['punch_date']);

        !empty($params['punch_date_start']) && $query->where('record.punch_date>=', $params['punch_date_start']);
        !empty($params['punch_date_end']) && $query->where('record.punch_date<=', $params['punch_date_end']);

        !empty($params['recent_punch_date']) && $query->where('record.recent_punch_date', $params['recent_punch_date']);
        !empty($params['next_punch_date']) && $query->where('record.next_punch_date', $params['next_punch_date']);

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