<?php declare(strict_types=1);

namespace Starburst\Version\Tests\Extension;

use PHPUnit\Framework\TestCase;
use Starburst\Version\Extension\BuildMetaData;

final class BuildMetaDataTest extends TestCase
{
	public function testCreateFromIdentifiersList(): void
	{
		$metaData = BuildMetaData::from('123', '456');

		$this->assertSame(['123', '456'], $metaData->getIdentifiers());
	}

	public function testCreateFromString(): void
	{
		$metaData = BuildMetaData::fromString('123.456');

		$this->assertSame(['123', '456'], $metaData->getIdentifiers());
	}

	public function testCreateFromArray(): void
	{
		$metaData = BuildMetaData::fromArray(['123', '456']);

		$this->assertSame(['123', '456'], $metaData->getIdentifiers());
	}

	public function testToString(): void
	{
		$extension = BuildMetaData::from('123', '456');

		$this->assertSame('123.456', $extension->toString());
	}

	public function testValidateInput(): void
	{
		$this->expectExceptionMessageIsOrContains('Build metadata identifiers must be strings');
		BuildMetaData::fromArray([123]);
	}

	public function testValidateEmptyValues(): void
	{
		$this->expectExceptionMessageIsOrContains('Build metadata identifiers must be non-empty-strings');
		BuildMetaData::from('123', '');
	}

	public function testValidateEmptyList(): void
	{
		$this->expectExceptionMessageIsOrContains('must contain at least one identifier');
		BuildMetaData::fromArray([]);
	}
}
