<?php
/**
 * 文件系统操作
 */
class FileSystem
{
    private $_path;

    public function __construct($path){
        $this->_path = realpath($path);
        #整理当前路径
        return $this;
    }

    public function getPathType()
    {
#        return 
    }

    /** '
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
                    self::readRecursiveDir( $entity );
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
        return $this->_path;
    }


    /** '
     *
     */
    protected function localPath( $path ) 
    {
        return ($_path=='')?$this->_path:($this->_path.'/'.$path);
    }


    public function isDir($dir_name='') {
        $dir_name = $this->localPath($dir_name);
        return is_dir($dir_name);
    }


    /** '
     * @type result
     *
     */
    public function dirList() {
        $result = NULL;
        if($this->isDir()) {
            $list = array();
            $handle = opendir($this->_path);
            while (false !== ($file = readdir($handle)) ) {
                if($file == '.' || $file == '..') continue;
                $list[] = $this->localPath( $file );
            }
            closedir($handle);
            sort($list);
            $result = $list;
        }
        return $result;
    }

    /**
     * @type result
     *
     */
    public function dirDetail( $attrs = ['size','type', 'ext'] ) 
    {
        $result = NULL;
        if($this->isDir()) {
            $list = array();
            $handle = opendir($this->_path);
            while (false !== ($file = readdir($handle)) ) {
                if($file == '.' || $file == '..') continue;
                $full_name = $this->localPath( $file );

                if( is_dir($full_name) ) {
                
                } else {
                    $file_obj = new FileOption( $full_name );
                    $list[] = $file_obj;
                }
            }
            closedir($handle);
            sort($list);
            $result = $list;
        } 
        return $result;
    }






}
