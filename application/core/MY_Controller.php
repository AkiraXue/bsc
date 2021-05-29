<?php
/**
 * Class MY_Controller
 * CI_Controller封装
 *
 * @property CI_Input  input
 * @property MY_Output output
 * @property CI_Router router
 * @property CI_Lang   lang
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use Service\BaseTrait;

use Service\Wechat\TokenService;
use Service\User\UserInfoService;

use Exception\Common\ApiInvalidArgumentException;

/**
 * Class MY_Controller
 */
class MY_Controller extends CI_Controller
{
    public $isNeedLogin = 1;

    public $openid;
    public $accountId;

    use BaseTrait;

    /**
     * MY_Controller constructor.
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();

        $this->checkCors();

        if ($this->isNeedLogin == 1) {
            $this->checkLogin();
        }

        $isAdmin = $_POST['is_admin'];
        if ($isAdmin) {

        } else {
            UserInfoService::getInstance()->checkByAccountId($this->accountId);
        }

        foreach ($_POST as $key => $value) {
            if (substr($key, 0, 3) === 'ext') {
                $_POST['extra'][$key] = $value;
            }
        }
    }

    public function isNeedLogin()
    {
        // Common class
        $pathInfo = $_SERVER['PATH_INFO'];
        $pathArr = array_reverse(explode(DIRECTORY_SEPARATOR, $pathInfo));
        $className = strtolower($pathArr[1]);
        // 不用check login 的 api
        $noCheckLoginApiList = ['common', 'login'];
        if (in_array($className, $noCheckLoginApiList)) {
            return false;
        }
        return true;
    }

    /**
     * @throws Exception
     */
    private function checkLogin()
    {
        $token = TokenService::getBearerToken();
        if (empty($token)) {
            throw new ApiInvalidArgumentException('authorization header token');
        }
        $claims = TokenService::parseToken($token);
        $currentTime = time();
        $expireTime = $claims['exp']->getValue();

        if ($currentTime > $expireTime) {
            throw new Exception('current app login token has expired', 1001);
        }

        $this->accountId = $claims['account_id'];
        $this->openid = $claims['openid'];
    }

    /**
     * @param null $data
     * @return mixed
     */
    public function _success($data = null)
    {
        if ($data === null) {
            $data = [];
        }
        return $this->_jsonOutput(1, $data, 'success');
    }

    /**
     * @param int $state
     * @param null $data
     * @param string $msg
     *
     * @return mixed
     */
    public function _jsonOutput($state = 1, $data = null, $msg = '')
    {
        if (isset($data['error']) && is_array($data['error'])) {
            $error = $data['error'];
            unset($data['error']);
            return [
                'state' => isset($error['state']) ? (int)$error['state'] : $state,
                'data'  => $data,
                'msg'   => isset($error['msg']) ? $error['msg'] : 'error',
            ];
        }

        $response = ['state' => $state, 'data' => $data, 'msg' => $msg];
        return $this->output->myOutput($response);
    }

    /**
     * 接口调用输出相应的json，并对非成功返回做记录，方面排查
     * @param $res array|string
     * @return string
     */
    public function apiReturn($res)
    {
        // 当传入纯数字（1或者4位数字的时候，自动补全格式）
        if (is_numeric($res) && (strlen($res) == 4 || $res == 1)) {
            $res = ['state' => $res];
        }

        // 接口没有带返回的msg，自动解析语言包填充
        if ($res['state'] && !$res['msg']) {
            $this->setLanguage();

            $method = $this->router->method;
            $lang_file = $this->router->directory . $this->router->class;
            $ucfirst_lang_file = $this->router->directory . ucfirst($this->router->class);

            $real_path = APPPATH . 'language/' . $this->config->item('language') . '/' . $lang_file . "_lang.php";
            $ucfrist_real_path = APPPATH . 'language/' . $this->config->item(
                'language'
            ) . '/' . $ucfirst_lang_file . "_lang.php";
            if (file_exists($real_path)) {
                $this->load->language($lang_file);
                $res['msg'] = $this->lang->line($method . '.' . $res['state']);
            } else {
                if (file_exists(strtolower($real_path))) {
                    $this->load->language(strtolower($lang_file));
                    $res['msg'] = $this->lang->line($method . '.' . $res['state']);
                } else {
                    if (file_exists($ucfrist_real_path)) {
                        $this->load->language($ucfirst_lang_file);
                        $res['msg'] = $this->lang->line($method . '.' . $res['state']);
                    }
                }
            }
        }

        if ($res['msg'] && $res['msg_params']) {
            foreach ($res['msg_params'] as $k => $v) {
                $res['msg'] = str_replace('{' . $k . '}', $v, $res['msg']);
            }
        }
        unset($res['msg_params']);

        $request_url = explode('index.php', $_SERVER['PHP_SELF']);
        $api_url = trim(end($request_url), '/');

        // 1，4001状态码自动加上通用语言包
        if (in_array((int)$res['state'], [1, 4001]) && !$res['msg']) {
            $this->lang->load('common/Validation', $this->config->item('language'));
            $res['msg'] = (string)$this->lang->line('common.' . $res['state']);
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($res))
            ->_display();
        die();
    }

