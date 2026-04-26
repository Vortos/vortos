<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Vortos\Foundation\Runner;

$config = require __DIR__ . '/../bootstrap/app.php';
$runner = new Runner(...$config, context: 'http');
$kernel = $runner->getKernel();

$request = Symfony\Component\HttpFoundation\Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
