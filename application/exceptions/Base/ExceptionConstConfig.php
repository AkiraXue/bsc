<?php
/**
 * ExceptionConstConfig.php
 * 抛错常量定义
 *
 * @copyright Co\pyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 2020-07-14 10:09
 */

namespace Exception\Base;

class ExceptionConstConfig
{
    /** 网关校验错误 - 1 */
    /** 网关校验的参数验证为空  */
    const GATEWAY_AUTH_ARGUMENT_INVALID_ERROR_CODE = 001;
    const GATEWAY_AUTH_ARGUMENT_INVALID_ERROR_MSG = 'gateway auth argument %s invalid';

    /** 请求方式错误 */
    const GATEWAY_AUTH_REQUEST_METHOD_ERROR_CODE = 002;
    const GATEWAY_AUTH_REQUEST_METHOD_ERROR_MSG = 'gateway auth request method error';

    /** 网关校验错误 - 1 end */

    /******** 接口的参数验证失败 - 2 start *******/
    /** 接口的参数验证为空 */
    const API_ARGUMENT_INVALID_ERROR_CODE = 001;
    const API_ARGUMENT_INVALID_ERROR_MSG = 'api argument %s invalid';

    /** 参数超过限定长度 */
    const API_ARGUMENT_LENGTH_OVER_LIMIT_ERROR_CODE = 002;
    const API_ARGUMENT_LENGTH_OVER_LIMIT_ERROR_MSG = 'api argument %s length over limit %s';

    /** 参数校验类型错误 */
    const API_VERIFY_ARGUMENT_TYPE_ERROR_CODE = 003;
    const API_VERIFY_ARGUMENT_TYPE_ERROR_MSG = 'api verify argument %s type %s error in control %s with function %s';

    /** ucenter accountID 初始化失败 */
    const API_USER_UCENTER_ACCOUNT_INITIAL_ERROR_CODE = 101;
    const API_USER_UCENTER_ACCOUNT_INITIAL_ERROR_MSG = 'ucenter account initial fail';

    /** 用户未入职，不可修改详细信息 */
    const API_USER_HAS_NOT_ENTRY_ERROR_CODE = 102;
    const API_USER_HAS_NOT_ENTRY_ERROR_MSG = 'use has not entry';

    /** 用户请求acl端口设置用户角色失败 */
    const API_USER_ROLE_SET_ERROR_CODE = 103;
    const API_USER_ROLE_SET_ERROR_MSG = 'user request acl api set role error';

    /** 用户不在集团机构中 */
    const API_USER_NOT_IN_ORGANIZATION_ERROR_CODE = 104;
    const API_USER_NOT_IN_ORGANIZATION_ERROR_MSG = 'user not in organization or child department';

    /** 用户关联的部门id不存在 */
    const USER_RELATE_DEPARTMENT_ID_NOT_EXIST_ERROR_CODE = 105;
    const USER_RELATE_DEPARTMENT_ID_NOT_EXIST_ERROR_MSG = 'user relate departmentID not exist';

    /** 用户标签数量超过长度限制 */
    const USER_TAG_NUM_OVER_LENGTH_LIMIT_ERROR_CODE = 106;
    const USER_TAG_NUM_OVER_LENGTH_LIMIT_ERROR_MSG = 'user tag num over length limit';

    /** 打开时间超过下班时间 */
    const API_PUNCH_TIME_OVER_ATTEND_DEADLINE_TIME_ERROR_CODE = 201;
    const API_PUNCH_TIME_OVER_ATTEND_DEADLINE_TIME_ERROR_MSG = '超过下班时间';

    /** 上个时间段还没下班 */
    const API_PREVIOUS_TIME_PERIOD_HAS_NOT_ATTEND_LEAVE_ERROR_CODE = 202;
    const API_PREVIOUS_TIME_PERIOD_HAS_NOT_ATTEND_LEAVE_ERROR_MSG = '上个时间段还没下班';

    /** 已打上班卡 */
    const API_ATTEND_INITIAL_TIME_PUNCH_LOG_HAS_EXIST_ERROR_CODE = 203;
    const API_ATTEND_INITIAL_TIME_PUNCH_LOG_HAS_EXIST_ERROR_MSG = '已打上班卡';

    /** 无法在本班次打卡，下个班次已经激活生效 */
    const API_NEXT_TIME_PERIOD_SCHEDULE_PLAN_HAS_ACTIVATED_ERROR_CODE = 204;
    const API_NEXT_TIME_PERIOD_SCHEDULE_PLAN_HAS_ACTIVATED_ERROR_MSG = '无法在本班次打卡，下个班次已经激活生效';

