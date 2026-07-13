<?php declare(strict_types=1);

namespace Starburst\Version\Comparison;

use Starburst\Version\CalendarVersion;
use Starburst\Version\Version;

final class CalendarVersionComparator extends AbstractComparator
{
	public function compare(Version $a, Version $b): int
	{
		if (!$a instanceof CalendarVersion) {
			return -1;
		}
		if (!$b instanceof CalendarVersion) {
			return -1;
		}
		$compare = $a->releaseDate <=> $b->releaseDate;
		if ($compare !== 0) {
			return $compare;
		}
		if ($a->preRelease && $b->preRelease) {
			return $this->comparePreReleases($a->preRelease, $b->preRelease);
		}

		return $this->resolvePreReleasePrecedence($a, $b);
	}
}
