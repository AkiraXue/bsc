<?php
/**
 * UploadService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/29/21 2:49 PM
 */

namespace Service\Upload;

use CI_Upload;
use Exception;
use Lib\Helper;

use Service\BaseTrait;
use Service\BaseService;

/**
 * Class UploadService
 * @package Service\Upload
 */
class UploadService extends BaseService
{
    use BaseTrait;

    public $config;

#region initial info
    public static $instance;

    public function __construct()
    {
        parent::__construct();
        $this->initUploadConfig();
    }

    public static function getInstance()
    {
        if (!self::$instance instanceof self){
            self::$instance = new self() ;
        }
        return self::$instance;
    }
#endregion

#region init
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
    }
#endregion

#region
    /**
     * @param CI_Upload $uploadClient
     *
     * @return array
     * @throws Exception
     */
    public function upload($uploadClient)
    {
        /** 1. get base name  */
        $filename = $_FILES['file']['name'];
        $suffix = pathinfo($filename, PATHINFO_EXTENSION);;
        $filename = date('Ymdhis').'_'.Helper::random_string('alnum', 8).'.'.$suffix;

        /** 2. get base info */
        $resourcePath =  APPPATH . '../resource/'. ARCHIVE_PATH;
        $levelDir = date('Y/m/d', time());
        $path = $resourcePath . $levelDir;
        if(!is_dir($path)){
            mkdir($path,0777,true);
        }
        $imgPath = ARCHIVE_PATH . $levelDir . '/';

        /** 3. get upload path  */
        $this->config['upload_path'] = $path;
        $this->config['file_name'] = $filename;
        $uploadClient->initialize($this->config);
        $result = $uploadClient->do_upload('file');
        if (!$result) {
            $responseMsg = $uploadClient->display_errors();
            throw new Exception($responseMsg, 2001);
        }

        /** 4. clear exif info */
        // ImgService::getInstance()->clearImgExif($path . $filename);

        /** return response */
        $response = ['file_name' => $imgPath . $filename];
        return $response;
    }
#endregion

}