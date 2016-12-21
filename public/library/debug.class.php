<?php

/***
  * 拥有便捷输出的功能
  * 拥有日志记录的功能
  *
  *
  *
  *
  */
class debug
{
    public static function vardump($data)
    {
        var_dump($data);
        exit;
    }

    public static function varexport($data)
    {
        var_export($data);
        exit;
    }

    public static function varjson($data)
    {
        $json = json_encode($data);
        var_dump($json);
        exit;
    }

}
