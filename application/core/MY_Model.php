<?php

/**
 * Class MY_model
 * CI_Model封装
 *
 * @property CI_DB db
 * @property CI_Loader load
 */
class MY_Model extends CI_Model
{

    protected $myDB = null; // 数据库实例 禁止在外部调用这个实例

    public $modelPath = null; // model文件所处的子文件夹 eg. 'his'=>models/his

    public $tablePrefix = ''; // 表名的前缀

    public $table = ''; // 该Model对应的表名

    public $primaryKey = 'id'; // 该Model对应的主键名

    public $fkArr = array(); // 外键集 eg. array('emrID' => array('emr','id')) 【fk】emrID=>emr.id

    public $fkLv = 0; // 外键级联最大层数

    public $blkArr = array(); // 逆向 外键集 eg. array('emr_diagnosis' => array('emrID',array('isDelete'=>0))) 【blk】emr_diagnosis.emrID=>emr.id extWhere

    public $blkLv = 0; // 逆向 外键集 开关

    public $channel = ''; // 切换数据库用

    public $recycleTable = ''; // 真删除备份表名

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    protected function myDB()
    { // 外部禁止调用这个方法
        if ($this->myDB == null) {
            $this->myDB = $this->db;
        }
        return $this->myDB;
    }

    public function myTable()
    {
        return $this->tablePrefix . $this->table;
    }

#region ci框架链式

    /**
     * where (eg. array('field' =>'value',...))
     * @param array $where
     * @return $this
     */
    public function _where($where = array())
    {
        foreach ($where as $k => $v) {
            $this->myDB()->where($k, $v);
        }
        return $this;
    }

    /**
     * or_where (eg. array('field' =>'value',...))
     * @param array $or_where
     * @return $this
     */
    public function _or_where($or_where = array())
    {
        foreach ($or_where as $k => $v) {
            $this->myDB()->or_where($k, $v);
        }
        return $this;
    }

    /**
     * limit $offset,$limit
     * @param int $limit
     * @param int $offset
     * @return $this
     */
    public function _limit($limit = 1, $offset = 0)
    {
        $this->myDB()->limit($limit, $offset);
        return $this;
    }

    /**
     * order by (eg. array('field1'=>'asc',...))
     * @param array $order_by
     * @return $this
     */
    public function _order_by($order_by = array())
    {
        if ($order_by) {
            foreach ($order_by as $k => $v) {
                $this->myDB()->order_by($k, $v);
            }
        } else {
            $this->myDB()->order_by($this->primaryKey, 'desc');
        }
        return $this;
    }

    /**
     * where in (eg. array('field1'=>array('value1','value2',...)))
     * @param array $where_in
     * @return $this
     */
    public function _where_in($where_in = array())
    {
        if ($where_in) {
            foreach ($where_in as $k => $v) {
                if ($v) {
                    $this->myDB()->where_in($k, $v);
                }else {
                    $this->myDB()->where("1 !=", "1");
                    break;
                }
            }
        }
        return $this;
    }

    /**
     * where not in (eg. array('field1'=>array('value1','value2',...)))
     * @param array $where_not_in
     * @return $this
     */
    public function _where_not_in($where_not_in = array())
    {
        if ($where_not_in) {
            foreach ($where_not_in as $k => $v) {
                $this->myDB()->where_not_in($k, $v);
            }
        }
        return $this;
    }

    /**
     * like (eg.)
     * @param array $like
     * @return $this
     */
    public function _like($like = array())
    {
        if ($like) {
            foreach ($like as $k => $v) {
                $this->myDB()->like($k, $v);
            }
        }
        return $this;
    }

    /**
     * or_like (eg.)
     * @param array $or_like
     * @return $this
     */
    public function _or_like($or_like = array())
    {
        if ($or_like) {
            foreach ($or_like as $k => $v) {
                $this->myDB()->or_like($k, $v);
            }
        }
        return $this;
    }

    /**
     * and (...)
     * group_start 左括号
     * @return $this
     */
    public function _group_start()
    {
        $this->myDB()->group_start();
        return $this;
    }