    /** 已打下班卡 */
    const API_ATTEND_DEADLINE_TIME_PUNCH_LOG_HAS_EXIST_ERROR_CODE = 205;
    const API_ATTEND_DEADLINE_TIME_PUNCH_LOG_HAS_EXIST_ERROR_MSG = 'attend punch log on deadline time has exist';

    /** 打卡考勤的设备code未注册 */
    const API_PUNCH_DEVICE_CODE_HAS_NOT_REGISTER_ERROR_CODE = 206;
    const API_PUNCH_DEVICE_CODE_HAS_NOT_REGISTER_ERROR_MSG = 'punch deviceCode has not register';

    /** 补打卡类型错误 */
    const API_PUNCH_AFTER_APPLY_TYPE_NOT_IN_CORRECT_RANGE_ERROR_CODE = 207;
    const API_PUNCH_AFTER_APPLY_TYPE_NOT_IN_CORRECT_RANGE_ERROR_MSG = 'punch after apply type not in correct range';

    /** 该时段已打卡，无需重复请求 */
    const API_DUPLICATE_REQUEST_ON_PUNCH_LOG_HAS_EXIST_ERROR_CODE = 208;
    const API_DUPLICATE_REQUEST_ON_PUNCH_LOG_HAS_EXIST_ERROR_MSG = 'duplicate request on punch log has exist';

    /** 补打卡审核通过类型错误 */
    const API_ATTEND_PUNCH_AFTER_APPLY_NOT_IN_WAIT_STATUS_ERROR_CODE = 209;
    const API_ATTEND_PUNCH_AFTER_APPLY_NOT_IN_WAIT_STATUS_ERROR_MSG = 'attend punch after apply not in wait status';

    /** 请假时长为0 */
    const API_REQUEST_ATTEND_TIME_PERIOD_LENGTH_INVALID_ERROR_CODE = 210;
    const API_REQUEST_ATTEND_TIME_PERIOD_LENGTH_INVALID_ERROR_MSG = 'api request attend time period length was zero';

    /** 假期券类型不在正确的范围内 */
    const API_HOLIDAY_TICKET_TYPE_NOT_IN_CORRECT_RANGE_ERROR_CODE = 301;
    const API_HOLIDAY_TICKET_TYPE_NOT_IN_CORRECT_RANGE_ERROR_MSG = 'holiday ticket type not in correct range';

    /** 假期有效期开始时间超过截止时间 */
    const API_HOLIDAY_PERIOD_START_TIME_OVER_END_TIME_ERROR_CODE = 401;
    const API_HOLIDAY_PERIOD_START_TIME_OVER_END_TIME_ERROR_MSG = 'holiday period start time over end time';

    /** 假期时长过短 */
    const API_HOLIDAY_TIME_LENGTH_LESS_ERROR_CODE = 402;
    const API_HOLIDAY_TIME_LENGTH_LESS_ERROR_MSG = 'holiday time length too less';


    /******** 接口的参数验证失败 - 2 end *******/

    /*********** 数据库的参数验证失败 - 3 start *************/
    /** 接口的参数验证为空 */
    const DB_ARGUMENT_INVALID_ERROR_CODE = 001;
    const DB_ARGUMENT_INVALID_ERROR_MSG = 'db verify argument %s invalid in model %s with function %s';

    /** 接口参数对应的数据对象不存在 */
    const DB_INVALID_OBJECT_ERROR_CODE = 002;
    const DB_INVALID_OBJECT_ERROR_MSG = 'db object %s invalid which select by argumentStr %s';

    /** 参数超过限定长度 */
    const DB_ARGUMENT_LENGTH_OVER_LIMIT_ERROR_CODE = 003;
    const DB_ARGUMENT_LENGTH_OVER_LIMIT_ERROR_MSG = 'db argument %s length over limit %s in model %s with function %s';

    /** 数据入参校验配置错误 */
    const DB_INVALID_VERIFY_ARGUMENT_CONFIG_ERROR_CODE = 004;
    const DB_INVALID_VERIFY_ARGUMENT_CONFIG_ERROR_MSG = 'db verify argument config %s invalid in object %s with function %s';

    /** 参数校验类型错误 */
    const DB_VERIFY_ARGUMENT_TYPE_ERROR_CODE = 005;
    const DB_VERIFY_ARGUMENT_TYPE_ERROR_MSG = 'db verify argument %s type %s error in model %s with function %s';



    /** 假期券起始时间配置错误 */
    const DB_HOLIDAY_TICKET_START_TIME_SETTING_ERROR_CODE = 201;
    const DB_HOLIDAY_TICKET_START_TIME_SETTING_ERROR_MSG = 'holiday ticket time column %s config error';

