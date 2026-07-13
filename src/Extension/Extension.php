<?php declare(strict_types=1);

namespace Starburst\Version\Extension;

use Starburst\Version\Exceptions\InvalidVersion;

abstract class Extension
{
	protected const string IDENTIFIERS_SEPARATOR = '.';

	/** @var list<string> */
	private array $identifiers;

	/**
	 * @param list<mixed> $identifiers
	 */
	final protected function __construct(array $identifiers)
	{
		$this->validate($identifiers);

		$this->identifiers = $identifiers;
	}

	/**
	 * @param array<mixed> $identifiers
	 * @phpstan-assert non-empty-list<string> $identifiers
	 * @throws InvalidVersion
	 */
	abstract protected function validate(array $identifiers): void;

	public static function from(string $identifier, string ...$identifiers): static
	{
		return new static(func_get_args());
	}

	/**
	 * @param list<mixed> $identifiers
	 */
	public static function fromArray(array $identifiers): static
	{
		return new static($identifiers);
	}

	public static function fromString(string $extension): static
	{
		return new static(explode(self::IDENTIFIERS_SEPARATOR, trim($extension)));
	}

	/**
	 * @return list<string>
	 */
	public function getIdentifiers(): array
	{
		return $this->identifiers;
	}

	public function toString(): string
	{
		return implode(self::IDENTIFIERS_SEPARATOR, $this->identifiers);
	}
}