    /**
     * or (...)
     * group_start 左括号
     * @return $this
     */
    function _or_group_start()
    {
        $this->myDB()->or_group_start();
        return $this;
    }

    /**
     * group_end 右括号
     * @return $this
     */
    public function _group_end()
    {
        $this->myDB()->group_end();
        return $this;
    }

    /**
     * select distinct
     * @return $this
     */
    public function _distinct()
    {
        $this->myDB()->distinct();
        return $this;
    }

    /**
     * 更新数据 eg. UPDATE table SET field = field+1
     * @param string $key field
     * @param string $val field+1
     * @return $this
     */
    public function _set($key = '', $val = '')
    {
        if ($key && $val) {
            $this->myDB()->set($key, $val, false);
        }
        return $this;
    }

    /**
     * 获取总数
     * @param string $table table name
     * @return mixed
     */
    public function _count($table = '')
    {
        $table = $table ? $table : $this->myTable();
        if (!$table) {
            return 0;
        }
        $this->myDB()->from($table);
        return $this->myDB()->count_all_results();
    }

    /**
     * select (eg. array('field1','field2',...) or 'filed1,filed2,...')
     * @param string $select
     * @param string $table table name
     * @return mixed
     */
    public function _select($select = "*", $table = "")
    {
        $table = $table ? $table : $this->myTable();
        if (!$table) {
            return array();
        }

        $this->myDB()->select($select);
        $query = $this->myDB()->get($table);
        if (!$query) {
            return array();
        }
        $data = $query->result_array();

        // 外键获取
        $data = $this->getByFKorBLK($data, $this->fkLv, $this->fkArr, $this->blkLv, $this->blkArr);

        return $data;
    }

    /**
     * select & count
     * @param int $count
     * @param string $select
     * @param string $table
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function _selectCount(&$count = 0, $select = "*", $table = "", $limit = 30, $offset = 0)
    {
        $table = $table ? $table : $this->myTable();
        if (!$table) {
            return array();
        }

        $count = $this->myDB()->count_all_results($table, false); // 不清空where条件

        $this->myDB()->limit($limit, $offset);
        $this->myDB()->select($select);
        $query = $this->myDB()->get();
        $data = $query->result_array();

        // 外键获取
        $data = $this->getByFKorBLK($data, $this->fkLv, $this->fkArr, $this->blkLv, $this->blkArr);

        return $data;
    }

#endregion

#region 通用操作
    /**
     * 执行sql
     * @param $sql
     * @return mixed
     */
    public function _query($sql)
    {
        $query = $this->myDB()->query($sql);
        $num = $this->myDB()->affected_rows();
        return $num;
    }

    /**
     * 返回多行数据
     * @param $sql
     * @return mixed
     */
    public function _getRows($sql)
    {
        $query = $this->myDB()->query($sql);
        return $query->result_array();
    }

    /**
     * 返回单行数据
     * @param $sql
     * @return mixed
     */
    public function _getRow($sql)
    {
        $data = $this->_getRows($sql);
        return $data[0];
    }

    /**
     * 返回单行首列数据
     * @param $sql
     * @return mixed
     */
    public function _getOne($sql)
    {
        $data = $this->_getRow($sql);
        return current($data);
    }

    /**
     * 插入数据
     * @param $data array 插入的数据array
     * @param string $table 表名
     * @return int 插入成功的id
     */
    public function _insert($data, $table = '')
    {
        $table = $table ? $table : $this->myTable();
        if (!$table) {
            return 0;
        }
        $query = $this->myDB()->insert($table, $data);
        $insert_id = $this->myDB()->insert_id();
        return $insert_id;
    }

    /**
     * 删除数据
     * @param array $where where (eg. array('field' =>'value',...))
     * @param string $table
     * @param int $limit
     * @return mixed
     */
    public function _delete($where, $table = '', $limit = 1)
    {
        $table = $table ? $table : $this->myTable();
        if (!$table) {
            return 0;
        }
        $this->myDB()->where($where);
        $this->myDB()->limit($limit);
        return $this->myDB()->delete($table);
    }

