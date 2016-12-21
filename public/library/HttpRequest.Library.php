<?php

/* curl 操作 http 请求  */
class HttpRequest
{
    private static $_instance = NULL;
    private static $url = '';
    private static $params = [];
    private static $method = 'GET';


    private function __construct() { }

    static public function getInstance($url, $params, $method='GET') {
        self::$url = $url;
        self::$params = $params;
        self::$method = strtoupper($method);
        if(self::$_instance==NULL) {
            self::$_instance = new HttpRequest();
        }
        return self::$_instance;
    }
    public function getUrl($show_directly=false) {
        if(strpos(self::$url, '?')===false) {
            self::$url .= '?'.http_build_query(self::$params);
        } else {
            self::$url .= '&'.http_build_query(self::$params);
        }
        if($show_directly) {
            echo self::$url;
            exit;
        }
        return self::$url;
    }
    //请求操作
    public function curlRequest() {
        if(self::$method=='GET') {
            self::$url .= '?'.http_build_query(self::$params);
            //echo $url;exit;
        }
        // 创建一个新cURL资源
        $ch = curl_init();
        // 设置URL和相应的选项
        curl_setopt($ch, CURLOPT_URL, self::$url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        if(self::$method=='POST') {
            //设置post方式提交
            curl_setopt($ch, CURLOPT_POST, 1);
            if(!empty($this->params)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, self::$params);
            }
        }
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //重定向后继续访问 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        // 设置超时限制防止死循环 
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
        //执行命令
        $data = curl_exec($ch);
        if(curl_error($ch)) {
            return curl_error($ch);
        }
        // 关闭cURL资源，并且释放系统资源
        curl_close($ch);
        return $data;
    }

    public function __get($attr) {
        if(empty(self::$$attr)) return false;
        return self::$$attr;
    }
}
