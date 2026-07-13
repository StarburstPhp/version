<?php declare(strict_types=1);

namespace Starburst\Version\Tests;

use PHPUnit\Framework\TestCase;
use Starburst\Version\Bump;
use Starburst\Version\Extension\BuildMetaData;
use Starburst\Version\Extension\PreRelease;
use Starburst\Version\SemVersion;

final class SemVersionBumpTest extends TestCase
{
	public function testBumpMajor(): void
	{
		$version = new SemVersion(1, 2, 3);
		$newVersion = $version->bump(Bump::Major);
		$this->assertSame('2.0.0', $newVersion->toString());
		$this->assertSame('1.2.3', $version->toString());
	}

	public function testBumpMinor(): void
	{
		$version = new SemVersion(1, 2, 3);
		$newVersion = $version->bump(Bump::Minor);
		$this->assertSame('1.3.0', $newVersion->toString());
		$this->assertSame('1.2.3', $version->toString());
	}

	public function testBumpPatch(): void
	{
		$version = new SemVersion(1, 2, 3);
		$newVersion = $version->bump(Bump::Patch);
		$this->assertSame('1.2.4', $newVersion->toString());
		$this->assertSame('1.2.3', $version->toString());
	}

	public function testStripsOldMetaData(): void
	{
		$version = new SemVersion(
			1,
			2,
			3,
			buildMetaData: BuildMetaData::from('test', '2'),
		);
		$newVersion = $version->bump(Bump::Minor);
		$this->assertSame('1.3.0', $newVersion->toString());
		$this->assertSame('1.2.3+test.2', $version->toString());
	}

	public function testStripsOldPreRelease(): void
	{
		$version = new SemVersion(
			1,
			2,
			3,
			preRelease: PreRelease::from('dev', '2'),
		);
		$newVersion = $version->bump(Bump::Minor);
		$this->assertSame('1.3.0', $newVersion->toString());
		$this->assertSame('1.2.3-dev.2', $version->toString());
	}

	public function testBumpWithPreRelease(): void
	{
		$version = new SemVersion(1, 2, 3);
		$newVersion = $version->bump(Bump::Major, preRelease: PreRelease::from('build', '2'));
		$this->assertSame('2.0.0-build.2', $newVersion->toString());
		$this->assertSame('1.2.3', $version->toString());
	}

	public function testBumpWithPreReleaseReplacesOldPreRelease(): void
	{
		$version = new SemVersion(
			1,
			2,
			3,
			preRelease: PreRelease::from('dev', '1'),
		);
		$newVersion = $version->bump(Bump::Major, preRelease: PreRelease::from('build', '2'));
		$this->assertSame('2.0.0-build.2', $newVersion->toString());
		$this->assertSame('1.2.3-dev.1', $version->toString());
	}

	public function testBumpWithBuildMetaData(): void
	{
		$version = new SemVersion(1, 2, 3);
		$newVersion = $version->bump(Bump::Major, buildMetaData: BuildMetaData::from('eda2fe9'));
		$this->assertSame('2.0.0+eda2fe9', $newVersion->toString());
		$this->assertSame('1.2.3', $version->toString());
	}

	public function testBumpWithBuildMetaDataReplacesOldMetaData(): void
	{
		$version = new SemVersion(
			1,
			2,
			3,
			buildMetaData: BuildMetaData::from('d841665'),
		);
		$newVersion = $version->bump(Bump::Major, buildMetaData: BuildMetaData::from('eda2fe9'));
		$this->assertSame('2.0.0+eda2fe9', $newVersion->toString());
		$this->assertSame('1.2.3+d841665', $version->toString());
	}

	public function testBumpWithPreReleaseAndBuildMetaData(): void
	{
		$version = new SemVersion(1, 2, 3);
		$newVersion = $version->bump(
			Bump::Major,
			preRelease: PreRelease::from('build', '2'),
			buildMetaData: BuildMetaData::from('eda2fe9'),
		);
		$this->assertSame('2.0.0-build.2+eda2fe9', $newVersion->toString());
		$this->assertSame('1.2.3', $version->toString());
	}
}
