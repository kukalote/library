<?php
/** 接口验证规则 **/
class FileSystem 
{
    private $path;
    public function __construct($path){
        $this->path = realpath($path);
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

    public function hasDir($dir_name=''){
        return $this->isDir($dir_name); 
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

    public function dirList($dir_name='') {
        if($this->isDir($dir_name)) {
            $list = array();
            $dir_name = $this->localPath($dir_name);
            $handle = opendir($dir_name);
            while (false !== ($file = readdir($handle)) ) {
                if($file == '.' || $file == '..') continue;
                $list[] = $file;
            }
            closedir($handle);
            sort($list);
            return $list;
        }
        return false;
    }

    public function dirDetail($dir_name='', $config = ['size','type']) {
        if($this->isDir($dir_name)) {
            $list = array();
            $dir_name = $this->localPath($dir_name);
            $handle = opendir($this->path);
            while (false !== ($file = readdir($handle)) ) {
                if($file == '.' || $file == '..') continue;
                $type = is_file($file)?'file':(is_dir($file)?'dir':'other');
                $size = filesize($file);
                $list[] = ['file_name'=>$file, 'type'=>$type, 'size'=>$size];
            }
            closedir($handle);
            sort($list);
            return $list;
        }
        return false;
    }


}
