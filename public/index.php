<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Fortizan\Tekton\Foundation\Runner;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\Debug;

$projectRoot = __DIR__ . "/..";

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');

$env = $_ENV['APP_ENV'] ?? 'prod';
$debug = false;

$debugBool = filter_var($_ENV['APP_DEBUG'], FILTER_VALIDATE_BOOL);

if ($env === 'dev' && $debugBool) {
    Debug::enable();
    $debug = true;
}

$runner = new Runner($env, $debug, $projectRoot);
$response = $runner->run();
$response->send();
$runner->cleanUp();
