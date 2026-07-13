# Starburst Version

[![Build Status](https://github.com/StarburstPhp/version/actions/workflows/continuous-integration.yml/badge.svg?branch=main)](https://github.com/StarburstPhp/version/actions/workflows/continuous-integration.yml)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/starburst/version.svg)](https://packagist.org/packages/starburst/version)
[![Software License](https://img.shields.io/github/license/StarburstPhp/version.svg)](LICENSE.md)

A PHP library for parsing, comparing, and bumping versions, supporting both Semantic Versioning (SemVer) and Calendar Versioning (CalVer).

## Features

- Parse version strings and arrays.
- Support for [Semantic Versioning 2.0.0](https://semver.org/).
- Support for Calendar Versioning (`YYYY.MM.DD`).
- Comprehensive version comparison (greater than, less than, equal to, etc.).
- Support for Pre-release and Build metadata.
- Version bumping for both SemVer and CalVer.
- JSON serializable version objects.

## Requirements

- PHP 8.4 or higher.

## Installation

You can install the package via Composer:

```bash
composer require starburst/version
```

## Usage

### Parsing Versions

Use the `Parser` class to create version objects from strings or arrays.

```php
use Starburst\Version\Parser;

$parser = new Parser();

// Parse Semantic Versioning
$version = $parser->parseString('1.2.3-alpha.1+build.123');

// Parse Calendar Versioning
$calVer = $parser->parseString('2024.05.20');

// Parse from array
$versionFromArray = $parser->parseArray([
    'major' => 1,
    'minor' => 2,
    'patch' => 3,
]);
```

### Comparing Versions

Version objects provide several methods for comparison.

```php
$v1 = $parser->parseString('1.2.3');
$v2 = $parser->parseString('1.2.4');

$v1->isLessThan($v2);         // true
$v1->isGreaterThan($v2);      // false
$v1->isEqualTo('1.2.3');      // true
$v1->compareTo($v2);          // -1
```

### Bumping Versions

You can bump versions using the `Bump` enum.

```php
use Starburst\Version\Bump;

$version = $parser->parseString('1.2.3');
$nextVersion = $version->bump(Bump::Minor); // 1.3.0

// with pre-release and build metadata
$nextVersion = $version->bump(
	Bump::Path,
	preRelease: PreRelease::from('alpha', '1'),
	buildMetaData: BuildMetaData::from('f3b2974'),
); // Today's date (e.g., 1.2.4-alpha.1+f3b2974)
```

For `CalendarVersion`, bumping typically uses the current date:

```php
$version = $parser->parseString('2024.01.01');
$nextVersion = $version->bump(); // Today's date (e.g., 2026.07.13)

// or specify a specific date
$nextVersion = $version->bump(releaseData: new DateTimeImmutable('2024-01-01')); // 2024.01.01

// with pre-release and build metadata
$nextVersion = $version->bump(
	preRelease: PreRelease::from('alpha', '1'),
	buildMetaData: BuildMetaData::from('f3b2974'),
); // Today's date (e.g., 2026.07.13-alpha.1+f3b2974)
```

## Scripts & Development

The project uses the following tools for development:

- **Tests**: `vendor/bin/phpunit`
- **Static Analysis**: `vendor/bin/phpstan analyse`
- **Coding Style**: `vendor/bin/phpcs`

## License

This project is licensed under the MIT License. See [LICENSE.md](LICENSE.md) for details.
