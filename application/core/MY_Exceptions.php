<?php

class MY_Exceptions extends CI_Exceptions
{
    private $CI;

    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->CI->output =& load_class('Output', 'core');
    }

    /**
     * 404改写，统一输出
     * @param string $page
     * @param bool $log_error
     */
    public function show_404($page = '', $log_error = true)
    {
        $this->CI->output
            ->set_status_header(404)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode([
                'state' => 10001,
                'msg'   => '接口不存在'
            ]))
            ->_display();
        die();
    }
}
