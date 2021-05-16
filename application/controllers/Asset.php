<?php
/**
 * Asset.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/17/21 12:54 AM
 */

use Lib\Constants;

use Service\Asset\AssetService;

/**
 * Class Activity
 */
class Asset extends MY_Controller
{
#region init
    public function __construct()
    {
        parent::__construct();
    }
#endregion

#region func
    /**
     * @throws Exception
     */
    public function save()
    {
        $data = $this->input->post(null, true);

        $result = AssetService::getInstance()->save($data);

        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function get()
    {
        $data = $this->input->post(null, true);
        $necessaryParamArr = ['code'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $data, true);
        $result = AssetService::getInstance()->checkByUniqueCode($filter['unique_code'], Constants::NO_VALUE);
        $this->_success($result);
    }

    /**
     * 搜索
     */
    public function find()
    {
        $data = $this->input->post(null, true);
        $result = AssetService::getInstance()->find($data);
        $this->_success($result);
    }
#endregion
}

