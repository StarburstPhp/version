<?php declare(strict_types=1);

namespace Starburst\Version\Comparison;

use Starburst\Version\SemVersion;
use Starburst\Version\Version;

final class SemVersionComparator extends AbstractComparator
{
	public function compare(Version $a, Version $b): int
	{
		if (!$a instanceof SemVersion) {
			return -1;
		}
		if (!$b instanceof SemVersion) {
			return -1;
		}
		$numberComparisonResult = $this->compareNumbers($a, $b);
		if ($numberComparisonResult !== 0) {
			return $numberComparisonResult;
		}

		if ($a->preRelease && $b->preRelease) {
			return $this->comparePreReleases($a->preRelease, $b->preRelease);
		}

		return $this->resolvePreReleasePrecedence($a, $b);
	}

	private function compareNumbers(SemVersion $a, SemVersion $b): int
	{
		return [$a->major, $a->minor, $a->patch] <=> [$b->major, $b->minor, $b->patch];
	}
}
