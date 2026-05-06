# Vortos Application Skeleton

## Installation

```bash
composer create-project vortos/vortos my-app
cd my-app
```

Composer runs the Vortos setup wizard after project creation. The wizard can configure Docker or local development and writes machine-specific values to `.env.local`.

Run it again any time:

```bash
php vortos setup
```

## Presets

```bash
php vortos setup --preset=docker-frankenphp
php vortos setup --preset=docker-phpfpm
php vortos setup --preset=local
php vortos setup --preset=minimal
```

Use `--dry-run` to preview generated environment and Docker changes without writing files:

```bash
php vortos setup --preset=docker-frankenphp --dry-run --no-interaction
```

## Docker Development

For Docker presets, setup publishes the selected runtime files. Start the environment with:

```bash
php vortos up
```

## Local Development

For local presets, setup configures in-memory cache and messaging by default:

```bash
php vortos setup --preset=local
php -S 127.0.0.1:8000 -t public
```

## Useful Commands

```bash
php vortos list
php vortos test
php vortos migrate:publish
php vortos migrate
```
