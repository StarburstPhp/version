<?php declare(strict_types=1);

namespace Starburst\Version\Tests\Comparison;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Starburst\Version\Comparison\Comparator;
use Starburst\Version\Comparison\SemVersionComparator;
use Starburst\Version\Parser;

final class SemVersionComparatorTest extends TestCase
{
	private Comparator $comparator;

	protected function setUp(): void
	{
		$this->comparator = new SemVersionComparator();
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
			'major' => ['1.10.1', '2.1.0', -1],
			'minor' => ['1.0.0', '1.1.0', -1],
			'patch' => ['2.1.1', '2.1.0', 1],
			'same' => ['1.0.0', '1.0.0', 0],
			'regular vs pre-release' => ['1.0.0', '1.0.0-alpha', 1],
			'pre-release alphabetical comparison' => ['1.0.0-alpha', '1.0.0-beta', -1],
			'pre-release alphabetical identifiers compared in order' => ['1.0.0-alpha.beta', '1.0.0-beta', -1],
			'pre-release numerical identifiers compared in order' => ['1.0.0-3.alpha', '1.0.0-1.beta', 1],
			'longer pre-release is greater if identifiers are the same' => ['1.0.0-alpha.1', '1.0.0-alpha', 1],
			'multi identifier pre-release alphabetical comparison' => ['1.0.0-alpha.beta', '1.0.0-alpha.1', 1],
			'numeric pre-release-identifiers' => ['1.0.0-beta.11', '1.0.0-beta.2', 1],
			'rc vs beta' => ['1.0.0-rc.1', '1.0.0-beta.11', 1],
			'build part ignored' => ['1.0.0-alpha+20150919', '1.0.0-alpha+exp.sha.5114f85', 0],
			'alphanumeric pre-releases' => ['1.0.0-b1', '1.0.0-a', 1],
			'incompatible versions' => ['1.0.0', '2026.07.10', -1],
			'incompatible versions 2' => ['2026.07.10', '1.0.0', -1],
		];
	}
}
