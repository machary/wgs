<?php
/**
 * Put errors on ON for debugging this file
 */
//ini_set('display_errors',1);
//error_reporting(E_ALL ^ E_DEPRECATED);

/*
 * Define the application environment
 */
define('APPLICATION_ENV', 'development');

/**
 * Define the absolute/relative paths to the library path, the app library path,
 * app path and the database configuration path
 */
define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
define('ZEND_LIBRARY_PATH', realpath(APPLICATION_PATH . '/../../library'));
define('APP_LIBRARY_PATH', realpath(APPLICATION_PATH . '/../library'));

$paths = array(
    ZEND_LIBRARY_PATH,
    APP_LIBRARY_PATH,
    get_include_path()
);

/**
 * Set the include paths to point to the new defined paths
 */
set_include_path(implode(PATH_SEPARATOR, $paths));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

// To allow single word class located in APP_LIBRARY_PATH
function load_class($class) {
    if (is_file(APP_LIBRARY_PATH . '/' . $class . '.php')) {
        require_once APP_LIBRARY_PATH . '/' . $class . '.php';
        return true;
    } else {
        return false;
    }
}
// spl_autoload_register('load_class');

//Start
$application
    ->bootstrap()
    ->run();