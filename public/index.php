<?php

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Di\FactoryDefault;
use Phalcon\Config\Adapter\Ini;
use Phalcon\Session\Adapter\Files as Session;
use Phalcon\Db\Adapter\Pdo\Mysql;

// Define some absolute path
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Register an autoloader
$loader = new Loader();
$loader->registerDirs([
	APP_PATH . '/controllers/',
	APP_PATH . '/models/',
]);
$loader->register();

// Create a DI
$di = new FactoryDefault();

// Setup the view component
$di->set('view', function () {
	$view = new View();
	$view->setViewsDir(APP_PATH . '/views/');
	return $view;
});

// Making the config global
$di->set('config', function () {
	return new Ini(BASE_PATH . '/configs/config.ini');
});

// starts a new session
$di->setShared('session', function () {
	$session = new Session();
	$session->start();
	return $session;
});

// connect to the database
$di->set('db', function () {
	$config = new Ini(BASE_PATH . '/configs/config.ini');
	return new Mysql((array)$config->database);
});

$application = new Application($di);

try {
	$response = $application->handle();
	$response->send();
} catch (\Exception $e) {
	echo 'Exception: ', $e->getMessage();
}