    /**
     * 更新数据
     * @param array $where where (eg. array('field' =>'value',...))
     * @param array $update update (eg. array('field' =>'value',...))
     * @param string $table
     * @param int $limit
     * @return int
     */
    public function _update($where, $update, $table = '', $limit = 1)
    {
        $table = $table ? $table : $this->myTable();
        if (!$table) {
            return 0;
        }
        $this->myDB()->where($where);
        $this->myDB()->limit($limit);
        $this->myDB()->update($table, $update);
        return $this->myDB()->affected_rows();
    }

#endregion

#region 常用操作

    public function distinct()
    {
        return $this->_distinct();
    }

    /**
     * 分页获取
     * @param int $page
     * @param int $limit
     * @param array $order_by
     * @param string $select
     * @param string $table
     * @return mixed
     */
    public function page($page = 1, $limit = 30, $order_by = array(), $select = "*", $table = "")
    {
        $offset = ($page - 1) * $limit;
        $this->_order_by($order_by);
        $this->_limit($limit, $offset);
        $data = $this->_select($select, $table);
        return $data;
    }

    /**
     * 获取总数
     * @param string $table
     * @return mixed
     */
    public function count($table = "")
    {
        return $this->_count($table);
    }

    /**
     * 分页获取 & 总数
     * @param int $count
     * @param int $page
     * @param int $limit
     * @param array $order_by
     * @param string $select
     * @param string $table
     * @return array
     */
    public function pageCount(&$count = 0, $page = 1, $limit = 30, $order_by = array(), $select = "*", $table = "")
    {
        $offset = ($page - 1) * $limit;
        $this->_order_by($order_by);
        $data = $this->_selectCount($count, $select, $table, $limit, $offset);
        return $data;
    }

    /**
     * 根据主键获取
     * @param int $id
     * @return array
     */
    public function getByID($id = 0)
    {
        if (!$id) {
            return array();
        }
        $where = array($this->primaryKey => $id);
        $arr = $this->_where($where)->_limit(1)->_select();
        if ($arr) {
            return $arr[0];
        }
        return array();
    }

    /**
     * 根据主键数组获取
     * @param array $ids
     * @return array
     */
    public function getByIDs($ids = array())
    {
        if (!$ids) {
            return array();
        }
        $limit = count($ids);
        $where_in = array($this->primaryKey => $ids);
        $arr = $this->_where_in($where_in)->_limit($limit)->_select();
        return $arr;
    }

