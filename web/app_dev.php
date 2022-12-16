<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Config\Environment\Development\ConfigurationDevelopment;
use Symfony\Component\HttpFoundation\Request;
use TheFeed\TheFeed;

if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !(in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1','::1'], true) || PHP_SAPI === 'cli-server')
) {
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

$app = new TheFeed();
$container = $app->initializeApplication(ConfigurationDevelopment::class);
$request = Request::createFromGlobals();
$framework = $container->get('framework');
$response = $framework->handle($request);
$response->send();