<?php
/**
 * OssUploadService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/29/21 1:24 PM
 */

namespace Service\Upload;

use Exception;

use Lib\Helper;
use OSS\OssClient;
use OSS\Core\OssException;

use Service\BaseTrait;
use Service\BaseService;

/**
 * Class OssUploadService
 * @package Service\OssUpload
 */
class OssUploadService extends BaseService
{
    use BaseTrait;

    public $ossClient;

#region initial info
    public static $instance;

    public function __construct()
    {
        parent::__construct();

        try {
            $this->ossClient = new OssClient(
                ALI_OSS_ACCESS_KEY_ID,
                ALI_OSS_ACCESS_KEY_SECRET,
                ALI_OSS_ENDPOINT,
                true
            );
            // 设置Socket层传输数据的超时时间，单位秒，默认5184000秒。
            $this->ossClient->setTimeout(3600);
            // 设置建立连接的超时时间，单位秒，默认10秒。
            $this->ossClient->setConnectTimeout(10);
        } catch (OssException $e) {
            print $e->getMessage();
        }
    }

    public static function getInstance()
    {
        if (!self::$instance instanceof self){
            self::$instance = new self() ;
        }
        return self::$instance;
    }
#endregion

#region base init
    public function getConfig()
    {
        /** policy => obj to base 64 */
        $policyObj = new \stdClass();
        $policyObj->expiration = '2022-12-01T12:00:00.000Z';

        $bucketObj = new \stdClass();
        $bucketObj->bucket = ALI_OSS_BUCKET;
        $policyObj->conditions = [
            $bucketObj,
            ["starts-with", "test", "bsc/"]
        ];

        /** base64(hmac-sha1(base64(policy), AccessKeySecret)) */

    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function upload()
    {
        /** 1. get base name  */
        $filename = $_FILES['file']['name'];
        $suffix = pathinfo($filename, PATHINFO_EXTENSION);
        $filename = date('Ymdhis').'_'.Helper::random_string('alnum', 8).'.'.$suffix;

        /** 2. touch file path */
        /** <yourLocalFile>由本地文件路径加文件名包括后缀组成，例如/users/local/myfile.txt */
        $levelDir = date('/Y/m/d/', time());
        $filePath =  $_FILES['tmp_name'];

        /** 3. upload */
        return $this->ossClient->uploadFile(ALI_OSS_BUCKET, $filename, $filePath);
    }



#endregion
}