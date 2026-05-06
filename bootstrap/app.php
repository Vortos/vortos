<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->overload(__DIR__ . '/../.env');

$localEnv = __DIR__ . '/../.env.local';
if (is_file($localEnv)) {
    $dotenv->overload($localEnv);
}

$env = $_ENV['APP_ENV'] ?? 'dev';
$debug = $env !== 'prod' && filter_var($_ENV['APP_DEBUG'] ?? true, FILTER_VALIDATE_BOOL);

return [
    'projectRoot' => __DIR__ . '/..',
    'environment' => $env,
    'debug' => $debug,
];