    /** 假期券状态不为已创建未分发，当前不可分发 */
    const DB_HOLIDAY_TICKET_STATUS_NOT_IN_WAIT_SEND_STATUS_ERROR_CODE = 202;
    const DB_HOLIDAY_TICKET_STATUS_NOT_IN_WAIT_SEND_STATUS_ERROR_MSG = 'holiday ticket status not in wait send status';

    /** 假期券状态不为已分发未使用，当前不可激活 */
    const DB_HOLIDAY_TICKET_STATUS_NOT_IN_HAS_SEND_STATUS_ERROR_CODE = 203;
    const DB_HOLIDAY_TICKET_STATUS_NOT_IN_HAS_SEND_STATUS_ERROR_MSG = 'holiday ticket status not in has sended status';

    /** 假期券状态不可过期 */
    const DB_HOLIDAY_TICKET_STATUS_CANNOT_EXPIRE_ERROR_CODE = 204;
    const DB_HOLIDAY_TICKET_STATUS_CANNOT_EXPIRE_ERROR_MSG = 'holiday ticket status cannot expire';

    /** 假期券状态不可删除 */
    const DB_HOLIDAY_TICKET_STATUS_CANNOT_DELETE_ERROR_CODE = 205;
    const DB_HOLIDAY_TICKET_STATUS_CANNOT_DELETE_ERROR_MSG = 'holiday ticket status cannot delete';

    /** 当前假期申请状态错误，不可审核 */
    const DB_ATTEND_LEAVE_APPLY_NOT_IN_WAIT_CHECK_STATUS_ERROR_CODE = 206;
    const DB_ATTEND_LEAVE_APPLY_NOT_IN_WAIT_CHECK_STATUS_ERROR_MSG = 'attend leave apply not in wait check status';

    /** 当前假期申请状态错误，不可修改 */
    const DB_ATTEND_LEAVE_APPLY_NOT_IN_MODIFY_STATUS_ERROR_CODE = 207;
    const DB_ATTEND_LEAVE_APPLY_NOT_IN_MODIFY_STATUS_ERROR_MSG = 'attend leave apply not in wait modify status';

    /** 当前假期类型不可修改 */
    const DB_HOLIDAY_TYPE_CAN_NOT_MODIFY_ERROR_CODE = 208;
    const DB_HOLIDAY_TYPE_CAN_NOT_MODIFY_ERROR_MSG = 'current holiday setting type cannot modify';


    /** 不支持的证件类型 */
    const DB_USER_CERTIFICATE_TYPE_INVALID_ERROR_CODE = 301;
    const DB_USER_CERTIFICATE_TYPE_INVALID_ERROR_MSG = '不支持的证件类型';

    /** 邮箱已存在 */
    const DB_USER_EMAIL_EXIST_ERROR_CODE = 302;
    const DB_USER_EMAIL_EXIST_ERROR_MSG = '邮箱已存在';

    /** 员工性质不在可转正范围内 */
    const DB_USER_EMPLOYEE_TYYE_NOT_IN_TRANSFER_REGULAR_RULE_ERROR_CODE = 303;
    const DB_USER_EMPLOYEE_TYYE_NOT_IN_TRANSFER_REGULAR_RULE_ERROR_MSG = 'user employeeType not in transfer regular rule';

    /**  用户当前状态不可离职 */
    const DB_USER_COMPANY_EMPLOYEE_TYPE_NOT_IN_DEPARTURE_STATUS_ERROR_CODE = 304;
    const DB_USER_COMPANY_EMPLOYEE_TYPE_NOT_IN_DEPARTURE_STATUS_ERROR_MSG = 'user company employee type not is departure status';

    /** 初始入职的员工性质类型只可为试用/实习/转正/兼职 */
    const DB_USER_ORIGIN_ENTRY_EMPLOYEE_TYPE_NOT_IN_TRANSFER_REGULAR_RULE_ERROR_CODE = 305;
    const DB_USER_ORIGIN_ENTRY_EMPLOYEE_TYPE_NOT_IN_TRANSFER_REGULAR_RULE_ERROR_MSG = 'origin entry user employeeType not in transfer regular rule';

    /** 没有上传识别图片 */
    const DB_UPLOAD_IDENTIFY_IMG_NOT_EXIST_ERROR_CODE = 401;
    const DB_UPLOAD_IDENTIFY_IMG_NOT_EXIST_ERROR_MSG = '没有上传识别图片';


    /*********** 数据库的参数验证失败 - 3 end ******************/

    /************** 接口的对数据库的操作出现错误 - 4 start ****************/
    /** 数据库操作存在错误 */
    const DB_OPERATION_EXIST_ERROR_CODE = 001;
    const DB_OPERATION_EXIST_ERROR_MSG = 'db %s operation error';


    /************** 接口的对数据库的操作出现错误 - 4 end ****************/

}
