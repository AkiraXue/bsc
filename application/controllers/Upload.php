<?php
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

    public function index()
    {
        /** 1. set  filename & path */
        $filename = date('Ymdhis').random_string('alnum',4).'.jpeg';
        $this->config['upload_path'] = FCPATH  . ARCHIVE_PATH;

        /** 2. init config */
        $this->upload->initialize($this->config);
        $result = $this->upload->do_upload('file');
        $this->_success($result);
    }


}