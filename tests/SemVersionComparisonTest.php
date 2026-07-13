<?php declare(strict_types=1);

namespace Starburst\Version\Tests;

use PHPUnit\Framework\TestCase;
use Starburst\Version\Parser;
use Starburst\Version\SemVersion;

final class SemVersionComparisonTest extends TestCase
{
	private function version(string $version): SemVersion
	{
		$parser = new Parser();
		$versionObj = $parser->parseString($version);
		$this->assertInstanceOf(SemVersion::class, $versionObj);
		return $versionObj;
	}

	public function testCanBeComparedToOtherVersion(): void
	{
		$this->assertSame(1, $this->version('2.1.1')->compareTo($this->version('2.1.0')));
	}

	public function testCanBeComparedUsingString(): void
	{
		$this->assertSame(0, $this->version('2.0.0')->compareTo('2.0.0'));
	}

	public function testEqualComparison(): void
	{
		$this->assertTrue($this->version('1.0.0')->isEqualTo('1.0.0'));
	}

	public function testNotEqualComparison(): void
	{
		$this->assertTrue($this->version('1.0.0')->isNotEqualTo('2.0.0'));
	}

	public function testGreaterThanComparison(): void
	{
		$this->assertTrue($this->version('1.0.1')->isGreaterThan('1.0.0'));
	}

	public function testGreaterOrEqualComparison(): void
	{
		$this->assertTrue($this->version('1.0.0')->isGreaterOrEqualTo('1.0.0'));
	}

	public function testLessThanComparison(): void
	{
		$this->assertTrue($this->version('1.0.1')->isLessThan('1.0.2'));
	}

	public function testLessThanOrEqualToComparison(): void
	{
		$this->assertTrue($this->version('1.0.0')->isLessOrEqualTo('1.0.0'));
	}
}
