<?php

/**
 * 文件的展示服务应用
 *
 */

class FileShow
{
    private $_fd_system;
    public function __construct( $path )
    {
        $this->initPath( $path );
    }

    private function initPath( $path )
    {
        $this->_fd_system = new FileSystem( $path );
        var_dump($this->_fd_system);exit;
    }

    //获取目录下文件列表
    public  function getDirList()
    {
        return $this->_fd_system->dirList();
    }
    //获取目录列表属性 
    public  function getDirAttrs()
    {
        return $this->_fd_system->dirDetail();   
    }

    public function showDir( ) 
    {
        $result = new Result();
        $list = $this->getDirAttrs();
        if( $list ) {
            $result->setData( $list )->setCode( Result::Success );
        } else {
            $result->setMsg( '目录为空' )->setCode( Result::Success );
        }
        return $result;
    }

    public function isEnable() 
    {
        return (bool) $this->_fd_system->getPath();
    }

    public function readFile()
    {
        $class = $this->_fd_system->getClass();

        var_dump($this->_fd_system);exit;
//        $file_option = new FileOption($this->_fd_system->);
//        if( $class == FileSystem::PLAIN ) {
//            $content = $file_option->readPlain();
//        } else if( $class == FileSystem::OTHER ) {
//            $content = $this->_fd_system->readOther();
//        }
        return $content;
    }

}
