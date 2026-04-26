<?php
declare(strict_types=1);
use Vortos\Cache\DependencyInjection\VortosCacheConfig;

return static function (VortosCacheConfig $config): void {
    $config->driver('redis')
        ->host($_ENV['REDIS_HOST'] ?? '127.0.0.1')
        ->port((int)($_ENV['REDIS_PORT'] ?? 6379));
};
