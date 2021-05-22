<?php
/**
 * Http.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/22/21 11:12 AM
 */

namespace Lib;

class Http
{
    private static $errno = 0;
    private static $errinfo = '';

    public static function request($url, $data, $method = 'POST', $waitForResponse = true, $timeout = 60)
    {
        self::$errno = 0;
        self::$errinfo = '';
        $urlarr = parse_url($url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        if (strtolower($urlarr['scheme']) == 'https') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        }
        if ($urlarr['port'])
            curl_setopt($ch, CURLOPT_PORT, $urlarr['port']);
        if (strtoupper($method) == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        } else //GET method
        {
            if ($data) {
                if (false === strpos($url, '?'))
                    $url .= '?' . $data;
                else
                    $url .= '&' . $data;
            }
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        $output = curl_exec($ch);
        self::$errno = curl_errno($ch);
        self::$errinfo = curl_error($ch);
        curl_close($ch);
        return $output;
    }

    public static function JsonHeaderRequest($url, $data, $method = 'POST', $waitForResponse = true, $timeout = 60)
    {
        self::$errno = 0;
        self::$errinfo = '';
        $urlarr = parse_url($url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

        $headers = array(
            "Content-type: application/json;charset='utf-8'",
            "Accept: application/json",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if (strtolower($urlarr['scheme']) == 'https') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        }
        if ($urlarr['port'])
            curl_setopt($ch, CURLOPT_PORT, $urlarr['port']);
        if (strtoupper($method) == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        } else //GET method
        {
            if ($data) {
                if (false === strpos($url, '?'))
                    $url .= '?' . $data;
                else
                    $url .= '&' . $data;
            }
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        $output = curl_exec($ch);
        self::$errno = curl_errno($ch);
        self::$errinfo = curl_error($ch);
        curl_close($ch);
        return $output;
    }

    public static function result()
    {
        return ['errno' => self::$errno, 'errinfo' => self::$errinfo];
    }
}