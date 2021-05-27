<?php
/**
 * KnowledgeService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/16/21 11:47 PM
 */

namespace Service\Knowledge;

use Exception;

use Lib\Constants;

use Service\BaseTrait;
use Service\BaseService;

use Exception\Common\DBInvalidObjectException;
use Service\Tag\TagRelationService;
use Service\Tag\TagService;

/**
 * Class KnowledgeService
 * @package Service\Knowledge
 */
class KnowledgeService extends BaseService
{
    use BaseTrait;

#region initial info
    public static $instance;

    public function __construct()
    {
        parent::__construct();
    }

    public static function getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }
#endregion

#region api func
    /**
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function banner(array $params=[])
    {
        /** get banner info */
        $condition = [
            'desc'  => Constants::KNOWLEDGE_TYPE_BANNER,
            'state' => Constants::YES_VALUE,
            'isAll' => Constants::YES_VALUE
        ];
        $tagBannerRes= TagService::getInstance()->find($condition);
        $tagBannerList = $tagBannerRes['list'] ?:[];
        $list = [];
        foreach ($tagBannerList as $banner) {
            $item = [
                'id'       => $banner['id'],
                'name'     => $banner['name'],
                'sub_name' => $banner['sub_name'],
                'pic'      => $banner['bg_pic'],
            ];
            $list[] = $item;
        }
        return $list;
    }

    /**
     * @param array $list
     *
     * @return mixed
     * @throws Exception
     */
    private function cycleTagList(array $list)
    {
        foreach ($list as &$tag) {
            $condition = [
                'desc'  => Constants::KNOWLEDGE_TYPE_GUIDE,
                'state' => Constants::YES_VALUE,
                'parent_tag_id' => $tag['id'],
                'isAll' => Constants::YES_VALUE
            ];
            $tagRes= TagService::getInstance()->find($condition);
            $tagList = $tagRes['list'] ?:[];
            if (empty($tagList) || !is_array($tagList)) {
                return [];
            }
            $tag['list'] = $tagList;

            $this->cycleTagList($tag['list']);
        }

        return $list;
    }

    /**
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function guide(array $params)
    {
        /** 1. get guide info */
        $condition = [
            'desc'  => Constants::KNOWLEDGE_TYPE_GUIDE,
            'state' => Constants::YES_VALUE,
            'parent_tag_id' => 0,
            'isAll' => Constants::YES_VALUE
        ];
        $tagRes= TagService::getInstance()->find($condition);
        $tagList = $tagRes['list'] ?:[];
        if (empty($tagList) || !is_array($tagList)) {
            return [];
        }
        $tagList = $this->cycleTagList($tagList);

        return $tagList;
    }

    /**
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function findByTagId(array $params)
    {
        /** 1. check base params */
        $necessaryParamArr = ['categoryId'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);

        $filter['tag_id'] = $filter['categoryId'];
        /** 1. check tag_id */
        $tag = TagService::getInstance()->checkById($filter['tag_id']);

        /** 2. get relation tag_relation list */
        $condition = [
            'tag_id' => $filter['tag_id'],
            'isAll'  => Constants::YES_VALUE
        ];
        $tagRelationListRes = TagRelationService::getInstance()->findRelationLeftJoinTag($condition);

        $knowledgeIdList = array_column($tagRelationListRes['list'], 'unique_code');
        if (empty($tagRelationListRes) || !is_array($tagRelationListRes)) {
            return [];
        }
        $relationList = [];
        foreach ($tagRelationListRes['list'] as $tagRelationList) {
            if (empty($tagRelationList['tag_id'])) {
                continue;
            }

            $relationList[$tagRelationList['tag_id']][] = $tagRelationList['knowledge_id'];
        }


        /** 3. get relation tag_relation knowledge content */
        $condition = [
            'ids'    => $knowledgeIdList,
            'isAll'  => Constants::YES_VALUE
        ];
        $knowledgeListRes = KnowledgeService::getInstance()->find($condition);
        $knowledgeList = array_column($knowledgeListRes['list'], null, 'id');
        if (empty($knowledgeList) || !is_array($knowledgeList)) {
            return [];
        }
        /** 3. get related knowledge */
        $list = [];

        if (!empty($relationList[$tag['id']])) {
            $tagRelationList = $relationList[$tag['id']];
            foreach ($tagRelationList as $tagId) {
                if (!$knowledgeList[$tagId]) {
                    continue;
                }
                $knowledgeItem = $knowledgeList[$tagId];
                $knowledgeContent = json_decode($knowledgeItem['content'], true);

                $item = [];
                ($knowledgeItem['title'] && $knowledgeContent['text'] ) && $item['title'] = $knowledgeItem['title'];
                $knowledgeContent['is_contain'] && $item['is_contain'] = $knowledgeContent['is_contain'];
                $knowledgeContent['text'] && $item['text'] = $knowledgeContent['text'];
                $knowledgeContent['img'] && $item['img'] =  CDN_HOST . $knowledgeContent['img'];

                $list[] = $item;
            }
        }

        $floors = [
            'title'     => $tag['name'],
            'subtitle'  => $tag['sub_name'],
            'bg_pic'    => $tag['bg_pic'],
            'bg_video'  => $tag['bg_video'],
            'data_type' => $tag['relation_type'],
            'content'   => $list
        ];

        return $floors;
    }
