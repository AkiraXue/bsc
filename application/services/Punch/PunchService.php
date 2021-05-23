<?php
/**
 * PunchService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/23/21 2:59 PM
 */

namespace Service\Punch;

use Service\BaseTrait;
use Service\BaseService;

/**
 * Class PunchService
 * @package Service\Punch
 */
class PunchService extends BaseService
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




}

