<?php
/**
 * Helper.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/16/21 10:28 AM
 */

namespace Lib;

use Exception;

class Helper
{
    /**
     * 遍历文件
     *
     * @param $dir
     * @return array
     */
    public static function myScanDir($dir)
    {
        $file_arr = scandir($dir);
        $new_arr = [];
        foreach($file_arr as $item){

            if($item!=".." && $item !="."){

                if(is_dir($dir."/".$item)){

                    $new_arr[$item] = self::myScanDir($dir."/".$item);

                }else{
                    $new_arr[] = $item;
                }
            }
        }
        return $new_arr;
    }

    /**
     * 遍历文件
     *
     * @param $path
     * @return array
     */
    public static function getDir($path)
    {
        //判断目录是否为空
        if(!file_exists($path)) {
            return [];
        }

        $files = scandir($path);
        $fileItem = [];
        foreach($files as $v) {
            $newPath = $path .DIRECTORY_SEPARATOR . $v;
            if(is_dir($newPath) && $v != '.' && $v != '..') {
                $fileItem = array_merge($fileItem, self::getDir($newPath));
            }else if(is_file($newPath)){
                $fileItem[] = $newPath;
            }
        }

        return $fileItem;
    }


    /**
     * @param $password
     * @return string
     */
    public static function encryptPass($password)
    {
        return md5(sha1($password));
    }

    /*
     * content: 根据数组某个字段进行排序
     * $arr    需要排序的数组
     * $field  数组里的某个字段
     * sort    1为正序排序  2为倒序排序
     */
    public static function itemSort($arr, $field, $sort){
        $item = array();
        foreach($arr as $kay => $value) {
            $item[] = $value[$field];
        }
        if($sort==1) {
            array_multisort($item,SORT_ASC, $arr);
        }else{
            array_multisort($item,SORT_DESC, $arr);
        }
        return $arr;
    }

    /**
     * 解密
     *
     * @param $encrypted
     * @return string
     */
    public static function decrypt($encrypted)
    {
        $decrypted = openssl_decrypt(
            $encrypted,
            'aes-128-cbc',
            Constants::CRYPT_KEY,
            OPENSSL_ZERO_PADDING ,
            Constants::CRYPT_IV
        );

        return trim($decrypted);
    }

    /**
     * Create a Random String
     *
     * Useful for generating passwords or hashes.
     *
     * @param    string    type of random string.  basic, alpha, alnum, numeric, nozero, md5 and sha1
     * @param    int    number of characters
     * @return    string
     * @throws   Exception
     */
    public static function random_string($type = 'alnum', $len = 8)
    {
        switch ($type) {
            case 'basic':
                return mt_rand();
            case 'alnum':
            case 'numeric':
            case 'nozero':
            case 'alpha':
                switch ($type) {
                    case 'alpha':
                        $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'alnum':
                        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'numeric':
                        $pool = '0123456789';
                        break;
                    case 'nozero':
                        $pool = '123456789';
                        break;
                }
                return substr(str_shuffle(str_repeat($pool, ceil($len / strlen($pool)))), 0, $len);
            case 'md5':
                return md5(uniqid(mt_rand()));
            case 'sha1':
                return sha1(uniqid(mt_rand(), true));
        }
        throw new Exception('type not exist');
    }

   public static function gen_uuid()
   {
       return sprintf('%04x%04x%04x%04x%04x%04x%04x%04x',
           // 32 bits for "time_low"
           mt_rand(0, 0xffff), mt_rand(0, 0xffff),

           // 16 bits for "time_mid"
           mt_rand(0, 0xffff),

           // 16 bits for "time_hi_and_version",
           // four most significant bits holds version number 4
           mt_rand(0, 0x0fff) | 0x4000,

           // 16 bits, 8 bits for "clk_seq_hi_res",
           // 8 bits for "clk_seq_low",
           // two most significant bits holds zero and one for variant DCE1.1
           mt_rand(0, 0x3fff) | 0x8000,

           // 48 bits for "node"
           mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
       );
   }
}