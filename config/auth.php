<?php
declare(strict_types=1);
use Vortos\Auth\DependencyInjection\VortosAuthConfig;

return static function (VortosAuthConfig $config): void {
    $config->jwt()
        ->secret($_ENV['JWT_SECRET'] ?? 'changeme')
        ->accessTtl((int)($_ENV['JWT_ACCESS_TTL'] ?? 900))
        ->refreshTtl((int)($_ENV['JWT_REFRESH_TTL'] ?? 604800));
};
