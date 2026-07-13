<?php declare(strict_types=1);

namespace Starburst\Version\Comparison;

use Starburst\Version\Parser;
use Starburst\Version\Version;

trait VersionComparatorTrait
{
	private static Comparator $comparator;

	public function isEqualTo(Version|string $version): bool
	{
		return $this->compareTo($version) === 0;
	}

	public function isNotEqualTo(Version|string $version): bool
	{
		return !$this->isEqualTo($version);
	}

	public function isGreaterThan(Version|string $version): bool
	{
		return $this->compareTo($version) > 0;
	}

	public function isGreaterOrEqualTo(Version|string $version): bool
	{
		return $this->compareTo($version) >= 0;
	}

	public function isLessThan(Version|string $version): bool
	{
		return $this->compareTo($version) < 0;
	}

	public function isLessOrEqualTo(Version|string $version): bool
	{
		return $this->compareTo($version) <= 0;
	}

	/**
	 * @return int (1 if $this > $version, -1 if $this < $version, 0 if equal)
	 */
	public function compareTo(Version|string $version): int
	{
		if (is_string($version)) {
			$version = new Parser()->parseString($version);
		}

		return $this->getComparator()->compare($this, $version);
	}

	abstract protected function getComparator(): Comparator;
}
