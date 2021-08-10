<?php
/**
 * Tag_relation.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/24/21 11:37 PM
 */

use Lib\Constants;

/**
 * Class Tag_relation_model
 */
class Tag_relation_model extends MY_Model
{
    public $table = 'tag_relation';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 批量添加
     *
     * @param $list
     *
     * @return int
     */
    public function batchAdd($list)
    {
        $data = [];
        foreach ($list as $item) {
            $data[] = [
                'unique_code'      => $item['unique_code'],
                'type'             => $item['type'],
                'tag_id'           => $item['tag_id'],
                'desc'             => $item['desc'],
                'state'            => $item['state'] ?: Constants::YES_VALUE
            ];
        }
        return $this->db->insert_batch($this->myTable(), $data);
    }

    /**
     * 批量更新
     *
     * @param $list
     *
     * @return int
     */
    public function batchUpdate($list)
    {
        return $this->db->update_batch($this->myTable(), $list, 'id');
    }

    /**
     * 批量删除
     *
     * @param $idList
     *
     * @return int
     */
    public function batchDelete($idList)
    {
        return $this->db->where_in('id', $idList)->delete($this->myTable());
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
        $selectStr = 'tag_id, desc, unique_code, type, desc, sort';
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
        !empty($params['desc']) ? $query->like('desc', $params['desc']) : null;

        !empty($params['tag_id']) ? $query->where_in('tag_id', $params['tag_id']) : null;
        !empty($params['tag_ids']) ? $query->where_in('tag_id', $params['tag_ids']) : null;
        !empty($params['no_tag_ids']) ? $query->where_not_in('tag_id', $params['no_tag_ids']) : null;

        !empty($params['type']) ? $query->where('type', $params['type']) : null;

        !empty($params['unique_code']) ? $query->where_in('unique_code', $params['unique_code']) : null;
        !empty($params['unique_codes']) ? $query->where_in('unique_code', $params['unique_codes']) : null;
        !empty($params['no_unique_codes']) ? $query->where_not_in('unique_code', $params['no_unique_codes']) : null;

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
    public function findRelationLeftJoinTag(array $params, &$count, $page=1, $limit=100)
    {
        $query = $this->db->select('relation.id, relation.unique_code, relation.tag_id, relation.desc, relation.sort, tag.name, knowledge.id as knowledge_id')
            ->from($this->myTable() . ' relation')
            ->join(IoC()->Tag_model->myTable() . ' tag', 'tag.id=relation.tag_id','left')
            ->join(IoC()->Knowledge_model->myTable() . ' knowledge', 'relation.unique_code=knowledge.id', 'left')
            ->order_by('knowledge.sort asc');

        !empty($params['tag_id']) && !is_array($params['tag_id']) &&
            $query->where('relation.tag_id', $params['tag_id']);
        !empty($params['tag_id']) && is_array($params['tag_id']) &&
        $query->where_in('relation.tag_id', $params['tag_id']);

        !empty($params['type']) && $query->where_in('relation.type', $params['type']);
        !empty($params['state']) && $query->where('knowledge.state', $params['state']);

        !empty($params['unique_code']) && !is_array($params['unique_code']) &&
            $query->where('relation.unique_code', $params['unique_code']);
        !empty($params['unique_code']) && is_array($params['unique_code']) &&
            $query->where_in('relation.unique_code', $params['unique_code']);


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

