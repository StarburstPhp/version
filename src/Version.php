<?php declare(strict_types=1);

namespace Starburst\Version;

use Starburst\Version\Extension\PreRelease;

interface Version extends \JsonSerializable, \Stringable
{
	public ?PreRelease $preRelease {get;}

	public function toString(): string;

	/**
	 * @return array<string, mixed>
	 */
	public function getArrayCopy(): array;
}
