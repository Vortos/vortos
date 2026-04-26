<?php
declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

(new Dotenv())->bootEnv(__DIR__ . '/../.env');

return [
    'projectRoot' => __DIR__ . '/..',
    'environment' => $_ENV['APP_ENV'] ?? 'dev',
    'debug'       => ($_ENV['APP_DEBUG'] ?? 'true') === 'true',
];
