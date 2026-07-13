<?php declare(strict_types=1);

namespace Starburst\Version\Tests\Comparison;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Starburst\Version\Comparison\CalendarVersionComparator;
use Starburst\Version\Comparison\Comparator;
use Starburst\Version\Parser;

final class CalendarVersionComparatorTest extends TestCase
{
	private Comparator $comparator;

	protected function setUp(): void
	{
		$this->comparator = new CalendarVersionComparator();
	}

	#[DataProvider('expectedComparisonResults')]
	public function testComparesTwoVersions(string $version1String, string $version2String, int $expectedResult): void
	{
		$parser = new Parser();
		$result = $this->comparator->compare(
			$parser->parseString($version1String),
			$parser->parseString($version2String),
		);

		$this->assertSame($expectedResult, $result);
	}

	/**
	 * @return array<string, array{0: string, 1: string, 2: int}>
	 */
	public static function expectedComparisonResults(): array
	{
		return [
			'major' => ['2025.12.31', '2026.07.13', -1],
			'minor' => ['2026.06.25', '2026.07.26', -1],
			'patch' => ['2026.07.10', '2026.07.09', 1],
			'same' => ['2026.07.13', '2026.07.13', 0],
			'regular vs pre-release' => ['2026.07.13', '2026.07.13-alpha', 1],
			'pre-release alphabetical comparison' => [
				'2026.07.13-alpha',
				'2026.07.13-beta',
				-1,
			],
			'pre-release alphabetical identifiers compared in order' => [
				'2026.07.13-alpha.beta',
				'2026.07.13-beta',
				-1,
			],
			'pre-release numerical identifiers compared in order' => [
				'2026.07.13-3.alpha',
				'2026.07.13-1.beta',
				1,
			],
			'longer pre-release is greater if identifiers are the same' => [
				'2026.07.13-alpha.1',
				'2026.07.13-alpha',
				1,
			],
			'multi identifier pre-release alphabetical comparison' => [
				'2026.07.13-alpha.beta',
				'2026.07.13-alpha.1',
				1,
			],
			'numeric pre-release-identifiers' => ['2026.07.13-beta.11', '2026.07.13-beta.2', 1],
			'rc vs beta' => ['2026.07.13-rc.1', '2026.07.13-beta.11', 1],
			'build part ignored' => ['2026.07.13-alpha+20150919', '2026.07.13-alpha+exp.sha.5114f85', 0],
			'alphanumeric pre-releases' => ['2026.07.13-b1', '2026.07.13-a', 1],
			'incompatible versions' => ['2026.07.10', '2.0.0', -1],
			'incompatible versions 2' => ['2.0.0', '2026.07.10', -1],
		];
	}
}
