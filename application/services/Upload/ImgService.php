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
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }
#endregion

#region func
    /**
     * @return bool
     */
    public function refreshImgExif()
    {
        $path1 = $resourcePath = APPPATH . '../resource/upload/2021/06/07';
        $this->refreshImgExifInfo($path1);

//        $path2 = $resourcePath = APPPATH . '../resource/origin';
//        $this->refreshImgExifInfo($path2);

        return true;
    }
#endregion

#region tool
    /**
     * @param $path
     * @return array
     */
    public function refreshImgExifInfo($path)
    {
        $dirFiles = Helper::getDir($path);

        $fileList = [];
        $allowArr = ['gif', 'jpg', 'png', 'jpeg'];
        foreach ($dirFiles as $filepath) {
            $extension = Helper::get_extension5($filepath);
            $extension = strtolower($extension);
            if (!in_array($extension, $allowArr)) {
                continue;
            }
            $fileList[] = $filepath;
        }
        unset($dirFiles);

        $total = count($fileList);
        if ($total == 0) {
            return ['file_count' => $total];
        }

        foreach ($fileList as $filepath) {
            /** rotate && refresh img */
            ImgService::getInstance()->rotate($filepath);

            ImgService::getInstance()->clearImgExif($filepath);
        }

        return ['file_count' => $total];
    }

    /**
     * @param $path
     * @return bool
     */
    public function rotate($path)
    {
        $image = imagecreatefromstring(file_get_contents($path));
        $exif = exif_read_data($path);
        if(empty($exif['Orientation'])) {
           return true;
        }
        switch($exif['Orientation']) {
            case 8:
                $image = imagerotate($image,90,0);
                break;
            case 3:
                $image = imagerotate($image,180,0);
                break;
            case 6:
                $image = imagerotate($image,-90,0);
                break;
        }
        imagejpeg($image, $path);
        imagedestroy($image);
        return true;
    }

    /**
     * clear img exif
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