    /**
     * 外键获取
     *
     * @param $data
     * @param $fkLv
     * @param $fkArr
     * @param $blkLv
     * @param $blkArr
     * @param array $hasFKArr
     * @param array $hasBLKArr
     * @return mixed
     */
    public function getByFKorBLK($data, $fkLv, $fkArr, $blkLv, $blkArr, &$hasFKArr = array(), &$hasBLKArr = array())
    {
        if (!$hasFKArr) {
            $hasFKArr[] = $this->table;
        }
        if (!$hasBLKArr) {
            $hasBLKArr[] = $this->table;
        }

        // 外键
        if ($fkLv && $fkArr) {
            $fkWhereIn = array();
            $fkDataIdx = array();
            foreach ($fkArr as $fk => $r) {
                $fkWhereIn[$fk] = array();
                $fkDataIdx[$fk] = array();
            }
            foreach ($data as $k => $v) {
                foreach ($fkArr as $fk => $r) {
                    if (isset($v[$fk])) {
                        if ($v[$fk]) {
                            if (!in_array($v[$fk], $fkWhereIn[$fk])) {
                                $fkWhereIn[$fk][] = $v[$fk];
                            }
                        }
                    }
                }
            }
            foreach ($fkArr as $fk => $r) {
                $fkTB = $r[0];
                $fkID = $r[1];
                if ($fkWhereIn[$fk]) {
                    $model = ucfirst($fkTB) . '_model'; // 引用对应的model
                    if (!in_array($fkTB, $hasFKArr)) { // 过滤循环
                        $rows = IoC()->$model->getByIDs($fkWhereIn[$fk]);
                        $fkDataIdx[$fk] = array_column($rows, null, $fkID);
                    }
                }
            }
            foreach ($data as $k => $v) {
                foreach ($fkArr as $fk => $r) {
                    $fkTB = "fk_{$fk}"; // 外键对应数据的键名
                    if ($fkDataIdx[$fk]) {
                        $data[$k][$fkTB] = $fkDataIdx[$fk][$v[$fk]];
                    } else {
                        $data[$k][$fkTB] = array();
                    }
                }
            }
            // 级联
            $readyFKArr = array();
            foreach ($fkArr as $fk => $r) {
                $_fkTB = "fk_{$fk}";
                foreach ($data as $k => $v) {
                    if ($data[$k][$_fkTB]) {
                        $readyFKArr[$fk][] = &$data[$k][$_fkTB];
                    }
                }
            }
            foreach ($fkArr as $fk => $r) {
                $fkTB = $r[0];
                $model = ucfirst($fkTB) . '_model'; // 引用对应的model
                if ($readyFKArr[$fk]) {
                    $readyFKArr[$fk] = $this->getByFKorBLK($readyFKArr[$fk], $fkLv - 1, $this->$model->fkArr, $blkLv, $this->$model->blkArr);
                }
            }
        }

        // 逆向外键
        if ($blkLv && $blkArr) {
            $blkWhereIn = array_column($data, 'id');
            $blkDataIdx = array();
            if ($blkWhereIn) {
                foreach ($blkArr as $blkTB => $r) {
                    $blkID = $r[0];
                    $extraWhere = $r[1];
                    $model = ucfirst($blkTB) . '_model'; // 引用对应的model
//					$this->load->model($this->modelPath.'/'.$model);
                    if (!in_array($blkTB, $hasBLKArr)) { // 过滤循环
                        $where_in = array(
                            $blkID => $blkWhereIn,
                        );
                        $limit = count($blkWhereIn) * 1000;
                        $rows = IoC()->$model->_where_in($where_in)->_where($extraWhere)->_limit($limit)->_select();
                        $arr = array();
                        foreach ($rows as $row) {
                            if (!isset($arr[$row[$blkID]])) {
                                $arr[$row[$blkID]] = array();
                            }
                            $arr[$row[$blkID]][] = $row;
                        }
                        $blkDataIdx[$blkTB] = $arr;
                    }
                }
                foreach ($data as $k => $v) {
                    $pk = $v['id'];
                    foreach ($blkArr as $blkTB => $r) {
                        $_blkTB = "blk_{$blkTB}"; // 逆向外键对应数据的键名
                        if ($blkDataIdx[$blkTB][$pk]) {
                            $data[$k][$_blkTB] = $blkDataIdx[$blkTB][$pk];
                        } else {
                            $data[$k][$_blkTB] = array();
                        }
                    }
                }
                // 级联
                $readyBLKArr = array();
                foreach ($blkArr as $blkTB => $r) {
                    $_blkTB = "blk_{$blkTB}";
                    foreach ($data as $k => $v) {
                        if ($data[$k][$_blkTB]) {
                            foreach ($data[$k][$_blkTB] as $blkK => $blkV) {
                                $readyBLKArr[$blkTB][] = &$data[$k][$_blkTB][$blkK];
                            }
                        }
                    }
                }
                foreach ($blkArr as $blkTB => $r) {
                    $model = ucfirst($blkTB) . '_model';
                    if ($readyBLKArr[$blkTB]) {
                        $readyBLKArr[$blkTB] = $this->getByFKorBLK($readyBLKArr[$blkTB], $fkLv, $this->$model->fkArr, $blkLv - 1, $this->$model->blkArr);
                    }
                }
            }
        }

        return $data;
    }

