# Vortos Application Skeleton

## Installation

```bash
composer create-project vortos/vortos my-app
cd my-app
```

## Docker Setup (Optional)

```bash
composer require vortos/vortos-docker --dev

# Add to config/services.php:
# $services->load('Vortos\\Docker\\', '../vendor/vortos/vortos-docker/src/');

# Then publish Docker files:
php bin/console vortos:docker:publish

# Or for php-fpm:
php bin/console vortos:docker:publish --runtime=phpfpm
```
