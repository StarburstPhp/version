<?php declare(strict_types=1);

namespace Starburst\Version;

use Starburst\Version\Comparison\CalendarVersionComparator;
use Starburst\Version\Comparison\Comparator;
use Starburst\Version\Comparison\VersionComparatorTrait;
use Starburst\Version\Extension\BuildMetaData;
use Starburst\Version\Extension\PreRelease;

final class CalendarVersion implements Version
{
	use VersionComparatorTrait;

	public function __construct(
		public \DateTimeImmutable $releaseDate,
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
		return $this->releaseDate->format('Y.m.d') .
			($this->preRelease ? '-' .  $this->preRelease->toString() : '') .
			($this->buildMetaData ? '+' .  $this->buildMetaData->toString() : '')
		;
	}

	/**
	 * @return array{
	 *     major: numeric-string,
	 *     minor: numeric-string,
	 *     patch: numeric-string,
	 *     releaseDate: string,
	 *     preRelease: list<string>|null,
	 *     buildMetaData: list<string>|null,
	 *     version: string,
	 * }
	 */
	public function getArrayCopy(): array
	{
		return [
			'major' => $this->releaseDate->format('Y'),
			'minor' => $this->releaseDate->format('m'),
			'patch' => $this->releaseDate->format('d'),
			'releaseDate' => $this->releaseDate->format('Y-m-d'),
			'preRelease' => $this->preRelease?->getIdentifiers(),
			'buildMetaData' => $this->buildMetaData?->getIdentifiers(),
			'version' => $this->toString(),
		];
	}

	public function bump(
		Bump $bump = Bump::Patch,
		?PreRelease $preRelease = null,
		?BuildMetaData $buildMetaData = null,
		?\DateTimeImmutable $releaseDate = null,
	): static {
		$releaseDate ??= new \DateTimeImmutable('now')->setTime(0, 0, 0);

		if ($releaseDate == $this->releaseDate && !$buildMetaData) {
			throw new \BadMethodCallException('No build metadata provided to bump version. If release date is the same, build metadata must be provided.');
		}

		return new self($releaseDate, $preRelease, $buildMetaData);
	}

	protected function getComparator(): Comparator
	{
		if (!isset(self::$comparator)) {
			self::$comparator = new CalendarVersionComparator();
		}

		return self::$comparator;
	}
}
