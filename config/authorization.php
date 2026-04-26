<?php
declare(strict_types=1);
use Vortos\Authorization\DependencyInjection\VortosAuthorizationConfig;

return static function (VortosAuthorizationConfig $config): void {
    $config->roleHierarchy([
        'ROLE_ADMIN' => ['ROLE_USER'],
    ]);
};
