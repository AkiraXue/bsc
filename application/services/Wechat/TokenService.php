<?php
/**
 * TokenService.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/22/21 12:03 PM
 */

namespace Service\Wechat;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;

/**
 * Class TokenService
 * @package Service\Wechat
 */
class TokenService
{
 #region init
    const ISS = 'JWT_TOKEN';

    public $token;
    public static $instance;

    public function __construct()
    {

    }

    public static function getInstance()
    {
        if (!self::$instance instanceof TokenService) {
            self::$instance = new self();
        }
        return self::$instance;
    }
#endregion

#region base info
    public function getAccountId()
    {
        $token = self::getBearerToken();

        $claims = self::parseToken($token);

        return $claims['account_id'] ? : '';

    }

    public function getOpenid()
    {
        $token = self::getBearerToken();

        $claims = self::parseToken($token);

        return $claims['openid'] ? : '';
    }
#endregion

#region  bearerToken
    /**
     * @return mixed|null
     */
    public static function getBearerToken()
    {
        $headers = self::getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    private static function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }
#endregion

#region token make & parse
    /**
     * 生成分享token
     *
     * @param array $claims
     * @param $string
     *
     * @return array
     */
    public static function makeToken(array $claims, $string)
    {
        $signer =  new Sha256();
        $lifeTimeSeconds = 3600 * 24 * 30;
        $expiration = time() + $lifeTimeSeconds;
        $builder = (new Builder())->issuedBy(self::ISS)
            ->issuedAt(time())
            ->canOnlyBeUsedAfter(time())
            ->expiresAt($expiration);// 15d 过期
        foreach ($claims as $key => $value) {
            $builder->withClaim($key, $value);
        }
        $accessToken = (string)$builder->getToken($signer, new Key($string));

        return ['access_token' => $accessToken, 'expired_at' => $expiration];
    }

    /**
     * 解析分组共享token
     *
     * @param string $token
     *
     * @return array
     */
    public static function parseToken(string $token)
    {
        $parser = new Parser();
        $token = $parser->parse((string) $token);
        $claims = $token->getClaims();

        return $claims;
    }
#endregion
}