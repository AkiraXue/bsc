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

use Service\BaseTrait;
use Service\BaseService;

use Exception\Common\DBInvalidObjectException;
use Exception\Common\ApiInvalidArgumentException;


/**
 * Class TagService
 * @package Service\Tag
 */
class TagService extends BaseService
{
    use BaseTrait;

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
        /** 1. check base params */
        $necessaryParamArr = [
            'sort', 'prize_contest_id', 'is_asset_award', 'asset_num'
        ];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);
        $checkLenLimitList = [
            'asset_num' => 50,
        ];
        $this->checkApiInvalidArgumentLenOverLimit($checkLenLimitList, $params);

        /** 2. check data */
        $id = $params['id'];


        /** 3. save prize contest schedule info */
        $condition = [
            'sort'              => $filter['sort'],
            'prize_contest_id'  => $filter['prize_contest_id'],
            'is_asset_award'    => $filter['is_asset_award'],
            'asset_num'         => $filter['asset_num']
        ];
        if ($id) {
            return IoC()->Tag_model->_update(['id' => $id], $condition);
        } else {
            return IoC()->Tag_model->_insert($condition);
        }
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


#region base func
    /**
     * @param $params
     *
     * @return array|bool
     * @throws Exception
     */
    public function checkTagEntryApiArgument($params)
    {
        $necessaryParamArr = ['name', 'sub_name', 'desc', 'bg_pic', 'bg_video', 'sort', 'act'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);

        $checkLenLimitList = [
            'name'      => 32,
            'sub_name'  => 32,
            'desc'      => 254,
            'bg_pic'    => 254,
            'bg_video'  => 254
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