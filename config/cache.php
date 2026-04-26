<?php
declare(strict_types=1);
use Vortos\Cache\DependencyInjection\VortosCacheConfig;
use Vortos\Cache\Adapter\ArrayAdapter;
use Vortos\Cache\Adapter\RedisAdapter;

return static function (VortosCacheConfig $config): void {
    $driver = extension_loaded('redis') ? RedisAdapter::class : ArrayAdapter::class;

    $config->driver($driver)
        ->dsn(sprintf('redis://%s:%s', $_ENV['REDIS_HOST'] ?? '127.0.0.1', $_ENV['REDIS_PORT'] ?? '6379'))
        ->prefix(($_ENV['APP_ENV'] ?? 'dev') . '_app_')
        ->defaultTtl(3600);
};