    // 语言包选择
    private function setLanguage()
    {
        $lang_arr = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
        $default_lang = $lang_arr[0];

        $lang_config = [
            'zh_cn'   => 'zh_cn',
            'zh-CN'   => 'zh_cn',
            'zh'      => 'zh_cn',
            'zhCN'    => 'zh_cn',
            'enUS'    => 'english',
            'en-US'   => 'english',
            'en'      => 'english',
            'english' => 'english',
        ];

        $lang = isset($lang_config[$default_lang]) ? $lang_config[$default_lang] : 'zh_cn';
        $this->config->set_item('language', $lang);
    }

    /**
     * 用来检测跨域请求是否合法的情况
     * 考虑到http后端格式
     */
    protected function checkCors()
    {
        if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
            $this->output->set_header("Access-Control-Allow-Origin:*");
            $this->output->set_header("Access-Control-Allow-Credentials:true");
            $this->output->set_header("Access-Control-Allow-Methods:POST,GET,PUT,DELETE,HEAD");
            $this->output->set_header("Access-Control-Allow-Headers:{$_SERVER['Access-Control-Allow-Headers']}");
            $this->output->set_header("Access-Control-Max-Age:86400");
            die();
        } else {
            $this->output->set_header("Access-Control-Allow-Origin:*");
            $this->output->set_header("Access-Control-Allow-Credentials:true");
            $this->output->set_header("Access-Control-Allow-Methods:POST,GET,PUT,DELETE,HEAD");
        }

//        $frontHosts = [];
//        $frontUrl = $_SERVER['APPLICATION_FRONT'];
//        $frontUrl = json_decode(base64_decode($frontUrl));
//        $origin = str_replace(['http://', 'https://', '/'], '', $_SERVER['HTTP_ORIGIN']);
//        if (!empty($frontUrl)) {
//            foreach ($frontUrl as $item) {
//                $host = parse_url($item);
//                $host['host'] && $frontHosts[] = $host['host'];
//            }
//        }
//        if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
//            if (in_array($origin, $frontHosts)) {
//                $this->output->set_header("Access-Control-Allow-Origin:{$_SERVER['HTTP_ORIGIN']}");
//                $this->output->set_header("Access-Control-Allow-Credentials:true");
//                $this->output->set_header("Access-Control-Allow-Methods:POST,GET,PUT,DELETE,HEAD ");
//                $this->output->set_header("Access-Control-Allow-Headers:{$_SERVER['Access-Control-Allow-Headers']}");
//                $this->output->set_header("Access-Control-Max-Age:86400");
//            }
//            die();
//        } else {
//            if (in_array($origin, $frontHosts)) {
//                $this->output->set_header("Access-Control-Allow-Origin:{$_SERVER['HTTP_ORIGIN']}");
//                $this->output->set_header("Access-Control-Allow-Credentials:true");
//                $this->output->set_header("Access-Control-Allow-Methods:POST,GET,PUT,DELETE,HEAD ");
//            }
//        }
    }
}
