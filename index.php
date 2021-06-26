<?php

use Limber\Application;
use Capsule\Factory\ServerRequestFactory;
use Limber\Exceptions\NotFoundHttpException;

session_start();

/**
 * Application name.
 * 
 * @var string
 */
define('MPG_APP_NAME', 'MongoDB PHP GUI');

/**
 * Application version.
 * 
 * @var string
 */
define('MPG_APP_VERSION', '1.1.2');

/**
 * Development mode?
 * 
 * @var string
 */
define('MPG_DEV_MODE', false);

/**
 * Absolute path. XXX Without trailing slash.
 * 
 * @var string
 */
define('MPG_ABS_PATH', __DIR__);

if (getenv("HTTP_HOST") !== false) {
  $_SERVER['HTTP_HOST'] = getenv("HTTP_HOST");
}

$baseUrl = '//' . $_SERVER['HTTP_HOST'];
$serverPath = str_replace('\\', '/', dirname($_SERVER['REQUEST_URI']));
$serverPath = ( $serverPath === '/' ) ? '' : $serverPath;
$baseUrl .= $serverPath;

/**
 * Server path. XXX Without trailing slash.
 * 
 * @var string
 */
define('MPG_SERVER_PATH', $serverPath);

/**
 * Base URL. XXX Without trailing slash.
 * 
 * @var string
 */
define('MPG_BASE_URL', $baseUrl);

require __DIR__ . '/autoload.php';
require __DIR__ . '/routes.php';

$application = new Application($router);
$serverRequest = ServerRequestFactory::createFromGlobals();

// XXX This hack makes index to work in sub-folder case.
try {
    $response = $application->dispatch($serverRequest);
} catch (NotFoundHttpException $e) {
    header('Location: ' . rtrim($_SERVER['REQUEST_URI'], '/') . '/index');
}

$application->send($response);
