<?php
define('DOMAIN',        'xd_domain');
define('METHOD',        'xd_method');
define('PRIVATE_KEY',   'xd_key');
define('LOCATE_URL',    'xd_locate_url');
define('BRANCH_KEY',    'xd_branch_key');

class DebugApi
{
    private static $config = [];
    private $domain = '';
    private $method = '';
    private $host   = '';
    private $params = [];
    private $locate_url = '';
    private $branch_key = '';

    public function __construct($get) {
        self::$config = loadConfigFiles('api');
        $data = $this->initParam($get);

        $this->domain     = $data['domain'];
        $this->params     = $data['params'];
        $this->method     = $data['method'];
        $this->locate_url = $data['locate_url'];
        $this->host       = parse_url($this->locate_url)['host'];
        $this->branch_key = isset($data['branch_key'])?$data['branch_key']:substr($this->host, 0, strpos($this->host, '.'));
        unset($data);
    }
    //初始化参数
    private function initParam($params) {
        $result = ['locate_url'=>'', 'method'=>'GET', 'branch_key'=>'','domain'=>'', 'params'=>[]];
        foreach($params as $key=>$val) {
            if(strpos($key, LOCATE_URL)===0) {
                $result['locate_url'][$key] = $val;
            } else if(strpos($key, METHOD)===0) {
                $result['method'] = strtoupper($val);
            } else if(strpos($key, BRANCH_KEY)===0) {
                $result['branch_key'] = $val;
            } else if(strpos($key, DOMAIN)===0) {
                $result['domain'] = $val;
            } else {
                $result['params'][$key] = $val;
            }
        }
        if(ctype_space($result['domain'])) 
            $this->throw_error('无选择请求域');
        if(!isset($result['locate_url'][LOCATE_URL.'_'.$result['domain']])) 
            $this->throw_error('无选择请求地址');
        $result['locate_url'] = $result['locate_url'][LOCATE_URL.'_'.$result['domain']];
        return $result;
    }

    //获取返回数据 
    public function getResponse() {
        if(array_key_exists($this->branch_key, self::$config)) {
            $method = self::$config[$this->branch_key]['token_method'];
            $this->params[self::$config[$this->branch_key]['key']] = ApiToken::$method(self::$config[$this->branch_key]['private_key'], $this->params);
        }
        $response = HttpRequest::getInstance($this->locate_url, $this->params, $this->method)->curlRequest();
        if(json_decode($response) && json_last_error()==JSON_ERROR_NONE) {
            header('Content-type: application/json'); 
        }
        return $response; 
    }

    public function getUrl() {
        if(array_key_exists($this->branch_key, self::$config)) {
            $method = self::$config[$this->branch_key]['token_method'];
            $this->params[self::$config[$this->branch_key]['key']] = ApiToken::$method(self::$config[$this->branch_key]['private_key'], $this->params);
        }
        $response = HttpRequest::getInstance($this->locate_url, $this->params, $this->method)->getUrl();
        return $response;
    }

    //异常终止
    private function throw_error($msg) {
        echo Result::setMsg($msg)->toJson();exit;
    }
}

