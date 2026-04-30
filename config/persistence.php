<?php

declare(strict_types=1);

use Vortos\Persistence\DependencyInjection\VortosPersistenceConfig;

return static function (VortosPersistenceConfig $config): void {
    $config
        ->writeDsn($_ENV['DATABASE_URL'] ?? sprintf(
            'pgsql://%s:%s@%s:5432/%s',
            $_ENV['POSTGRES_USER'] ?? 'postgres',
            $_ENV['POSTGRES_PASSWORD'] ?? 'postgres',
            $_ENV['POSTGRES_HOST'] ?? 'write_db',
            $_ENV['POSTGRES_DB_NAME'] ?? 'app',
        ))
        ->readDsn(sprintf(
            'mongodb://%s:%s@%s:%s',
            $_ENV['MONGO_INITDB_ROOT_USERNAME'] ?? 'root',
            $_ENV['MONGO_INITDB_ROOT_PASSWORD'] ?? 'root',
            $_ENV['MONGO_HOST'] ?? 'read_db',
            $_ENV['MONGO_PORT'] ?? '27017',
        ))
        ->readDatabase($_ENV['MONGO_DB_NAME'] ?? 'app');
};
