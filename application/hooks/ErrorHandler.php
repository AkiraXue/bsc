<?php
/**
 * ErrorHandler.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 2020-09-18 17:44
 */

defined('BASEPATH') or exit('No direct script access allowed');

use Exception\Base\Base as BaseException;

/**
 * Class ErrorHandler
 */
class ErrorHandler
{
    /**
     * @var CI_Controller
     */
    private $ci;

    public function __construct()
    {
        //不要调用 load 的 initialize 方法
        $this->ci =& get_instance();
        $this->ci->load =& load_class('Loader', 'core');
        $this->ci->output =& load_class('Output', 'core');
    }

    public function registerAllHandler()
    {
        $this->setExceptionHandler();

        $this->setErrorHandler();

        return true;
    }

    private function setExceptionHandler()
    {
        set_exception_handler(function(Throwable $exception) {
            $this->ci->load->helper('url');

            /** initial exception and throw exception */
            /** @var Exception|Throwable $exception */
            $exceptionBase = [
                'state' => BaseException::getErrorCode($exception),
                'data' => [],
                'msg' => $exception->getMessage()
            ];

            $this->ci->output->myOutput($exceptionBase);
            $this->ci->output->_display();

            log_message('error', current_url() .
                ' throw exception:' . $exception->getMessage() .
                ' with param get:' . json_encode($_GET) .
                ' with param post'. json_encode($_POST));
        });
    }

    private function setErrorHandler()
    {
        set_error_handler(function($errno , $errstr, $errfile = '', $errline = 0, $errcontext = []) {
            log_message('error', $errstr . ' occur in '.$errfile . ' of line '. $errline);
        }, E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
    }
}