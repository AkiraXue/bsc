<?php
/**
 * Tag_model.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/24/21 11:32 PM
 */

class Tag_model extends MY_Model
{
    public $table = 'tag';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 复用: 搜索
     *
     * @param array $params
     * <ul>
     *  <li>appkey          string                  应用key</li>
     *  <li>channel         int | array             应用渠道</li>
     * </ul>
     * @param $count
     * @param integer $page
     * @param integer $limit
     *
     * @return array
     */
    public function find(array $params, &$count, $page=1, $limit=100)
    {
        $selectStr = '*';
        !empty($params['selectStr']) && $selectStr = $params['selectStr'];

        $query = $this->db->select($selectStr);
        $query->from($this->myTable());

        $orderBy = ['sort' => 'asc'];
        !empty($params['orderBy']) && $orderBy = $params['orderBy'];
        is_array($orderBy) ? $query->order_by(key($orderBy), current($orderBy)) : $query->order_by($orderBy);

        /** initialize where,group,having,order **/
        $query = $this->filterQuery($query, $params);
        $count = $query->count_all_results('', false);

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

    /**
     * 统计
     *
     * @param array $params
     * @return mixed
     */
    public function getTotal(array $params)
    {
        $query = $this->db->select('COUNT(`id`) as totalNum')->from($this->table);

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

        !empty($params['ids']) && is_array($params['ids']) ? $query->where_in('id', $params['ids']) : null;
        !empty($params['desc']) ? $query->where('desc', $params['desc']) : null;
        !empty($params['parent_tag_id']) ? $query->where('parent_tag_id', $params['parent_tag_id']) : null;
        isset($params['parent_tag_id']) ? $query->where('parent_tag_id', $params['parent_tag_id']) : null;

        isset($params['name']) ? $query->like('name', $params['name']) : null;

        return $query;
    }
}

