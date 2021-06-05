<?php
/**
 * AssetLogService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 6/6/21 1:42 AM
 */

namespace Service\Asset;

use Service\BaseTrait;
use Service\BaseService;


class AssetLogService extends BaseService
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

#region base func
    /**
     * @param array $params
     * @return array
     */
    public function find(array $params)
    {
        $condition = [];

        empty($params['unique_code']) || $condition['unique_code'] = $params['unique_code'];
        empty($params['source']) || $condition['source'] = $params['source'];
        empty($params['type']) || $condition['type'] = $params['type'];

        $page = $params['page'];
        $limit = $params['limit'];
        $page = !empty($page) ? intval($page) : 1;
        $limit = !empty($limit) ? intval($limit) : 10;

        $data = IoC()->Asset_change_log_model->findLeftJoinItem($condition, $count, $page, $limit);
        $totalPage = ceil($count / $limit);
        $totalPage = $totalPage ? $totalPage : 1;
        return [
            'list' => $data,
            'total' => $count,
            'total_page' => $totalPage
        ];
    }

#endregion
}
