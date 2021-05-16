<?php
/**
 * KnowledgeService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/16/21 1:05 PM
 */

namespace Service\Activity;

use Exception;

use Lib\Helper;
use Lib\Constants;

use Service\BaseTrait;
use Service\BaseService;

use Exception\Common\DBInvalidObjectException;

/**
 * Class KnowledgeService
 * @package Service
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

#region base func
    /**
     * @param int  $id
     * @param int  $isThrowError
     *
     * @return array
     * @throws Exception
     */
    public function checkKnowledgeById(int $id, $isThrowError=Constants::YES_VALUE)
    {
        $knowledge = IoC()->Activity_schedule_model->getByID($id);
        if (empty($knowledge)) {
            if ($isThrowError == Constants::NO_VALUE) {
                return [];
            }
            throw new DBInvalidObjectException('KnowledgeObj', 'id');
        }
        return $knowledge;
    }

#region
}