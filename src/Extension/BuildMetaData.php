<?php declare(strict_types=1);

namespace Starburst\Version\Extension;

use Starburst\Version\Exceptions\InvalidVersion;

final class BuildMetaData extends Extension
{
	protected function validate(array $identifiers): void
	{
		if (count($identifiers) < 1) {
			throw new InvalidVersion('Build metadata must contain at least one identifier');
		}
		foreach ($identifiers as $identifier) {
			if (!is_string($identifier)) {
				throw new InvalidVersion('Build metadata identifiers must be strings');
			}
			if (!\strlen($identifier)) {
				throw new InvalidVersion('Build metadata identifiers must be non-empty-strings');
			}
		}
	}
}
