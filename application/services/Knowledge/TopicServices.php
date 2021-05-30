<?php
/**
 * TopicServices.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/17/21 12:48 AM
 */

namespace Service\Knowledge;

use Exception;

use Lib\Constants;

use Service\BaseTrait;
use Service\BaseService;

use Exception\Common\DBInvalidObjectException;

/**
 * Class TopicServices
 * @package Service\Knowledge
 */
class TopicServices extends BaseService
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
        if (!self::$instance instanceof self){
            self::$instance = new self() ;
        }
        return self::$instance;
    }
#endregion

#region func
    public function randomTopic($num)
    {
        return IoC()->Topic_model->random_topic($num);
    }

    public function find(array $params)
    {
        $condition = [];

        empty($params['ids']) || $condition['ids'] = $params['ids'];
        empty($params['title']) || $condition['title'] = $params['title'];
        empty($params['type']) || $condition['type'] = $params['type'];

        empty($params['answer_type']) || $condition['answer_type'] = $params['answer_type'];
        empty($params['knowledge_id']) || $condition['knowledge_id'] = $params['knowledge_id'];

        empty($params['state']) || $condition['state'] = $params['state'];

        $page = $params['page'];
        $limit = $params['limit'];
        $page = !empty($page) ? intval($page) : 1;
        $limit = !empty($limit) ? intval($limit) : 10;

        $data =  IoC()->Topic_model->find($condition, $count, $page, $limit);
        foreach ($data as &$item) {
            $item['content'] = json_decode($item['content'], true);
        }

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
        $necessaryParamArr = ['title', 'type', 'answer_type', 'knowledge_id', 'answer_num', 'contentList', 'state'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $params, true);
        $checkLenLimitList = [
            'title' => 50,
            'answer_type' => 50
        ];
        $this->checkApiInvalidArgumentLenOverLimit($checkLenLimitList, $params);

        /** 2. check topic content */
        if (count($params['contentList']) === 0) {
            throw new Exception('题目内容不能为空', 3001);
        }
        if($filter['answer_num'] < 1) {
            throw new Exception('正确题目选项错误', 3001);
        }
        /** 3. check data */
        $state = Constants::YES_VALUE;
        if ($params['state'] && in_array($params['state'], [Constants::YES_VALUE, Constants::NO_VALUE])) {
            $state = $params['state'];
        }
        KnowledgeService::getInstance()->checkById($filter['knowledge_id']);

        /** 4. save topic info */
        $content = [
            'answer_num' => $filter['answer_num'] - 1,
            'list'       => $filter['contentList'],
            'pic'        => $params['pic'] ? $params['pic'] : '',
        ];
        $condition = [
            'title'         => $filter['title'],
            'type'          => $filter['type'],
            'answer_type'   => $filter['answer_type'],
            'knowledge_id'  => $filter['knowledge_id'],
            'content'       => json_encode($content, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'state'         => $state
        ];

        /** 5. update base knowledge */
        if ($params['id']) {
            $this->checkById($params['id']);
            $where = ['id' => $params['id']];
            $update = $condition;
            return IoC()->Topic_model->_update($where, $update);
        } else {
            $insert = $condition;
            return IoC()->Topic_model->_insert($insert);
        }
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
        $topic = IoC()->Topic_model->getByID($id);
        if (empty($topic)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('TopicObj', 'id');
        }
        $topic['content'] = json_decode($topic['content'], true);
        return $topic;
    }
#endregion
}