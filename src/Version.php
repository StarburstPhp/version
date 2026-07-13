<?php declare(strict_types=1);

namespace Starburst\Version;

use Starburst\Version\Extension\BuildMetaData;
use Starburst\Version\Extension\PreRelease;

interface Version extends \JsonSerializable, \Stringable
{
	public ?PreRelease $preRelease {get;}

	public function toString(): string;

	/**
	 * @return array<string, mixed>
	 */
	public function getArrayCopy(): array;

	public function bump(Bump $bump, ?PreRelease $preRelease = null, ?BuildMetaData $buildMetaData = null): static;
}
