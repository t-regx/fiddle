<?php
namespace TRegx\CleanRegex\Replace\Callback;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\InvalidReplacementException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Details\Group\ReplaceMatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\RawMatchesToMatchAdapter;
use TRegx\CleanRegex\Internal\Replace\Details\Modification;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Internal\Type\ValueType;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\Group\CapturingGroup;
use TRegx\CleanRegex\Match\Details\MatchDetail;
use TRegx\CleanRegex\Replace\Details\ReplaceDetail;

class ReplaceCallbackObject
{
    /** @var callable */
    private $callback;
    /** @var Subject */
    private $subject;
    /** @var RawMatchesOffset */
    private $analyzedPattern;
    /** @var int */
    private $counter = 0;
    /** @var int */
    private $byteOffsetModification = 0;
    /** @var string */
    private $subjectModification;
    /** @var int */
    private $limit;
    /** @var ReplaceCallbackArgumentStrategy */
    private $argumentStrategy;

    public function __construct(callable                        $callback,
                                Subject                         $subject,
                                RawMatchesOffset                $analyzedPattern,
                                int                             $limit,
                                ReplaceCallbackArgumentStrategy $argumentStrategy)
    {
        $this->callback = $callback;
        $this->subject = $subject;
        $this->analyzedPattern = $analyzedPattern;
        $this->subjectModification = $this->subject->getSubject();
        $this->limit = $limit;
        $this->argumentStrategy = $argumentStrategy;
    }

    public function getCallback(): callable
    {
        return function (array $match) {
            return $this->invoke($match);
        };
    }

    private function invoke(array $match): string
    {
        $result = ($this->callback)($this->matchObject());
        $replacement = $this->getReplacement($result);
        $this->modifySubject($replacement);
        $this->modifyOffset($match[0], $replacement);
        return $replacement;
    }

    private function matchObject()
    {
        return $this->argumentStrategy->mapArgument($this->createDetailObject());
    }

    private function createDetailObject(): ReplaceDetail
    {
        $index = $this->counter++;
        $match = new RawMatchesToMatchAdapter($this->analyzedPattern, $index);
        return new ReplaceDetail(MatchDetail::create(
            $this->subject,
            $index,
            $this->limit,
            $match,
            new EagerMatchAllFactory($this->analyzedPattern),
            new UserData(),
            new ReplaceMatchGroupFactoryStrategy(
                $this->byteOffsetModification,
                $this->subjectModification)),
            new Modification($match, $this->subjectModification, $this->byteOffsetModification));
    }

    private function getReplacement($replacement): string
    {
        if (\is_string($replacement)) {
            return $replacement;
        }
        if ($replacement instanceof CapturingGroup) {
            return $this->groupAsReplacement($replacement);
        }
        if ($replacement instanceof Detail) {
            return $replacement;
        }
        throw new InvalidReplacementException(new ValueType($replacement));
    }

    private function groupAsReplacement(CapturingGroup $group): string
    {
        if ($group->matched()) {
            return $group->text();
        }
        throw GroupNotMatchedException::forReplacement(GroupKey::of($group->usedIdentifier()));
    }

    private function modifyOffset(string $search, string $replacement): void
    {
        $this->byteOffsetModification += \strlen($replacement) - \strlen($search);
    }

    private function modifySubject(string $replacement): void
    {
        [$text, $offset] = $this->analyzedPattern->getTextAndOffset($this->counter - 1);

        $this->subjectModification = \substr_replace(
            $this->subjectModification,
            $replacement,
            $offset + $this->byteOffsetModification,
            \strlen($text));
    }
}
