<?php
/**
 * ImgService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 6/25/21 10:30 AM
 */

namespace Service\Upload;


use Lib\Helper;
use Service\BaseTrait;
use Service\BaseService;

/**
 * Class ImgService
 * @package Service\Upload
 */
class ImgService extends BaseService
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
        if (!self::$instance instanceof self){
            self::$instance = new self() ;
        }
        return self::$instance;
    }
#endregion

#region func
    public function refreshImgExif()
    {
        $path = $resourcePath =  APPPATH . '../resource/origin';

        $dir = Helper::getDir($path);

        echo json_encode(['dir' => $dir]);
    }
#endregion

#region tool
    /**
     * clear img
     *
     * @param $path
     * @return bool
     */
    public function clearImgExif($path)
    {
        $img = imagecreatefromjpeg($path);
        $w = imagesx($img);
        $h = imagesy($img);

        $trans = imagecolortransparent($img);
        if($trans >= 0) {
            $rgb = imagecolorsforindex($img, $trans);
            $oldImg = $img;
            $img = imagecreatetruecolor($w,$h);
            $color = imagecolorallocate($img,$rgb['red'],$rgb['green'],$rgb['blue']);
            imagefilledrectangle($img,0,0,$w,$h,$color);
            imagecopy($img,$oldImg,0,0,0,0,$w,$h);
        }

        return imagejpeg($img,$path);
    }
#endregion
}