<?php
define( 'LIBRARY_DIR', __DIR__);
define( 'CONFIG_DIR', __DIR__.'/config');
define( 'MPATH', LIBRARY_DIR.'/models');
define( 'LPATH', LIBRARY_DIR.'/library');
define( 'CPATH', LIBRARY_DIR.'/controllers');
define( 'SPATH', LIBRARY_DIR.'/services');
#define( 'VPATH', LIBRARY_DIR.'/views');

#$load_path = loadConfigFiles('load');

$loader = new Loader();
#var_dump(spl_autoload_functions());
// 自动加载类
class Loader
{
    protected $_model_directory_path = [];
    protected $_library_directory_path = [];
    protected $_controller_directory_path = [];
#    protected $_view_directory_path = [];

    protected $_model_extension = '';
    protected $_library_extension = '';
    protected $_controller_extension = '';
    protected $_service_extension = '';
#    protected $_view_extension = '';

    public function __construct() {
        $this->_model_directory_path        = MPATH;
        $this->_library_directory_path      = LPATH;
        $this->_controller_directory_path   = CPATH;
        $this->_service_directory_path      = SPATH;
#        $this->_view_directory_path         = VPATH;

        $this->_model_extension = '.Model.php';
        $this->_library_extension = '.Library.php';
        $this->_controller_extension = '.Controller.php';
        $this->_service_extension = '.Service.php';
#        $this->_view_extension = '.php';

        spl_autoload_register([$this, 'loadModel']);
        spl_autoload_register([$this, 'loadLibrary']);
        spl_autoload_register([$this, 'loadController']);
        spl_autoload_register([$this, 'loadService']);
    }

    protected function loadModel($class_name) {
        $this->loadClassCore($class_name, $this->_model_directory_path, $this->_model_extension);
    }

    protected function loadLibrary($class_name) {
        $this->loadClassCore($class_name, $this->_library_directory_path, $this->_library_extension);
    }

    protected function loadService($class_name) {
        $this->loadClassCore($class_name, $this->_service_directory_path, $this->_service_extension);
    }

    protected function loadController($class_name) {
        $this->loadClassCore($class_name, $this->_controller_directory_path, $this->_controller_extension);
    }
    protected function loadClassCore($class_name, $path, $extension) {
        $file = $path . '/' . $class_name . $extension;
        if(ctype_alpha($class_name) && is_file($file)) {
            include_once $file;
#            set_include_path($path);
#            spl_autoload_extensions($extension);
#            spl_autoload($class_name);
        }
    }
}

function loadConfigFiles($config_file) {
    $config_file = CONFIG_DIR."/{$config_file}.php";
    if(is_file($config_file)) {
        return include_once $config_file;
    }
    return [];
}
