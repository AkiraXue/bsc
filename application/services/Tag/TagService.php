<?php
/**
 * TagService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 4/30/21 9:45 AM
 */

namespace Service\Tag;

use Exception;

use Lib\Constants;

use Service\BaseService;

use Model\TagModel;
use Model\TagRelationModel;

use Exception\Common\DBInvalidObjectException;
use Exception\Common\ApiInvalidArgumentException;

/**
 * Class TagService
 * @package Service
 */
class TagService extends BaseService
{
#region init
    const USER_TAG_LENGTH_LIMIT = 20;

    public static $instance;

    public static function getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        parent::__construct();
    }
#endregion

#region func with tag
    /**
     * @param array $params
     *
     * @return mixed
     * @throws Exception
     */
    public function save(array $params)
    {
        $name = $params['name'];
        $desc = $params['desc'];
        $sort = $params['sort'];
        $act = $params['act'];
        $state = $params['state']?:Constants::YES_VALUE;

        if ($act === 'add') {
            $id = $this->add($name, $desc, $sort);
        } else {
            $id = intval($params['id']);
            $this->update($id, $name, $desc, $sort, $state);
        }
        return $id;
    }

    /**
     * 删除tag
     *
     * @param integer $id
     *
     * @return mixed
     * @throws Exception
     */
    public function delete($id)
    {
        $oldUserTag = TagModel::getIns()->getById($id);
        if (empty($oldUserTag)) {
            throw new DBInvalidObjectException('HrUserTag', 'id');
        }

        /** 1. delete user tag relation data*/
        $totalNum = TagRelationModel::getIns()->num(['tag_id' => $id]);
        if ($totalNum) {
            TagRelationModel::getIns()->del(['tagId' => $id]);
        }

        /** 2. delete user tag data */
        TagModel::getIns()->del(['id' => $id]);

        return true;
    }

    /**
     * @param $params
     *
     * @return array
     * @throws Exception
     */
    public function find($params)
    {
        $condition = [];

        empty($params['account_id']) || $condition['account_id'] = $params['account_id'];
        empty($params['name']) || $condition['name'] = $params['name'];
        empty($params['desc']) || $condition['desc'] = $params['desc'];

        empty($params['tag_id']) || $condition['id'] = $params['tag_id'];

        $page = $params['page'];
        $limit = $params['limit'];
        $page = !empty($page) ? intval($page) : 1;
        $limit = !empty($limit) ? intval($limit) : 10;

        $response = TagModel::getIns()->allItems($page, $limit, $condition);
        $count = $response['total'];
        $data = $response['items'];

        $totalPage = ceil($count / $limit);
        $totalPage = $totalPage ? $totalPage : 1;

        return [
            'list'       => $data,
            'total'      => $count,
            'total_page' => $totalPage
        ];
    }
#endregion

#region func item with tag
    /**
     * @param string  $name
     * @param string  $desc
     * @param integer $sort
     *
     * @return int|string
     * @throws Exception
     */
    private function add($name, $desc, $sort)
    {
        /** 判定长度，默认只能插入20个tag */
        $totalNum = TagModel::getIns()->num();
        if ($totalNum >= self::USER_TAG_LENGTH_LIMIT) {
            throw new Exception('标签数目超过限制', 3001);
        }
        $insertCondition = [
            'name'    => $name,
            'desc'    => $desc,
            'sort'    => $sort
        ];
        $id =  TagModel::getIns()->add($insertCondition);
        /** update sort */
        TagModel::getIns()->update(['id' => $id], ['sort' => $id]);

        return $id;
    }

    /**
     * @param integer $id
     * @param string  $name
     * @param string  $desc
     * @param integer $sort
     * @param integer $state
     *
     * @return mixed
     * @throws Exception
     */
    private function update($id, $name, $desc, $sort, $state)
    {
        if (empty($id)) {
            throw new ApiInvalidArgumentException('id');
        }
        $oldUserTag = TagModel::getIns()->getById($id);
        if (empty($oldUserTag)) {
            throw new DBInvalidObjectException('HrUserTag','id');
        }
        $updateCondition = [
            'name'    => $name,
            'desc'    => $desc,
            'sort'    => $sort,
            'state'  => $state
        ];
        TagModel::getIns()->update(['id' => $id], $updateCondition);

        return $id;
    }
#endregion

#region base func
    /**
     * @param $params
     *
     * @return array|bool
     * @throws Exception
     */
    public function checkTagEntryApiArgument($params)
    {
        $necessaryParamArr = ['name', 'desc', 'act'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);

        $checkLenLimitList = [
            'name' => 32,
            'desc' => 254
        ];
        $this->checkApiInvalidArgumentLenOverLimit($checkLenLimitList, $params);

        $filter['sort'] = empty($params['sort']) ? 0 : intval($params['sort']);

        if (!in_array($params['act'], [Constants::ACT_ADD, Constants::ACT_MODIFY])) {
            throw new ApiInvalidArgumentException('act');
        }

        return $filter;
    }
#endregion
}