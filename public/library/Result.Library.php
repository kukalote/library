<?php

/** 结果集调用入口 **/
class Result 
{
    const Error = -1;
    const Success = 1;

    public static function __callStatic($method, $params)
    {
        $return_data = new ReturnData();
        return call_user_func_array([$return_data, $method], $params);
    }
    public function __call($method, $params)
    {
        return 'call a method not existed';
    }
}

/** 具体处理返回结果集 **/
class ReturnData
{
    private $msg  = '';
    private $code = 0;
    private $data = [];

    public function __construct() { }
    private function getAttr($attr)
    {
        $attr = strtolower($attr);
        if(in_array($attr, ['msg', 'code', 'data']))
        {
            return $this->$attr;
        }
        return NULL;
    }

    private function setAttr($attr, $data)
    {
        $attr = strtolower($attr);
        if(in_array($attr, ['msg', 'code', 'data']))
        {
            switch($attr)
            {
                case 'msg':  $data = (string) $data[0]; break;
                case 'code': $data = (int) $data[0]; break;
                case 'data': $data = (array) $data[0]; break;
            }
            $this->$attr = $data;   
        }
        return $this;
    }

    public function __call($method, $params)
    {
        if(strpos($method, 'set')==0 || strpos($method, 'get')==0)
        {
            $attr_name = strtolower(substr($method, 3));
            $method = strtolower(substr($method, 0, 3));
            $method .= 'Attr';
            return $this->$method($attr_name, $params);
        }
    }

    public function toJson()
    {
        return json_encode(['msg'=>$this->msg, 'code'=>$this->code, 'data'=>$this->data]);
    }
}
