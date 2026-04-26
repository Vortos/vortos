<?php
declare(strict_types=1);
use Vortos\Cache\DependencyInjection\VortosCacheConfig;

return static function (VortosCacheConfig $config): void {
    $config->dsn(sprintf('redis://%s:%s', $_ENV['REDIS_HOST'] ?? '127.0.0.1', $_ENV['REDIS_PORT'] ?? '6379'))
        ->prefix(($_ENV['APP_ENV'] ?? 'dev') . '_app_')
        ->defaultTtl(3600);
};
