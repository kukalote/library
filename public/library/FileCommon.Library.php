<?php

class FileCommon 
{
    protected $_fd_obj;
    protected $_path;

    public function __construct( $path )
    {
        $this->_path = realpath( $path );
    }

    public function getPath()
    {
        return $this->_path;
    }
    public function isFile()
    {
        return is_file( $this->getPath() );
    }

    public function isDir()
    {
        return is_dir( $this->getPath() );
    }

    public function getBasename()
    {
        return basename($this->getPath());
    }

    public function isReadable()
    {
        return is_readable( $this->getPath() );
    }


}
