<?php declare(strict_types=1);

namespace Starburst\Version\Comparison;

use Starburst\Version\Version;

interface Comparator
{
	public function compare(Version $a, Version $b): int;
}
