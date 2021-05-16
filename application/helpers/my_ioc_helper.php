<?php

/**
 * Class IoCMgr
 * IoC容器
 */
class IoCMgr
{

    public function __get($propertyName)
    {
        $propertyName = trim($propertyName);
        if (!$propertyName) {
            return null;
        }

        $CI = &get_instance();

//		preg_match("/.*(module)$/",$propertyName, $matches);
//		if (isset($matches[1])) {
        $modelName = $propertyName;
        if (isset($this->registerModelDic[$modelName])) {
            $modelPath = $this->registerModelDic[$modelName];
            $CI->load->model($modelPath);
            return $CI->$modelName;
        }
//		}

//		preg_match("/.*(Lib)$/",$propertyName, $matches);
//		if (isset($matches[1])) {
        $libName = $propertyName;
        if (isset($this->registerLibDic[$libName])) {
            $libPath = $this->registerLibDic[$libName];
            $CI->load->library($libPath, '', $libName);
            return $CI->$libName;
        }
//		}

        return null;
    }

    /* 注册model */
    var $registerModelDic = array();

    public function registerModel($modelName, $modelPath)
    {
        $this->registerModelDic[$modelName] = $modelPath;
    }

    /* 注册lib */
    var $registerLibDic = array();

    public function registerLib($libName, $libPath)
    {
        $this->registerLibDic[$libName] = $libPath;
    }

    function __construct()
    {

    }

}

/**
 *
 * models/
 *
 * @property Activity_model                         $Activity_model
 * @property Activity_schedule_model                $Activity_schedule_model
 *
 * @property Activity_participate_record_model      $Activity_participate_record_model
 * @property Activity_participate_schedule_model    $Activity_participate_schedule_model
 *
 * @property Knowledge_model        $Knowledge_model
 *
 * @property Group_model            $Group_model
 * @property Group_item_model       $Group_item_model
 *
 * @property User_model             $User_model
 *
 * libraries/
 *
 * App/
 */
class HisIoCMgr extends IoCMgr
{

    function __construct()
    {
        parent::__construct();

        /* register module */
        $this->registerModel('Activity_model', 'Activity_model');
        $this->registerModel('Activity_schedule_model', 'Activity_schedule_model');
        $this->registerModel('Activity_participate_schedule_model', 'Activity_participate_schedule_model');
        $this->registerModel('Activity_participate_record_model', 'Activity_participate_record_model');


        $this->registerModel('Knowledge_model', 'Knowledge_model');

        $this->registerModel('Group_model', 'Group_model');
        $this->registerModel('Group_item_model', 'Group_item_model');

        $this->registerModel('User_model', 'User_model');

        /* library */
    }
}

function IoC()
{
    static $mgr;
    if (!$mgr) {
        $mgr = new HisIoCMgr();
    }
    return $mgr;
}

