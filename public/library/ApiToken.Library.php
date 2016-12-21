<?php 
/** 接口验证规则 **/
class ApiToken
{
    private function __construct(){}
    //zebra 类 token生成方法
    public static function makeToken($private_key, $params) {
        //为空不进行验证 
        if(empty($private_key)) {
            echo Result::setMsg('密钥不得为空')->toJson();exit;
        }
        ksort($params);
        $str = urldecode(http_build_query($params, '', '&',2) ). $private_key;
        $str = md5($str);
        return $str;
    }

    //newapi 类 sn 生成方法
    public static function makeSn($private_key, $params) {
        //var_dump($private_key, $params);exit;
        //为空不进行验证 
        if(empty($private_key)) {
            echo Result::setMsg('密钥不得为空')->toJson();exit;
        }
        ksort($params);
        $str = http_build_query($params, '', '&',2) . $private_key;
        $str = urldecode($str);
        $str = md5($str);
        $str = $str{20} . $str{15} . $str{0} . $str{3} . $str{1} . $str{5};
        return $str;
    }
}
