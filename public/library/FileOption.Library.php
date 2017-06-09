<?php

/**
 * 对文件的操作
 *
 */

class FileOption extends FileCommon
{
    const PLAIN = 'plain';
    const OTHER = 'other';

    private static $_file_info_config = ['class', 'size', 'ext', 'file_name', 'path'];
    private static $_text_plain  = ['text/plain'];
    private static $_other       = ['inode/x-empty', 'application/x-gzip'];
//    private $_path;
    private $_file_info = array();

    public function __construct( $path )
    {
        parent::__construct( $path );
    }

    /**
     * @type tool
     */
    public function setFileInfo( $attrs=array() )
    {
        $attrs = empty($attrs)?:self::$_file_info_config;

        in_array( 'file_name', $attrs ) && ($this->_file_info['file_name'] = basename($this->getPath()));
        in_array( 'class', $attrs ) && ($this->_file_info['class'] = $this->getClass());
        in_array( 'size', $attrs ) && ($this->_file_info['size'] = $this->getSize());
        in_array( 'ext', $attrs ) && ($this->_file_info['ext'] = $this->getExtension());
    }

    /**
     * @type result
     */
    public function getFileInfo( $attrs=array() )
    {
        $file_info = array();
        if( is_string($attrs) ) {
            $this->setFileInfo( $attrs );
            $file_info = $this->_file_info[ $attrs ];
        } else if( is_array( $attrs ) ) {
            $exist_attrs = array_keys( $this->_file_info );       
            $attrs = !empty($attrs) ? $attrs : self::$_file_info_config;
            $without_attrs = array_diff( $attrs, $exist_attrs );
            if( !empty($without_attrs) ){
                if( array_diff( $without_attrs, self::$_file_info_config ) ) {
                    return '获取属性异常';
                }
                $this->setFileInfo( $without_attrs );
            }
            $attrs = array_flip( $attrs );
            $file_info = array_intersect_key( $attrs, $this->_file_info );
        }
        return $file_info;
    }



    public function getExtension()
    {
        return mime_content_type( $this->getPath() );
    }

    /**
     * @type result
     */
    private function getSize()
    {
        return self::convertUnit( filesize( $this->getPath() ) );
    }

    /**
     * '@type result
     */
    public static function convertUnit( $byte_number )
    {
        $byte_number = intval($byte_number);
        $unit = ['B', 'K', 'M', 'G', 'T'];
        $cyle = 0;
        while( $byte_number > 1024 ){
            $byte_number /= 1024;
            $cyle++;
        }

        if( $cyle > 4 ) return false;

        $byte_number = ceil($byte_number*100)/100;
        return "{$byte_number}{$unit[$cyle]}";
    }

    /**
     *' @type result
     * 返回当前文件读取方式
     */
    public function getClass()
    {
        $class = '';
        $ext = isset($this->_file_info['ext'])?($this->_file_info['ext']):($this->getExtension( $this->getPath() ));
        if( in_array( $ext, self::$_text_plain ) ) {
            $class = self::PLAIN;
        } else if( in_array( $ext, self::$_other ) ) {
            $class = self::OTHER;
        }
        return $class;
    }

    /**
     *' @type result
     * 读取文本文件
     */
    public function  readPlain( $start_line=0, $length=10)
    {
        if( !$this->isReadable() ) {
            return '文件不可读';
        }
        //读取文件内容 
        $content = '';
        $fd = fopen( $this->getPath(), 'r' );
        ( !feof($fd) ) && fgets( $fd, (($start_line-1)*1024) );
        ( !feof($fd) ) && $content .= fgets( $fd, ($length*1024) );
        fclose($fd);

        return $content;
    }



}
