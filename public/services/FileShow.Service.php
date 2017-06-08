<?php

/**
 * 文件的展示服务应用
 *
 */

class FileShow
{
    private $dir_obj;
    public function __construct( $dir )
    {
        $this->setPath( $dir );
//        return $file_system;
//        if( !is_dir($log_dir) ) {
//            return Result::setMsg('目录异常')
//                ->setCode(Result::Error)
//                ->toJson();
//        }

    }

    private function setPath( $dir )
    {
        $this->dir_obj = new FileSystem( $dir );
    }

    //获取目录下文件列表
    public  function getDirList()
    {
        return $this->dir_obj->dirList();
    }
    //获取目录列表属性 
    public  function getDirAttrs()
    {
        return $this->dir_obj->dirDetail();   
    }

    public function showDir( ) 
    {
        return $this->getDirAttrs();
        //抓取目录数据

        //分析并输出
    }

}