#endregion

#region func
    public function find(array $params)
    {
        $condition = [];

        empty($params['ids']) || $condition['ids'] = $params['ids'];

        empty($params['title']) || $condition['title'] = $params['title'];
        empty($params['type']) || $condition['type'] = $params['type'];

        empty($params['state']) || $condition['state'] = $params['state'];
        empty($params['isAll']) || $condition['isAll'] = $params['isAll'];

        $page = $params['page'];
        $limit = $params['limit'];
        $page = !empty($page) ? intval($page) : 1;
        $limit = !empty($limit) ? intval($limit) : 10;

        $data =  IoC()->Knowledge_model->find($condition, $count, $page, $limit);
        $totalPage = ceil($count / $limit);
        $totalPage = $totalPage ? $totalPage : 1;
        return [
            'list'       => $data,
            'total'      => $count,
            'total_page' => $totalPage
        ];
    }

    /**
     * @param array $params
     *
     * @return int
     * @throws Exception
     */
    public function save(array $params)
    {
        /** 1. check base params */
        $necessaryParamArr = ['title', 'type', 'pic', 'content'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);
        $checkLenLimitList = [
            'pic' => 254
        ];
        $this->checkApiInvalidArgumentLenOverLimit($checkLenLimitList, $params);

        /** 2. check data */
        $state = Constants::YES_VALUE;
        if ($params['state'] && in_array($params['state'], [Constants::YES_VALUE, Constants::NO_VALUE])) {
            $state = $params['state'];
        }

        /** 3. save knowledge info */
        $condition = [
            'title'         => $filter['title'],
            'type'          => $filter['type'],
            'pic'           => $filter['pic'],
            'content'       => $filter['content'],
            'state'         => $state
        ];
        if ($params['id']) {
            $this->checkById($params['id']);
            $where = ['id' => $params['id']];
            $update = $condition;
            return IoC()->Knowledge_model->_update($where, $update);
        } else {
            $insert = $condition;
            return IoC()->Knowledge_model->_insert($insert);
        }
    }

#endregion

#region base func
    /**
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function getById(int $id)
    {
        $knowledge = $this->checkById($id, Constants::NO_VALUE);

        $knowledgeContent = json_decode($knowledge['content'], true);

        $content = [
            'title' => $knowledge['title'],
            'is_contain'  => $knowledgeContent['is_contain'],
            'text'  => $knowledgeContent['text'],
            'img'   => $knowledgeContent['img'],
        ];

        $floor = [
            'title'     => $knowledge['title'],
            'subtitle'  => '',
            'bg_pic'    => $knowledge['pic'],
            'data_type' => $knowledge['type'],
            'bg_video'  => '',
            'content'   => [$content]
        ];

        return $floor;
    }


    /**
     * @param integer  $id
     * @param integer $isThrowError
     *
     * @return array
     * @throws Exception
     */
    public function checkById(int $id, $isThrowError=Constants::YES_VALUE)
    {
        $knowledge = IoC()->Knowledge_model->getByID($id);
        if (empty($knowledge)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('KnowledgeObj', 'id');
        }
        return $knowledge;
    }
#endregion
}