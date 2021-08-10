<?php
/**
 * Order_item_model.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/17/21 1:05 AM
 */

use Lib\Constants;
use Service\BaseModelTrait;

class Order_item_model extends MY_Model
{
    use BaseModelTrait;

    public $table = 'order_item';

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
                'sku'           => $item['sku'],
                'unique_code'   => $item['unique_code'],
                'trade_no'      => $item['trade_no'],
                'type'          => $item['type'],
                'price'         => $item['price'],
                'name'          => $item['name'],
                'pic'           => $item['pic'],
                'detail'        => $item['detail'],
                'remark'        => $item['remark'],
                'state'         => $item['state'] ?: Constants::YES_VALUE
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
        !empty($params['sku']) && $query->where('sku', $params['sku']);

        !empty($params['skus']) && $query->where_in('sku', $params['skus']);
        !empty($params['no_skus']) && $query->where_not_in('sku', $params['no_skus']);

        !empty($params['unique_code']) && $query->where('unique_code', $params['unique_code']);
        !empty($params['unique_codes']) && $query->where_in('unique_code', $params['unique_codes']);

        !empty($params['trade_no']) && $query->where('trade_no', $params['trade_no']);
        !empty($params['trade_nos']) && $query->where_in('trade_no', $params['trade_nos']);

        !empty($params['type']) && $query->where('type', $params['type']);
        !empty($params['name']) && $query->where('name', $params['name']);

        !empty($params['start_date']) && $query->where('created_at >=', date('Y-m-d H:i:s', strtotime($params['start_date'])));
        !empty($params['end_date']) && $query->where('created_at <=', date('Y-m-d H:i:s', strtotime($params['end_date'])));

        !empty($params['state']) && $query->where('state', $params['state']);

        return $query;
    }
}