    /**
     * 获取数据 带外键数据
     * @param $data
     * @return array
     */
    public function getByFK($data)
    {
        if (!$this->fkArr || !$this->fkLv) {
            return $data;
        }
        $this->fkLv--;
        $fkWhereIn = array();
        $fkDataIdx = array();
        foreach ($this->fkArr as $fk => $r) {
            $fkWhereIn[$fk] = array();
            $fkDataIdx[$fk] = array();
        }
        foreach ($data as $k => $v) {
            foreach ($this->fkArr as $fk => $r) {
                if (isset($v[$fk])) {
                    if ($v[$fk]) {
                        if (!in_array($v[$fk], $fkWhereIn[$fk])) {
                            $fkWhereIn[$fk][] = $v[$fk];
                        }
                    }
                }
            }
        }
        foreach ($this->fkArr as $fk => $r) {
            $fkTB = $r[0];
            $fkID = $r[1];
            if ($fkWhereIn[$fk]) {
                $model = ucfirst($fkTB) . '_model'; // 引用对应的model
                $this->load->model($this->modelPath . '/' . $model);
                $this->$model->withFKLv($this->fkLv);
                $this->$model->withBlkLv($this->blkLv); // 顺带blk
                if ($this->channel) {
                    $this->$model->setChannel($this->channel);
                }
                $rows = $this->$model->getByIDs($fkWhereIn[$fk]);
                $fkDataIdx[$fk] = array_column($rows, null, $fkID);
            }
        }
        foreach ($data as $k => $v) {
            foreach ($this->fkArr as $fk => $r) {
                $fkTB = "fk_{$fk}"; // 外键对应数据的键名
                if ($fkDataIdx[$fk]) {
                    $data[$k][$fkTB] = $fkDataIdx[$fk][$v[$fk]];
                } else {
                    $data[$k][$fkTB] = array();
                }
            }
        }
        return $data;
    }

    /**
     * 设置外键级联获取的最大层数
     * @param int $lv
     * @return $this
     */
    public function withFKLv($lv = 0)
    {
        $this->fkLv = $lv;
        return $this;
    }

    /**
     * 获取数据 带逆向 外键数据
     * @param $data
     * @return array
     */
    public function getByBLK($data)
    {
        if (!$this->blkArr || !$this->blkLv) {
            return $data;
        }

        $blkWhereIn = array_column($data, $this->primaryKey);
        $blkDataIdx = array();

        if (!$blkWhereIn) {
            return $data;
        }

        foreach ($this->blkArr as $blkTB => $r) {
            $blkID = $r[0];
            $extraWhere = $r[1];
            $model = ucfirst($blkTB) . '_model'; // 引用对应的model
            $this->load->model($this->modelPath . '/' . $model);
            $this->$model->withFKLv($this->fkLv); // 顺带fk
            $where_in = array(
                $blkID => $blkWhereIn,
            );
            $limit = count($blkWhereIn) * 1000;
            $rows = $this->$model->_where_in($where_in)->_where($extraWhere)->_limit($limit)->_select();
            $arr = array();
            foreach ($rows as $row) {
                if (!isset($arr[$row[$blkID]])) {
                    $arr[$row[$blkID]] = array();
                }
                $arr[$row[$blkID]][] = $row;
            }
            $blkDataIdx[$blkTB] = $arr;
        }
        foreach ($data as $k => $v) {
            $pk = $v[$this->primaryKey];
            foreach ($this->blkArr as $blkTB => $r) {
                $_blkTB = "blk_{$blkTB}"; // 逆向外键对应数据的键名
                if ($blkDataIdx[$blkTB][$pk]) {
                    $data[$k][$_blkTB] = $blkDataIdx[$blkTB][$pk];
                } else {
                    $data[$k][$_blkTB] = array();
                }
            }
        }
        return $data;
    }

    /**
     * 设置逆向外键开关
     * @param int $lv
     * @return $this
     */
    public function withBlkLv($lv = 0)
    {
        $this->blkLv = $lv;
        return $this;
    }

    public function delByID($id, $needRecycle = true)
    {
        $where = array($this->primaryKey => $id);
        return $this->delByWhere($where, 1, $needRecycle);
    }

