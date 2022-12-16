<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Config\Environment\Production\ConfigurationProduction;
use Symfony\Component\HttpFoundation\Request;
use TheFeed\TheFeed;

$app = new TheFeed();
$container = $app->initializeApplication(ConfigurationProduction::class);
$request = Request::createFromGlobals();
$framework = $container->get('framework');
$response = $framework->handle($request);
$response->send();