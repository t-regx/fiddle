<?php
namespace TRegx\CleanRegex\Internal\Model;

use TRegx\CleanRegex\Internal\Model\Match\Entry;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Match\UsedForGroup;

class FalseNegative implements Entry, UsedForGroup
{
    /** @var RawMatchOffset */
    private $match;

    public function __construct(RawMatchOffset $match)
    {
        $this->match = $match;
    }

    public function maybeGroupIsMissing($nameOrIndex): bool
    {
        return !$this->match->hasGroup($nameOrIndex);
    }

    public function text(): string
    {
        return $this->match->getText();
    }

    public function byteOffset(): int
    {
        return $this->match->byteOffset();
    }

    public function isGroupMatched($nameOrIndex): bool
    {
        return $this->match->isGroupMatched($nameOrIndex);
    }

    public function getGroupTextAndOffset($nameOrIndex): array
    {
        return $this->match->getGroupTextAndOffset($nameOrIndex);
    }
}
