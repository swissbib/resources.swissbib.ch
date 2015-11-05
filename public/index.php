<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

// Setup autoloading
require 'init_autoloader.php';

defined('LOCAL_OVERRIDE_DIR')
|| define('LOCAL_OVERRIDE_DIR', (getenv('RESOURCES_LOCAL_DIR') ? getenv('RESOURCES_LOCAL_DIR') : ''));


defined('RESOURCES_LOCAL_DIR')
|| define('RESOURCES_LOCAL_DIR', (getenv('RESOURCES_LOCAL_DIR') ? getenv('RESOURCES_LOCAL_DIR') : ''));



defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', dirname(__DIR__));


defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', (getenv('RESOURCES_ENV') ? getenv('RESOURCES_ENV') : 'production'));


//if ($_SERVER['APPLICATION_ENV'] == 'development') {
//    error_reporting(E_ALL);
//    ini_set("display_errors", 1);
//}



// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
