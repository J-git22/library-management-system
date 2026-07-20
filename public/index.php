<?php
/**
 * Single Entry Point
 * All requests are routed through here.
 */
session_start();

// Define absolute path to the project root
define('APPROOT', dirname(dirname(__FILE__)));

// Require the Composer autoloader
if (file_exists(APPROOT . '/vendor/autoload.php')) {
    require_once APPROOT . '/vendor/autoload.php';
} else {
    // Custom autoloader fallback
    spl_autoload_register(function ($className) {
        $prefix = 'App\\';
        $base_dir = APPROOT . '/app/';
        
        $len = strlen($prefix);
        if (strncmp($prefix, $className, $len) !== 0) {
            return;
        }
        
        $relative_class = substr($className, $len);
        $path_parts = explode('\\', $relative_class);
        $class_file = array_pop($path_parts) . '.php';
        $dir_path = strtolower(implode('/', $path_parts));
        
        $file = $base_dir . ($dir_path ? $dir_path . '/' : '') . $class_file;
        
        if (file_exists($file)) {
            require_once $file;
        }
    });
}

// Load config & helpers
require_once APPROOT . '/app/config/config.php';
require_once APPROOT . '/app/helpers/session_helper.php';

// Simple Router
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'auth/login';
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Map specific root URLs or handle default
if (empty($url[0])) {
    $controllerName = 'AuthController';
    $methodName = 'login';
} else {
    $controllerName = ucwords($url[0]) . 'Controller';
    $methodName = isset($url[1]) ? $url[1] : 'index';
}

$controllerFile = APPROOT . '/app/controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    // Controller exists, instantiate it
    $controllerClass = 'App\\Controllers\\' . $controllerName;
    $controller = new $controllerClass();
    
    // Check if method exists
    if (method_exists($controller, $methodName)) {
        // Remove controller and method from url array to pass params
        unset($url[0]);
        unset($url[1]);
        $params = $url ? array_values($url) : [];
        
        // Call Method
        call_user_func_array([$controller, $methodName], $params);
    } else {
        die("Method {$methodName} not found in {$controllerName}.");
    }
} else {
    // Controller not found
    die("Controller {$controllerName} not found.");
}
