<?php
/**
 * rule.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/31/21 9:31 PM
 */

use Lib\Constants;
use Service\Activity\ActivityService;
use Service\BaseSetting\RuleService;


/**
 * Class Punch
 */
class Rule extends MY_Controller
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

        $result = RuleService::getInstance()->save($data);

        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function get()
    {
        $data = $this->input->post(null, true);
        $necessaryParamArr = ['id'];
        $filter = $this->checkApiInvalidArgument($necessaryParamArr, $data, true);
        $result = RuleService::getInstance()->checkById($filter['id'], Constants::NO_VALUE);
        $this->_success($result);
    }

    /**
     * 搜索
     */
    public function find()
    {
        $data = $this->input->post(null, true);
        $result = RuleService::getInstance()->find($data);
        $this->_success($result);
    }

    /**
     * @throws Exception
     */
    public function toggle()
    {
        $data = $this->input->post(null, true);
        $result = RuleService::getInstance()->toggle($data);
        $this->_success($result);
    }
#endregion

#region base
    /**
     * @throws Exception
     */
    public function index()
    {
        $result = RuleService::getInstance()->checkByType(Constants::RULE_TYPE_ALL);
        $this->_success($result['remark']);
    }

    /**
     * @throws Exception
     */
    public function prize()
    {
        $result = RuleService::getInstance()->checkByType(Constants::RULE_TYPE_PRIZE);
        $this->_success($result['remark']);
    }


    public function oldRule()
    {
        $result = <<<EOF
个人积分组成：
    活动期间打卡
    答题冲顶
    线上分组活动（本次上线暂无）

活动期间打卡：

1. 每日签到
活动期间，每人每日可签到一次，每次签到可获得30积分。

2. 每日学习
活动期间，每完成1个模块的知识点学习，可获得150积分，知识点可重复学习，每日封顶150积分。

3. 冲顶答题
活动期间，每人每日有1次冲顶答题的机会，将随机出现5题，每答对1题可获得50积分，冲顶成功则可额外获得50积分的加权分，若在答题过程中回答错误，则此次冲顶失败，答题结束。

4. 活动周期
活动周期21天，从员工登录小程序第1天起始

5. 积分兑换
积分有效期28天，从员工登录小程序第1天起始，已兑换的积分将做扣除，过期后所有积分清零，请及时兑换
EOF;

        $result = <<<EOF
活动规则：
　　活动期间，每人每日有1次冲顶答题的机会，将随机出现5题，每答对1题可获得30积分，冲顶成功则可额外获得50积分的加权分，若在答题过程中回答错误，则此次冲顶失败，答题结束。　

封顶积分：200分
活动封顶积分：4200积分
EOF;

        $result1 = <<<EOF
个人积分组成：
　　 活动期间打卡
	答题冲顶
	线上分组活动（本次上线暂无）

活动期间打卡：
1.  每日签到
	活动规则：活动期间，每人每日可签到一次，每次签到可获得10积分
	每日封顶积分：10积分
	活动封顶积分：210积分

2.	每日学习
	活动规则：活动期间，每完成1个模块的知识点学习，可获得100积分，知识点可重复学习，每日封顶100积分。
	每日封顶积分：100积分
	活动封顶积分：2100积分

冲顶答题：
	活动规则：活动期间，每人每日有1次冲顶答题的机会，将随机出现5题，每答对1题可获得30积分，冲顶成功则可额外获得50积分的加权分，若在答题过程中回答错误，则此次冲顶失败，答题结束。
	封顶积分：200分
	活动封顶积分：4200积分

活动周期：
	从员工登录到小程序第1天开始
	常规打卡/冲顶答题活动：3周，21天
	线上分组活动：新员工培训前1周（本次上线暂无）

积分兑换：
	积分有效期：4周，28天（3周活动时间，1周积分兑换时间）
	积分规则：员工可通过活动参与，每日获得相应的积分。不同积分额度可兑换不同奖励，已兑换的积分将做扣除。积分有效期共4周，过期后所有积分清零，请及时兑换。
EOF;
        ;
    }
#endregion
}