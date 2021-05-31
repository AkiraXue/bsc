<?php
/**
 * rule.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/31/21 9:31 PM
 */


/**
 * Class Punch
 */
class Punch extends MY_Controller
{
#region init
    public function __construct()
    {
        parent::__construct();
    }
#endregion

#region base
    public function index()
    {
        $result = <<<EOF
冲顶答题
活动规则：活动期间，每人每日有1次冲顶答题的机会，将随机出现5题，每答对1题可获得30积分，冲顶成功则可额外获得50积分的加权分，若在答题过程中回答错误，则此次冲顶失败，答题结束。
封顶积分：200分
活动封顶积分：4200积分

活动周期
从员工登录到小程序第1天开始
常规打卡/冲顶答题活动：3周，21天
线上分组活动：新员工培训前1周（本次上线暂无）

积分兑换
积分有效期：4周，28天（3周活动时间，1周积分兑换时间）
积分规则：员工可通过活动参与，每日获得相应的积分。不同积分额度可兑换不同奖励，已兑换的积分将做扣除。积分有效期共4周，过期后所有积分清零，请及时兑换。
EOF;
;
        $this->_success($result);
    }
#endregion
}