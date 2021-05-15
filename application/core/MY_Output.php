<?php

class MY_Output extends CI_Output
{

    public function __construct()
    {
        parent::__construct();
    }

    public function myOutput($response)
    {
        if (is_array($response)) {
            $str = json_encode($response);
        } else {
            $str = $response;
        }

        return $this->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output($str);
    }

    /**
     * @param $response
     * 统一在MY_Controller 中处理
     */
    /* function setAccessHeader() {
         $this->set_header('Access-Control-Allow-Origin:*');
         $this->set_header('Access-Control-Allow-Methods:POST');
         $this->set_header('Access-Control-Allow-Headers:x-requested-with,content-type');
         return $this;
     }*/
}
