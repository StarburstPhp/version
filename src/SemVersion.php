<?php declare(strict_types=1);

namespace Starburst\Version;

use Starburst\Version\Comparison\Comparator;
use Starburst\Version\Comparison\SemVersionComparator;
use Starburst\Version\Comparison\VersionComparatorTrait;
use Starburst\Version\Extension\BuildMetaData;
use Starburst\Version\Extension\PreRelease;

final class SemVersion implements Version
{
	use VersionComparatorTrait;

	public function __construct(
		public int $major,
		public int $minor,
		public int $patch,
		public ?PreRelease $preRelease = null,
		public ?BuildMetaData $buildMetaData = null,
	) {}

	public function __toString(): string
	{
		return $this->toString();
	}

	public function jsonSerialize(): mixed
	{
		return $this->toString();
	}

	public function toString(): string
	{
		return $this->major .
			'.' . $this->minor .
			'.' . $this->patch .
			($this->preRelease ? '-' .  $this->preRelease->toString() : '') .
			($this->buildMetaData ? '+' .  $this->buildMetaData->toString() : '')
		;
	}

	/**
	 * @return array{
	 *     major: int,
	 *     minor: int,
	 *     patch: int,
	 *     preRelease: list<string>|null,
	 *     buildMetaData: list<string>|null,
	 *     version: string,
	 * }
	 */
	public function getArrayCopy(): array
	{
		return [
			'major' => $this->major,
			'minor' => $this->minor,
			'patch' => $this->patch,
			'preRelease' => $this->preRelease?->getIdentifiers(),
			'buildMetaData' => $this->buildMetaData?->getIdentifiers(),
			'version' => $this->toString(),
		];
	}

	public function bump(Bump $bump, ?PreRelease $preRelease = null, ?BuildMetaData $buildMetaData = null): static
	{
		$major = $this->major;
		$minor = $this->minor;
		$patch = $this->patch;
		if ($bump === Bump::Major) {
			$major++;
			$minor = 0;
			$patch = 0;
		}
		elseif ($bump === Bump::Minor) {
			$minor++;
			$patch = 0;
		}
		elseif ($bump === Bump::Patch) {
			$patch++;
		}
		return new self(
			$major,
			$minor,
			$patch,
			$preRelease,
			$buildMetaData,
		);
	}

	protected function getComparator(): Comparator
	{
		if (!isset(self::$comparator)) {
			self::$comparator = new SemVersionComparator();
		}

		return self::$comparator;
	}
}
