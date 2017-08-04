<?php

use Search\Controller\Controller as SearchController;
use Search\Logger\NullLogger as Logger;
use Search\Service\GoogleClientService as SearchService;

// You can use Yandex search instead Google
//use Search\Service\YandexClientService as SearchService;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'On');

// autoload
spl_autoload_register(function ($className) {
	include '../src/' . str_replace('\\', '/', $className) . '.php';
});

$conf = include '../conf.php';

$searchService = new SearchService($conf);
$logger = new Logger($conf);
$controller = new SearchController($searchService, $logger, $conf);

if (!array_key_exists($key = 'action', $_REQUEST)
	|| !is_string($action = $_REQUEST[$key])
	|| !is_callable([$controller, $method = $action.'Action'])) {
	$method = 'mainAction';
}
$controller->$method($_REQUEST);
