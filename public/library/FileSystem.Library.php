<?php
/** 接口验证规则 **/
class FileSystem 
{
    private $path;
    public function __construct($path){
        $this->path = realpath($path);
        return $this;
    }

    /**
     * 输出路径下所有文件列表
     * @param   $recurseive_dir  string  循环目录地址
     */
    public static function readRecursiveDir( $recurseive_dir )
    {
        $result_dir = array();
        if( $dir_handle = opendir($recurseive_dir) ) {
            while( false !== ($entity = readdir($dir_handle)) ) {
                if( $entity==='.' || $entity==='..' ) continue;
                $entity =  "{$recurseive_dir}/{$entity}";

                if( is_dir($entity) ) {
                    readRecursiveDir( $entity );
                    continue;
                }
                array_push( $result_dir, $entity );
#               echo "{$entity}\n";
            }
            closedir($dir_handle);
        }
        return $result_dir;
    }

    public function getPath() {
        return $this->path;
    }

    public function changePath($new_path) {
        if(strpos($new_path, '/')===false) {
            $new_path = realpath($this->path.'/'.$new_path);
        } else {
            $new_path = realpath($new_path);
        }
        if(!$new_path) {
            return false;
        }
        $this->path = $new_path;
        return $this;
    }

    public function upDir() {
        $this->path = dirname($this->path);
        return $this;
    }

    public function isReadable($file_or_dir=''){
        $file_or_dir = $this->localPath($file_or_dir);
        return is_readable($file_or_dir); 
    }

    public function isWriteable($file_or_dir=''){
        $file_or_dir = $this->localPath($file_or_dir);
        return is_writable($file_or_dir); 
    }

    public function hasFile($file_name=''){
        return $this->isFile($file_name); 
    }


    protected function localPath($path) {
        return $path = ($path=='')?$this->path:($this->path.'/'.$path);
    }

    public function removeFile($file_name='') {
        $file_name = $this->localPath($file_name);
        if($this->isFile($file_name)) {
            return unlink($file_name);
        }
        return false;
    }

    public function removeDir($dir_name='') {
        $dir_name = $this->localPath($dir_name);
        if ($this->isDir($dir_name) ) {
            return rmdir($dir_name);
        }
        return false;
    }

    public function mkDir($dir_name) {
        if ($this->isDir()) {
            return mkdir($this->path.'/'.$dir_name);
        }
        return false;
    }

    public function mkFile($file_name) {
        if ($this->isDir()) {
            return touch($this->path.'/'.$file_name);
        }
        return false;
    }

    public function isDir($dir_name='') {
        $dir_name = $this->localPath($dir_name);
        return is_dir($dir_name);
    }

    public function isFile($file_name='') {
        $file_name = $this->localPath($file_name);
        return is_file($file_name);
    }

    public function fileDetail($file_name='') {
        if($this->isFile($file_name)) {
            $file_name = $this->localPath($file_name);
            return pathinfo($file_name);
        }
        return false;
    }

    /**
     * @type result
     *
     */
    public function dirList() {
        if($this->isDir()) {
            $list = array();
            $handle = opendir($this->path);
            while (false !== ($file = readdir($handle)) ) {
                if($file == '.' || $file == '..') continue;
                $list[] = $this->localPath( $file );
            }
            closedir($handle);
            sort($list);
            $result = Result::setData($list)->setCode(Result::Success);
        } else {
            $result = Result::setMsg('目录异常')->setCode(Result::Error);
        }
        return $result;
    }

    /**
     * @type result
     *
     */
    public function dirDetail( $attrs = ['size','type', 'ext'] ) 
    {
        if($this->isDir()) {
            $list = array();
            $handle = opendir($this->path);
            while (false !== ($file = readdir($handle)) ) {
                if($file == '.' || $file == '..') continue;
                $full_name = $this->localPath( $file );
                $file_info = self::fileInfo( $full_name, $attrs );
                $list[] = array_merge(['full_name'=>$full_name, 'file_name'=>$file], $file_info);
            }
            closedir($handle);
            sort($list);
            $result = Result::setData($list)->setCode(Result::Success);
        } else {
            $result = Result::setMsg('目录异常')->setCode(Result::Error);
        }
        return $result;
    }

    /**
     * @type tool
     */
    public static function fileInfo( $file_name, $attrs=['type', 'size', 'ext'] )
    {
        $result = array();
        $type = is_file($file_name)?'file':(is_dir($file_name)?'dir':'other');
        in_array( 'type', $attrs ) && ($result['type'] = $type);
        if( $type==='file' ) {
            in_array( 'size', $attrs ) && ($result['size'] = self::fileSize( $file_name ));
            in_array( 'ext', $attrs ) && ($result['ext'] = self::fileExtension( $file_name ));
        }
        return $result;
    }

    /**
     * @type tool
     */
    private static function fileSize( $file_name )
    {
        return self::convertUnit(filesize($file_name));
    }

    /**
     * @type tool
     */
    private static function fileExtension( $file_name )
    {
        return mime_content_type($file_name);
    }

    /**
     * @type tool
     */
    public static function convertUnit( int $byte_number )
    {
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


}
