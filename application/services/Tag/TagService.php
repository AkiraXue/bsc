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

use Exception\Common\DBInvalidObjectException;
use Lib\Constants;

use Service\BaseTrait;
use Service\BaseService;

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
        $filter = $this->checkTagEntryApiArgument($params);

        /** 2. check data */
        $id = $params['id'];

        /** 3. save prize contest schedule info */
        $condition = [
            'name'     => $filter['name'],
            'sub_name' => $filter['sub_name'],
            'desc'     => $filter['desc'],
            'bg_pic'   => $filter['bg_pic'],
            'bg_video' => $filter['bg_video'],
            'sort'     => $filter['sort'],
            'relation_type' => $filter['relation_type']?:'',
            'parent_tag_id'     => $filter['parent_tag_id']?:0,
            'state'    => $filter['state'] ?: Constants::NO_VALUE
        ];
        if ($id) {
            return IoC()->Tag_model->_update(['id' => $id], $condition);
        } else {
            return IoC()->Tag_model->_insert($condition);
        }
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

        empty($params['name']) || $condition['name'] = $params['name'];
        empty($params['desc']) || $condition['desc'] = $params['desc'];

        empty($params['tag_id']) || $condition['id'] = $params['tag_id'];
        empty($params['orderBy']) || $condition['orderBy'] = $params['orderBy'];
        empty($params['isAll']) || $condition['isAll'] = $params['isAll'];
        !isset($params['parent_tag_id']) || $condition['parent_tag_id'] = $params['parent_tag_id'];

        $page = $params['page'];
        $limit = $params['limit'];
        $page = !empty($page) ? intval($page) : 1;
        $limit = !empty($limit) ? intval($limit) : 10;

        $data =  IoC()->Tag_model->find($condition,$count, $page, $limit);
        foreach ($data as &$tag) {
            $tag['bg_pic'] = strpos($tag['bg_pic'], 'http') ?  $tag['bg_pic'] : CDN_HOST . $tag['bg_pic'];
        }

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
     * @param integer  $id
     * @param integer $isThrowError
     *
     * @return array
     * @throws Exception
     */
    public function checkById(int $id, $isThrowError=Constants::YES_VALUE)
    {
        $tag = IoC()->Tag_model->getByID($id);
        if (empty($tag)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('TagObj', 'id');
        }
        $tag['bg_pic'] = strpos($tag['bg_pic'], 'http') ?  $tag['bg_pic'] : CDN_HOST . $tag['bg_pic'];
        return $tag;
    }

    /**
     * @param $params
     *
     * @return array|bool
     * @throws Exception
     */
    public function checkTagEntryApiArgument($params)
    {
        $necessaryParamArr = ['name', 'sub_name', 'desc', 'bg_pic'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);

        $checkLenLimitList = [
            'name'      => 32,
            'sub_name'  => 32,
            'desc'      => 254,
            'bg_pic'    => 254
        ];
        $this->checkApiInvalidArgumentLenOverLimit($checkLenLimitList, $params);

        $filter['sort'] = empty($params['sort']) ? 0 : intval($params['sort']);

        return $filter;
    }
#endregion
}