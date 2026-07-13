<?php declare(strict_types=1);

namespace Starburst\Version;

enum Bump
{
	case Major;
	case Minor;
	case Patch;
}
