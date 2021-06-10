<?php
/**
 * Prize_contest_record_item_model.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/22/21 9:11 PM
 */

use Lib\Constants;
use Service\BaseModelTrait;

/**
 * Class Prize_contest_record_item_model
 */
class Prize_contest_record_item_model extends MY_Model
{
    use BaseModelTrait;

    public $table = 'prize_contest_record_item';

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
                'prize_contest_record_id' => $item['prize_contest_record_id'],
                'prize_contest_id'        => $item['prize_contest_id'],
                'account_id'              => $item['account_id'],
                'date'                    => $item['date'],
                'knowledge_id'            => $item['knowledge_id'],
                'topic_id'                => $item['topic_id'],
                'sort'                    => $item['sort'],
                'draft'                   => $item['draft']?:'',
                'answer'                  => $item['answer']?:'',
                'is_correct'              => $item['is_correct'],
                'is_asset_award'          => $item['is_asset_award'],
                'asset_num'               => $item['asset_num'],
                'state'                   => $item['state'] ?: Constants::YES_VALUE
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

        /** initialize where,group,having,order **/
        $query = $this->filterQuery($query, $params);

        $totalNum  = $query->get()->row();

        return isset($totalNum->totalNum) ? $totalNum->totalNum : 0;
    }

    /**
     * 统计积分总值
     *
     * @param array $params
     * @return mixed
     */
    public function getTotalAssetNum(array $params)
    {
        $query = $this->db->select('SUM(`asset_num`) as totalNum')->from($this->table);

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
        !empty($params['prize_contest_record_id']) && $query->where('prize_contest_record_id', $params['prize_contest_record_id']);
        !empty($params['prize_contest_id']) && $query->where('prize_contest_id', $params['prize_contest_id']);
        !empty($params['account_id']) && $query->where('account_id', $params['account_id']);
        !empty($params['date']) && $query->where('date', $params['date']);
        !empty($params['knowledge_id']) && $query->where('knowledge_id', $params['knowledge_id']);
        !empty($params['topic_id']) && $query->where('topic_id', $params['topic_id']);
        !empty($params['sort']) && $query->where('sort', $params['sort']);
        !empty($params['is_correct']) && $query->where('is_correct', $params['is_correct']);
        !empty($params['is_asset_award']) && $query->where('is_asset_award', $params['is_asset_award']);

        !empty($params['state']) && $query->where('state', $params['state']);

        return $query;
    }
}
