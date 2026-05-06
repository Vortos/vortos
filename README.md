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

## Setup Profiles

Use profiles for the common paths:

```bash
php vortos setup --profile=docker
php vortos setup --profile=minimal
```

Interactive setup asks for a profile first, then shows a review step with `Continue`, `Customize`, or `Cancel`. Choose `Customize` when you want to switch to a specific runtime preset before files are written.

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
