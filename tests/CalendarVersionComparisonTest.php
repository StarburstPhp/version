<?php declare(strict_types=1);

namespace Starburst\Version\Tests;

use PHPUnit\Framework\TestCase;
use Starburst\Version\CalendarVersion;
use Starburst\Version\Parser;

final class CalendarVersionComparisonTest extends TestCase
{
	private function version(string $version): CalendarVersion
	{
		$parser = new Parser();
		$versionObj = $parser->parseString($version);
		$this->assertInstanceOf(CalendarVersion::class, $versionObj);
		return $versionObj;
	}

	public function testCanBeComparedToOtherVersion(): void
	{
		$this->assertSame(1, $this->version('2026.07.01')->compareTo($this->version('2026.06.30')));
	}

	public function testCanBeComparedUsingString(): void
	{
		$this->assertSame(0, $this->version('2026.01.01')->compareTo('2026.01.01'));
	}

	public function testEqualComparison(): void
	{
		$this->assertTrue($this->version('2026.02.15')->isEqualTo('2026.02.15'));
	}

	public function testNotEqualComparison(): void
	{
		$this->assertTrue($this->version('2026.07.08')->isNotEqualTo('2026.07.14'));
	}

	public function testGreaterThanComparison(): void
	{
		$this->assertTrue($this->version('2026.07.10')->isGreaterThan('2026.07.08'));
	}

	public function testGreaterOrEqualComparison(): void
	{
		$this->assertTrue($this->version('2026.05.25')->isGreaterOrEqualTo('2026.05.25'));
	}

	public function testLessThanComparison(): void
	{
		$this->assertTrue($this->version('2026.06.02')->isLessThan('2026.06.03'));
	}

	public function testLessThanOrEqualToComparison(): void
	{
		$this->assertTrue($this->version('2026.01.15')->isLessOrEqualTo('2026.01.15'));
	}
}
