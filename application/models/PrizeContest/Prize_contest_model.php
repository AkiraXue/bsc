<?php
/**
 * Prize_contest_model.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/22/21 8:31 PM
 */

use Service\BaseModelTrait;

/**
 * Class Prize_contest_model
 */
class Prize_contest_model extends MY_Model
{
    use BaseModelTrait;

    public $table = 'prize_contest';

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
        !empty($params['name']) && $query->like('name', $params['name']);

        !empty($params['start_date']) && $query->where('start_date >= ', $params['start_date']);
        !empty($params['end_date']) && $query->where('end_date <= ', $params['end_date']);

        !empty($params['is_asset_award_section']) && $query->where('is_asset_award_section', $params['is_asset_award_section']);

        !empty($params['state']) && $query->where('state', $params['state']);

        return $query;
    }
}
