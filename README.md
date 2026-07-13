# Starburst Version

A PHP library for parsing and comparing versions, supporting both Semantic Versioning (SemVer) and Calendar Versioning (CalVer).

## Features

- Parse version strings and arrays.
- Support for [Semantic Versioning 2.0.0](https://semver.org/).
- Support for Calendar Versioning (`YYYY.MM.DD`).
- Comprehensive version comparison (greater than, less than, equal to, etc.).
- Pre-release and Build metadata support.
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

You can use the `Parser` class to create version objects from strings or arrays.

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

### Calendar Versioning

The library supports calendar-based versions, which are automatically detected if the first part is a 4-digit year.

```php
$v = $parser->parseString('2024.01.01');
echo $v->toString(); // 2024.01.01
```

## Development

### Running Tests

```bash
vendor/bin/phpunit
```

### Static Analysis

```bash
vendor/bin/phpstan analyse
```

### Coding Style Check

```bash
vendor/bin/phpcs
```

## License

This project is licensed under the MIT License. See [LICENSE.md](LICENSE.md) for details.
