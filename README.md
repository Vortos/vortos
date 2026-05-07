# Vortos Application Skeleton

## Installation

```bash
composer create-project vortos/vortos my-app
cd my-app
```

Composer runs the Vortos setup wizard after project creation. The wizard can configure Docker or local development and writes machine-specific values to `.env.local`.

In interactive Linux/macOS terminals, setup supports arrow-key selection. In CI, Windows, or non-interactive shells, it falls back to the standard prompt and command flags.

Run it again any time:

```bash
php vortos setup
```

## Environment Files

Commit `.env` and `.env.example` with safe shared defaults. Do not commit `.env.local`, `.env.*.local`, `.vortos-setup.json`, or `*.bak.*`.

`php vortos setup` writes generated secrets, service URLs, and local driver choices to `.env.local`. Edit `.env.local` only for your own machine-specific overrides.

Docker development files load `.env` first and `.env.local` second. Keep generated Docker passwords in `.env.local`; do not copy them into `.env`.

Setup does not rewrite `config/*.php`. Config files stay stable as override points. Framework modules read `VORTOS_*` env defaults from `.env.local`; if no env driver is selected, cache and messaging use in-memory drivers.

Framework modules read agnostic env names such as `VORTOS_WRITE_DB_DSN`, `VORTOS_READ_DB_DSN`, `VORTOS_CACHE_DSN`, and `VORTOS_MESSAGING_DSN`. Docker images may still need vendor-specific env such as `POSTGRES_PASSWORD`, but those are only for service bootstrap.

## Setup Profiles

Use profiles for the common paths:

```bash
php vortos setup --profile=docker
php vortos setup --profile=minimal
```

Interactive setup asks for a profile first, then shows a review step with `Continue`, `Customize`, or `Cancel`.

Choose `Customize` when you want to choose each setup category before files are written:

- runtime
- write database
- read database
- cache
- messaging

Some categories have only one supported option today. They still appear in the custom flow so future package choices can fit into the same setup path without changing the command shape.

Setup choices come from registered capabilities. Vortos modules can add options by registering a `SetupCapabilityInterface` service tagged with `vortos.setup_capability`.

Capability keys use `category.option`, for example:

- `write_db.mysql`
- `read_db.postgres`
- `cache.memcached`
- `messaging.rabbitmq`

The capability makes the option appear in setup. The module still provides the real services, config defaults, environment mapping, Docker files, migrations, and tests behind that option.

## Runtime Presets

Use presets when you want a specific runtime:

```bash
php vortos setup --preset=docker-frankenphp
php vortos setup --preset=docker-phpfpm
php vortos setup --preset=local
php vortos setup --preset=minimal
```

Use `--dry-run` to preview generated environment and Docker changes without writing files:

```bash
php vortos setup --profile=docker --dry-run --no-interaction
```

## Docker Development

For Docker presets, setup publishes the selected runtime files. Start the environment with:

```bash
php vortos up
```

## Local Development

For local presets, setup configures in-memory cache and messaging by default:

```bash
php vortos setup --profile=minimal
php -S 127.0.0.1:8000 -t public
```

## Useful Commands

```bash
php vortos list
php vortos test
php vortos migrate:publish
php vortos migrate
```