    public function delByWhere($where, $limit = 200, $needRecycle = true)
    {
        $hasDelTable = $this->myTable(); // 发生删除的表名
        $recycleTable = $this->recycleTable; // 备份用表的表名
        if ($hasDelTable && $recycleTable && $needRecycle) {
            // 备份
            $rows = $this->_where($where)->_limit($limit)->_select('*', $hasDelTable);
            if ($rows) {
                foreach ($rows as $row) {
                    $pk = isset($row['id']) ? intval($row['id']) : 0;
                    if (!$pk) {
                        continue;
                    }
                    $insert = array(
                        'table' => $hasDelTable,
                        'pk' => $pk,
                        'row' => json_encode($row),
                        'created' => date('Y-m-d H:i:s'),
                    );
                    $this->_insert($insert, $recycleTable);
                }
            }
        }

        // 真删除
        return $this->_delete($where, $hasDelTable, $limit);
    }

    /**
     * @param $extraFields
     * @return array
     * @throws Exception
     */
    public function _dealExtraField($extraFields)
    {
        if (!$extraFields) return [];
        $parsedExtraFields = [];
        foreach ($extraFields as $key => $value) {
            $tmp = explode('_', $key);
            if (count($tmp) != 3) {
                echo json_encode([$key, $tmp]); die;
                throw new Exception('Invalid extraFields', 1010);
            }
            if (!is_numeric ($tmp[2]) || $tmp[2] < 1 || $tmp[2] > 5) throw new Exception('Invalid extraFields', 1010);
            switch ($tmp[1]) {
                case 'int':
                case 'varchar':
                    $parsedExtraFields[$key] = $value;
                    break;
                default:
                    throw new Exception('Invalid extraFields contains invaid fields', 1011);
                    break;
            }
        }
        return $parsedExtraFields;
    }

    protected function _dealExtraCondition($extra, $columnSet=[]) {
        $condition = array_filter($extra, function($value){return ($value === null || $value === []) ? false : true;});

        if (!$condition) {
            return $this;
        }

        foreach($condition as $eachKey => $eachValue) {
            //如果设置了参数范围，检测用户请求的是否在参数范围内
            if ($columnSet) {
                if (!in_array($eachKey, $columnSet)) {
                    continue;
                }
            }
            //如果没设置，检测键名是否是字符串
            if (!is_string($eachKey)) {
                continue;
            }
            //检测值是否是数组
            if (is_array($eachValue)) { //数组
                $result['whereIn'][$eachKey] = $eachValue;
                $this->_where_in([$eachKey => $eachValue]);
            } elseif (is_numeric($eachValue) || is_string($eachValue)) { //非空数字或字符串
                $result['where'][$eachKey] = $eachValue;
                $this->_where([$eachKey => $eachValue]);
            }
        }

        return $this;
    }

    /**
     * _dealOrderColumn
     * @description: 格式化order字段
     * @param $array ['asc' => [], 'desc' => []]
     * @return array [keyName => 'asc/desc']
     * @date: 2020/4/2
     */
    protected function _dealOrderColumn($array) {
        $result = [];
        if (!is_array($array)) {
            return $result;
        }

        $array = array_filter($array);
        if (!$array) {
            return $result;
        }

        foreach($array as $eachKey => $eachValue) {
            $key = strtolower($eachKey);
            if(!in_array($key, ['asc', 'desc'])) {
                $key = 'asc';
            }
            if (!is_array($eachValue)) {
                //'asc' => 'key1'
                $value = trim($eachValue);
                $result[$value] = $key;
            }

            if (is_array($eachValue)) {
                //'asc' => ['key1']
                //$value = array_filter($eachValue);
                array_filter($eachValue);
                if ($eachValue) {
                    foreach($eachValue as $moreValue) {
                        if (is_string($moreValue)) {
                            $result[$moreValue] = $key;
                        } else {
                            continue;
                        }
                    }
                } else {
                    continue;
                }
            }
        }

        return $result;
    }

}
