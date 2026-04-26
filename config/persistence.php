<?php
declare(strict_types=1);
use Vortos\Persistence\DependencyInjection\VortosPersistenceConfig;

return static function (VortosPersistenceConfig $config): void {
    $config
        ->writeDsn($_ENV['DB_DSN'] ?? '')
        ->readDsn($_ENV['MONGO_URI'] ?? '')
        ->readDatabase($_ENV['MONGO_DB'] ?? '');
};
