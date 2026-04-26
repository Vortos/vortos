<?php
declare(strict_types=1);
use Vortos\Auth\DependencyInjection\VortosAuthConfig;
use Vortos\Auth\Storage\InMemoryTokenStorage;

return static function (VortosAuthConfig $config): void {
    $config
        ->secret($_ENV['JWT_SECRET'] ?? 'changeme')
        ->accessTokenTtl((int)($_ENV['JWT_ACCESS_TTL'] ?? 900))
        ->refreshTokenTtl((int)($_ENV['JWT_REFRESH_TTL'] ?? 604800))
        ->tokenStorage(InMemoryTokenStorage::class);
};
