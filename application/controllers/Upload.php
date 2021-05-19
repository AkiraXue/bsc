<?php

use Lib\Helper;

/**
 * Upload.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/24/21 1:27 PM
 */

class Upload extends MY_Controller
{
    public $config;

    public function __construct()
    {
        parent::__construct();
        $this->initUploadConfig();
    }

    private function initUploadConfig()
    {
        $this->config = [
            'allowed_types' => ALLOW_RESOURCE_TYPE,
            'max_size'      => MAX_BUFFER_LIMIT,
            'max_width'     => 0,
            'max_height'    => 0,
            'min_width'     => 0,
            'min_height'    => 0,
            'file_ext_tolower'   => false,
            'overwrite'   => false,
        ];
        $this->load->library('upload', $this->config);
    }

    /**
     * @throws Exception
     */
    public function index()
    {
        /** 1. get base name  */
        $filename = $_FILES['file']['name'];
        $suffix = pathinfo($filename, PATHINFO_EXTENSION);;
        $filename = date('Ymdhis').'_'.Helper::random_string('alnum', 8).'.'.$suffix;

        /** 2. get base info */

        $resourcePath =  APPPATH . '../'. ARCHIVE_PATH;
        $levelDir = date('Y/m/d', time());
        $path = $resourcePath . $levelDir;
        if(!is_dir($path)){
            mkdir($path,0777,true);
        }
        $imgPath = ARCHIVE_PATH . $levelDir . '/';

        /** 3. get upload path  */
        $this->config['upload_path'] = $path;
        $this->config['file_name'] = $filename;

        $this->upload->initialize($this->config);
        $result = $this->upload->do_upload('file');

        if (!$result) {
            $responseMsg = $this->upload->display_errors();
            throw new Exception($responseMsg, 2001);
        }
        $response = ['file_name' => CDN_HOST . $imgPath . $filename];
        $this->_success($response);
    }


}