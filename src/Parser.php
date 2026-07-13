<?php declare(strict_types=1);

namespace Starburst\Version;

use Starburst\Version\Exceptions\FailedToParseVersion;
use Starburst\Version\Extension\BuildMetaData;
use Starburst\Version\Extension\PreRelease;

final class Parser
{
	private const string REGEXP = '#^(?P<prefix>v|release\-)?
		(?P<Major>0|[0-9]\d*)\.
		(?P<Minor>0|[0-9]\d*)\.
		(?P<Patch>0|[0-9]\d*)
		(?:
			\-
			(?P<PreReleaseSuffix>(?:0|[1-9]\d*|\d*[a-zA-Z\-][0-9a-zA-Z\-]*)(?:\.(?:0|[1-9]\d*|\d*[a-zA-Z\-][0-9a-zA-Z\-]*))*)
		)?
		(?:
			\+
			(?P<BuildMetadata>[0-9a-zA-Z\-]+(?:\.[0-9a-zA-Z\-]+)*)
		)?
	$#xi';

	/**
	 * @param array{
	 *     major: int|numeric-string,
	 *     minor?: int|numeric-string,
	 *     patch?: int|numeric-string,
	 *     preRelease?: list<string>|null,
	 *     buildMetaData?: list<string>|null,
	 *     releaseDate?: string,
	 * } $data
	 */
	public function parseArray(array $data): Version
	{
		$buildMetaData = $preRelease = null;
		if (isset($data['buildMetaData'])) {
			$buildMetaData = BuildMetaData::fromArray($data['buildMetaData']);
		}
		if (isset($data['preRelease'])) {
			$preRelease = PreRelease::fromArray($data['preRelease']);
		}

		if (isset($data['releaseDate'])) {
			return new CalendarVersion(
				new \DateTimeImmutable($data['releaseDate']),
				$preRelease,
				$buildMetaData,
			);
		}

		if (!isset($data['minor'], $data['patch'])) {
			throw new FailedToParseVersion('Failed to parse version array: ' . var_export($data, true));
		}

		return new SemVersion(
			(int)$data['major'],
			(int)$data['minor'],
			(int)$data['patch'],
			$preRelease,
			$buildMetaData,
		);
	}

	public function parseString(string $version): Version
	{
		$rs = preg_match(self::REGEXP, $version, $matches);
		if (!$rs) {
			throw new FailedToParseVersion('Failed to parse version string: ' . $version);
		}

		if (strlen($matches['Major']) === 4) {
			if (!isset($matches['Minor']) || !isset($matches['Patch'])) {
				throw new FailedToParseVersion('Failed to parse version string: ' . $version);
			}

			$date = $matches['Major'] . '-' . $matches['Minor'] . '-' . $matches['Patch'];
			$releaseDate = \DateTimeImmutable::createFromFormat('!Y-m-d', $date);
			if (!$releaseDate) {
				throw new FailedToParseVersion('Failed to parse version string: ' . $version);
			}

			return new CalendarVersion(
				$releaseDate,
				$this->parsePreRelease($matches['PreReleaseSuffix'] ?? null),
				$this->parseBuildMetaData($matches['BuildMetadata'] ?? null),
			);
		}

		return new SemVersion(
			(int)$matches['Major'],
			isset($matches['Minor']) ? (int)$matches['Minor'] : 0,
			isset($matches['Patch']) ? (int)$matches['Patch'] : 0,
			$this->parsePreRelease($matches['PreReleaseSuffix'] ?? null),
			$this->parseBuildMetaData($matches['BuildMetadata'] ?? null),
		);
	}

	private function parsePreRelease(?string $preRelease): ?PreRelease
	{
		if (!$preRelease) {
			return null;
		}
		return PreRelease::fromString($preRelease);
	}

	private function parseBuildMetaData(?string $build): ?BuildMetaData
	{
		if (!$build) {
			return null;
		}
		return BuildMetaData::fromString($build);
	}

	public function unknownVersion(): Version
	{
		return new SemVersion(0, 0, 0, buildMetaData: BuildMetaData::from('unknown'));
	}
}
