<?php declare(strict_types=1);

namespace Starburst\Version\Tests;

use PHPUnit\Framework\TestCase;
use Starburst\Version\Bump;
use Starburst\Version\CalendarVersion;
use Starburst\Version\Extension\BuildMetaData;
use Starburst\Version\Extension\PreRelease;

final class CalendarVersionBumpTest extends TestCase
{
	public function testBumpSameDateWithoutMetaData(): void
	{
		$date = new \DateTimeImmutable('2021-01-01');
		$version = new CalendarVersion($date);

		$this->expectException(\BadMethodCallException::class);
		$version->bump(Bump::Major, releaseDate: $date);
	}

	public function testBumpSameDateWithMetaData(): void
	{
		$date = new \DateTimeImmutable('2021-01-01');
		$version = new CalendarVersion($date);

		$newVersion = $version->bump(
			buildMetaData: BuildMetaData::from('f3b2974'),
			releaseDate: $date,
		);

		$this->assertSame('2021.01.01+f3b2974', $newVersion->toString());
		$this->assertSame('2021.01.01', $version->toString());
	}

	public function testBumpWithoutReleaseDate(): void
	{
		$today = $this->now();
		$date = new \DateTimeImmutable('2021-01-01');
		$version = new CalendarVersion($date);

		$newVersion = $version->bump(
			buildMetaData: BuildMetaData::from('f3b2974'),
		);

		$this->assertSame($today->format('Y.m.d') . '+f3b2974', $newVersion->toString());
		$this->assertSame('2021.01.01', $version->toString());
	}

	public function testBumpWithoutReleaseDateAndSameDate(): void
	{
		$today = $this->now();
		$version = new CalendarVersion($today);

		$newVersion = $version->bump(
			buildMetaData: BuildMetaData::from('f3b2974'),
		);

		$this->assertSame($today->format('Y.m.d') . '+f3b2974', $newVersion->toString());
		$this->assertSame($today->format('Y.m.d'), $version->toString());
	}

	public function testBumpWithoutReleaseDateAndSameDateAndMissingMetaData(): void
	{
		$today = $this->now();
		$version = new CalendarVersion($today);

		$this->expectException(\BadMethodCallException::class);
		$version->bump();
	}

	public function testBumpWithPreRelease(): void
	{
		$today = $this->now();
		$date = new \DateTimeImmutable('2021-01-01');
		$version = new CalendarVersion($date);

		$newVersion = $version->bump(
			preRelease: PreRelease::from('alpha', '1'),
		);

		$this->assertSame($today->format('Y.m.d') . '-alpha.1', $newVersion->toString());
		$this->assertSame('2021.01.01', $version->toString());
	}

	public function testBumpWithPreReleaseAndMetaData(): void
	{
		$today = $this->now();
		$date = new \DateTimeImmutable('2021-01-01');
		$version = new CalendarVersion($date);

		$newVersion = $version->bump(
			preRelease: PreRelease::from('alpha', '1'),
			buildMetaData: BuildMetaData::from('f3b2974'),
		);

		$this->assertSame($today->format('Y.m.d') . '-alpha.1+f3b2974', $newVersion->toString());
		$this->assertSame('2021.01.01', $version->toString());
	}

	public function testBumpWithPreReleaseAndMetaDataReplacesOldData(): void
	{
		$today = $this->now();
		$date = new \DateTimeImmutable('2021-01-01');
		$version = new CalendarVersion(
			$date,
			preRelease: PreRelease::from('beta', '2'),
			buildMetaData: BuildMetaData::from('d841665'),
		);

		$newVersion = $version->bump(
			preRelease: PreRelease::from('alpha', '1'),
			buildMetaData: BuildMetaData::from('f3b2974'),
		);

		$this->assertSame($today->format('Y.m.d') . '-alpha.1+f3b2974', $newVersion->toString());
		$this->assertSame('2021.01.01-beta.2+d841665', $version->toString());
	}

	private function now(): \DateTimeImmutable
	{
		return new \DateTimeImmutable('now')->setTime(0, 0, 0);
	}
}
