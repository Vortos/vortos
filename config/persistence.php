<?php
declare(strict_types=1);
use Vortos\Persistence\DependencyInjection\VortosPersistenceConfig;

return static function (VortosPersistenceConfig $config): void {
    $config->write()->dsn($_ENV['DB_DSN'] ?? '');
    $config->read()->dsn($_ENV['MONGO_URI'] ?? '')->database($_ENV['MONGO_DB'] ?? '');
};
