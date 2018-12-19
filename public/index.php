<?php

use Zend\Mvc\Application;
use Zend\Stdlib\ArrayUtils;

// Set server time zone
date_default_timezone_set('America/Chicago');
// Remove index.php from the URL
$url = $_SERVER["REQUEST_URI"];
if(strpos($url,"index.php")!==false) {
    $url = str_replace("index.php","",$url);
    header("Location: $url");
    exit;
}
// ======================== Error reporting =============================== //
/**
 * Display all errors when APPLICATION_ENV is development.
 *
 * http://stackoverflow.com/questions/10256326/best-practice-for-environment-variable-management
 *
 * "I have experienced the same problem as you.
 * However, it is not as big as it seems. Once index.php is set up it never changes,
 * so it is safe to leave it out of version control.
 * Hard code the the definition of APPLICATION_ENV on each of your dev,
 * staging and production servers and forget about it."
 *
 */
define('APPLICATION_ENV', getenv('APP_ENV') ? getenv('APP_ENV') : 'production');
/**
 * Display all errors when APPLICATION_ENV is development.
 */
if ( APPLICATION_ENV==='development') {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}
// ======================== Error reporting =============================== //

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (__FILE__ !== $path && is_file($path)) {
        return false;
    }
    unset($path);
}

// Composer autoloading
include __DIR__ . '/../vendor/autoload.php';

if (! class_exists(Application::class)) {
    throw new RuntimeException(
        "Unable to load application.\n"
        . "- Type `composer install` if you are developing locally.\n"
        . "- Type `vagrant ssh -c 'composer install'` if you are using Vagrant.\n"
        . "- Type `docker-compose run zf composer install` if you are using Docker.\n"
    );
}

// Retrieve configuration
$appConfig = require __DIR__ . '/../config/application.config.php';
if (file_exists(__DIR__ . '/../config/development.config.php')) {
    $appConfig = ArrayUtils::merge($appConfig, require __DIR__ . '/../config/development.config.php');
}

// Run the application!
Application::init($appConfig)->run();
