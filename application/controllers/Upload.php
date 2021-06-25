<?php
/**
 * Upload.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/24/21 1:27 PM
 */


use Lib\Helper;

use Service\Upload\ImgService;
use Service\Upload\UploadService;
use Service\Upload\OssUploadService;

/**
 * Class Upload
 */
class Upload extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('upload', $this->config);
    }

    /**
     * @throws Exception
     */
    public function uploadOss()
    {
        $response = OssUploadService::getInstance()->upload();

        $this->_success($response);
    }

    /**
     * @throws Exception
     */
    public function upload()
    {
        $response = UploadService::getInstance()->upload($this->upload);

        $this->_success($response);
    }

    /**
     * @throws Exception
     */
    public function refreshImgExif()
    {
        $response = ImgService::getInstance()->refreshImgExif();
        $this->_success($response);
    }

}