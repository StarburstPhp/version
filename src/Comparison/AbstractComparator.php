<?php declare(strict_types=1);

namespace Starburst\Version\Comparison;

use Starburst\Version\Extension\PreRelease;
use Starburst\Version\Version;

abstract class AbstractComparator implements Comparator
{
	protected function comparePreReleases(PreRelease $preRelease1, PreRelease $preRelease2): int
	{
		$preRelease1Ids = $preRelease1->getIdentifiers();
		$preRelease2Ids = $preRelease2->getIdentifiers();

		$preRelease1IdsCount = count($preRelease1Ids);
		$preRelease2IdsCount = count($preRelease2Ids);

		$limit = min($preRelease1IdsCount, $preRelease2IdsCount);

		for ($i = 0; $i < $limit; $i++) {
			if ($preRelease1Ids[$i] === $preRelease2Ids[$i]) {
				continue;
			}

			return $preRelease1Ids[$i] <=> $preRelease2Ids[$i];
		}

		return $preRelease1IdsCount - $preRelease2IdsCount;
	}

	protected function resolvePreReleasePrecedence(Version $version1, Version $version2): int
	{
		//pre-release version has lower precedence than a normal version
		return -1 * (($version1->preRelease ? 1 : 0) <=> ($version2->preRelease ? 1 : 0));
	}
}
