<?php
/**
 * UserInfoService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/16/21 2:53 PM
 */

namespace Service\User;


use Lib\Constants;

use Service\BaseTrait;
use Service\BaseService;

use Exception\Common\DBInvalidObjectException;

/**
 * Class UserInfoService
 * @package Service\User
 */
class UserInfoService extends BaseService
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
     * @param string $activityCode
     * @param string $accountId
     * @param int $isThrowError
     *
     * @return array
     * @throws DBInvalidObjectException
     */
    public function checkByAccountId(string $accountId, $isThrowError=Constants::YES_VALUE)
    {
        $condition = [
            'account_id'     => $accountId,
        ];
        $userInfo = IoC()->User_model->get($condition);
        if (empty($userInfo)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('UserObj', 'account_id');
        }
        return $userInfo;
    }
#endregion
}
