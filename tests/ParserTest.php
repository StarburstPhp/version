<?php declare(strict_types=1);

namespace Starburst\Version\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Starburst\Version\CalendarVersion;
use Starburst\Version\Extension\BuildMetaData;
use Starburst\Version\Extension\PreRelease;
use Starburst\Version\Parser;
use Starburst\Version\SemVersion;
use Starburst\Version\Version;

final class ParserTest extends TestCase
{
	public function testUnknownVersion(): void
	{
		$version = new Parser()->unknownVersion();

		$this->assertInstanceOf(SemVersion::class, $version);
		$this->assertSame('0.0.0+unknown', $version->toString());
	}

	public function testParseSemVerFromArray(): void
	{
		$version = new SemVersion(1, 2, 3, buildMetaData: BuildMetaData::fromString('aabbccd'));

		$parser = new Parser();
		$parsedVersion = $parser->parseArray($version->getArrayCopy());
		$this->assertInstanceOf(SemVersion::class, $parsedVersion);
		$this->assertSame($parsedVersion->toString(), '1.2.3+aabbccd');
	}

	public function testParseCalenderVersionFromArray(): void
	{
		$version = new CalendarVersion(
			new \DateTimeImmutable('2026-07-09'),
			PreRelease::fromString('alpha'),
			BuildMetaData::fromString('aabbccd'),
		);

		$parser = new Parser();
		$parsedVersion = $parser->parseArray($version->getArrayCopy());
		$this->assertInstanceOf(CalendarVersion::class, $parsedVersion);
		$this->assertSame($parsedVersion->toString(), '2026.07.09-alpha+aabbccd');
	}

	/**
	 * @param class-string<Version> $expectedClass
	 */
	#[DataProvider('validVersions')]
	public function testParser(string $version, string $expectedClass): void
	{
		$parser = new Parser();
		$versionObj = $parser->parseString($version);
		$this->assertInstanceOf($expectedClass, $versionObj);
		$this->assertSame($version, $versionObj->toString());
		$this->assertSame($version, (string)$versionObj);
		$this->assertSame($version, $versionObj->jsonSerialize());
	}

	/**
	 * @return array<array{0: string, 1: class-string<Version>}>
	 */
	public static function validVersions(): array
	{
		return [
			'Simple semver' => [
				'1.7.3',
				SemVersion::class,
			],
			'Semver with pre-release' => [
				'2.0.0-alpha',
				SemVersion::class,
			],
			'Semver with build meta data' => [
				'1.11.3+111',
				SemVersion::class,
			],
			'Semver with pre-release and build meta data' => [
				'3.0.0-beta.1+1.2.3',
				SemVersion::class,
			],
			'Multiple pre release identifiers' => [
				'1.0.0-alpha.beta',
				SemVersion::class,
			],
			'Multiple build release identifiers' => [
				'1.0.0+aa.bb',
				SemVersion::class,
			],
			'Simple calendar version' => [
				'2026.03.05',
				CalendarVersion::class,
			],
			'Calendar version with pre-release' => [
				'2026.03.05-2',
				CalendarVersion::class,
			],
			'Calendar version with pre-release with multiple identifiers' => [
				'2026.03.05-b.2',
				CalendarVersion::class,
			],
			'Calendar version with build meta data' => [
				'2026.03.05-2+d31d336',
				CalendarVersion::class,
			],
			'Calendar version with pre-release and build meta data with multiple identifiers' => [
				'2026.03.05-dev.2+d31d336',
				CalendarVersion::class,
			],
			'Calendar version with commit hash as pre-release' => [
				'2026.03.05-d31d336',
				CalendarVersion::class,
			],
			'Calendar version with commit hash as build meta data' => [
				'2026.03.05+d31d336',
				CalendarVersion::class,
			],
		];
	}
}
