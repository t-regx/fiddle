<?php
namespace TRegx\CleanRegex\Internal\Pcre\Legacy;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\FalseNegative;

/**
 * @deprecated
 */
class GroupPolyfillDecorator implements IRawMatchOffset
{
    /** @var FalseNegative */
    private $falseMatch;
    /** @var IRawMatchOffset */
    private $trueMatch;
    /** @var MatchAllFactory */
    private $allFactory;
    /** @var int */
    private $newMatchIndex;

    public function __construct(FalseNegative $match, MatchAllFactory $allFactory, int $newMatchIndex)
    {
        $this->falseMatch = $match;
        $this->trueMatch = null;
        $this->allFactory = $allFactory;
        $this->newMatchIndex = $newMatchIndex;
    }

    public function hasGroup(GroupKey $group): bool
    {
        if ($this->falseMatch->maybeGroupIsMissing($group->nameOrIndex())) {
            return $this->trueMatch()->hasGroup($group);
        }
        return true;
    }

    public function text(): string
    {
        return $this->falseMatch->text();
    }

    public function isGroupMatched($nameOrIndex): bool
    {
        if ($this->falseMatch->maybeGroupIsMissing($nameOrIndex)) {
            return $this->trueMatch()->isGroupMatched($nameOrIndex);
        }
        return $this->falseMatch->isGroupMatched($nameOrIndex);
    }

    public function getGroup($nameOrIndex): ?string
    {
        if ($this->falseMatch->maybeGroupIsMissing($nameOrIndex)) {
            return $this->read($this->trueMatch(), $nameOrIndex);
        }
        return $this->read($this->falseMatch, $nameOrIndex);
    }

    private function read(UsedForGroup $forGroup, $nameOrIndex): ?string
    {
        [$text, $offset] = $forGroup->getGroupTextAndOffset($nameOrIndex);
        if ($offset === -1) {
            return null;
        }
        return $text;
    }

    public function getGroupTextAndOffset($nameOrIndex): array
    {
        if ($this->falseMatch->maybeGroupIsMissing($nameOrIndex)) {
            return $this->trueMatch()->getGroupTextAndOffset($nameOrIndex);
        }
        return $this->falseMatch->getGroupTextAndOffset($nameOrIndex);
    }

    public function byteOffset(): int
    {
        return $this->falseMatch->byteOffset();
    }

    public function groupTexts(): array
    {
        return $this->trueMatch()->groupTexts();
    }

    public function groupOffsets(): array
    {
        return $this->trueMatch()->groupOffsets();
    }

    public function getGroupKeys(): array
    {
        return $this->trueMatch()->getGroupKeys();
    }

    private function trueMatch(): IRawMatchOffset
    {
        if ($this->trueMatch === null) {
            $this->trueMatch = new RawMatchesToMatchAdapter($this->allFactory->getRawMatches(), $this->newMatchIndex);
        }
        return $this->trueMatch;
    }
}
