<?php declare(strict_types=1);

namespace Starburst\Version\Tests\Extension;

use PHPUnit\Framework\TestCase;
use Starburst\Version\Extension\PreRelease;

final class PreReleaseTest extends TestCase
{
	public function testCreateFromIdentifiersList(): void
	{
		$metaData = PreRelease::from('123', '456');

		$this->assertSame(['123', '456'], $metaData->getIdentifiers());
	}

	public function testCreateFromString(): void
	{
		$metaData = PreRelease::fromString('123.456');

		$this->assertSame(['123', '456'], $metaData->getIdentifiers());
	}

	public function testCreateFromArray(): void
	{
		$metaData = PreRelease::fromArray(['123', '456']);

		$this->assertSame(['123', '456'], $metaData->getIdentifiers());
	}

	public function testToString(): void
	{
		$extension = PreRelease::from('123', '456');

		$this->assertSame('123.456', $extension->toString());
	}

	public function testValidateInput(): void
	{
		$this->expectExceptionMessageIsOrContains('Pre-release version identifiers must be strings');
		PreRelease::fromArray([123]);
	}

	public function testValidateEmptyValues(): void
	{
		$this->expectExceptionMessageIsOrContains('Pre-release version identifiers must be non-empty-strings');
		PreRelease::from('123', '');
	}

	public function testValidateEmptyList(): void
	{
		$this->expectExceptionMessageIsOrContains('must contain at least one identifier');
		PreRelease::fromArray([]);
	}
}
