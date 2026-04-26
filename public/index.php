<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Vortos\Foundation\Runner;

$config = require __DIR__ . '/../bootstrap/app.php';

if (isset($_SERVER['FRANKENPHP_WORKER'])) {
    $runner = new Runner(...$config, context: 'http');
    $runner->getContainer();
    while (frankenphp_handle_request(function () use ($runner): void {
        header('X-Vortos-Mode: Worker-Active');
        $response = $runner->run();
        $response->send();
        $runner->cleanUp();
    })) {}
} else {
    $runner = new Runner(...$config, context: 'http');
    $response = $runner->run();
    $response->send();
    $runner->cleanUp();
}
