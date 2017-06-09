<?php

/** 结果集调用入口 **/
class Result 
{
    const Error = -1;
    const Success = 1;
    private $_return_data;

    public function __construct()
    {
        $this->_return_data = new ReturnData();
    }

#    public static function __callStatic($method, $params)
#    {
#        $_return_data = new ReturnData();;
#        return call_user_func_array([$_return_data, $method], $params);
#    }
    public function __call($method, $params)
    {
        return $this->_return_data->$method($params);
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
        $attr = ($attr==='message')?'msg':$attr;
        if(in_array($attr, ['msg', 'code', 'data'])) {
            return $this->$attr;
        }
        return NULL;
    }

    private function setAttr($attr, $data)
    {
        $attr = strtolower($attr);
        if(in_array($attr, ['message', 'msg', 'code', 'data'])) {

            switch($attr) {
                case 'message':
                case 'msg':  
                    $data = (string) $data[0]; 
                    break;
                case 'code': 
                    $data = (int) $data[0]; 
                    break;
                case 'data': 
                    $data = (array) $data[0]; 
                    break;
            }
            $this->$attr = $data;   
        }
        return $this;
    }

    /**
     * 判断当前result 中 code 是成功或失败
     */
    private function isCodeError()
    {
        return ($this->code===Result::Error);
    }

    public function __call($method, $params)
    {
        if(strpos($method, 'set')==0 || strpos($method, 'get')==0 ) {
            $attr_name = strtolower(substr($method, 3));
            $method = strtolower(substr($method, 0, 3));
            $method .= 'Attr';
            return $this->$method($attr_name, $params[0]);
        }
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }

    public function toArray()
    {
        return get_object_vars($this);
    }
}
