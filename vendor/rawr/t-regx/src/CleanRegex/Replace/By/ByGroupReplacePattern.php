<?php
namespace TRegx\CleanRegex\Replace\By;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Message\Replace\WithUnmatchedGroupMessage;
use TRegx\CleanRegex\Internal\Replace\By\GroupFallbackReplacer;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\DictionaryMapper;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\GroupMapper;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\IdentityMapper;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\MapGroupMapperDecorator;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\SubstituteFallbackMapper;
use TRegx\CleanRegex\Internal\Replace\By\IgnoreMessages;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\ComputedMatchStrategy;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\ConstantReturnStrategy;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\LazyMessageThrowStrategy;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\MatchRs;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\ThrowStrategy;
use TRegx\CleanRegex\Internal\Replace\By\PerformanceEmptyGroupReplace;
use TRegx\CleanRegex\Internal\Replace\By\UnmatchedGroupStrategy;
use TRegx\CleanRegex\Internal\Replace\Wrapper;
use TRegx\CleanRegex\Internal\Replace\WrappingMapper;
use TRegx\CleanRegex\Internal\Replace\WrappingMatchRs;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Replace\Callback\MatchGroupStrategy;
use TRegx\CleanRegex\Replace\Callback\ReplacePatternCallbackInvoker;
use TRegx\CleanRegex\Replace\GroupReplace;

class ByGroupReplacePattern implements GroupReplace
{
    /** @var GroupFallbackReplacer */
    private $fallbackReplacer;
    /** @var GroupKey */
    private $group;
    /** @var Subject */
    private $subject;
    /** @var PerformanceEmptyGroupReplace */
    private $performanceReplace;
    /** @var ReplacePatternCallbackInvoker */
    private $replaceCallbackInvoker;
    /** @var Wrapper */
    private $middlewareMapper;

    public function __construct(GroupFallbackReplacer         $fallbackReplacer,
                                PerformanceEmptyGroupReplace  $performanceReplace,
                                ReplacePatternCallbackInvoker $replaceCallbackInvoker,
                                GroupKey                      $group,
                                Subject                       $subject,
                                Wrapper                       $middlewareMapper)
    {
        $this->fallbackReplacer = $fallbackReplacer;
        $this->group = $group;
        $this->subject = $subject;
        $this->performanceReplace = $performanceReplace;
        $this->replaceCallbackInvoker = $replaceCallbackInvoker;
        $this->middlewareMapper = $middlewareMapper;
    }

    public function map(array $occurrencesAndReplacements): GroupReplace
    {
        return $this->performMap(new DictionaryMapper($occurrencesAndReplacements));
    }

    public function mapAndCallback(array $occurrencesAndReplacements, callable $mapper): GroupReplace
    {
        return $this->performMap(new MapGroupMapperDecorator(new DictionaryMapper($occurrencesAndReplacements), $mapper));
    }

    private function performMap(GroupMapper $mapper): GroupReplace
    {
        return new UnmatchedGroupStrategy(
            $this->fallbackReplacer,
            $this->group,
            new SubstituteFallbackMapper(
                new WrappingMapper($mapper, $this->middlewareMapper),
                new LazyMessageThrowStrategy(),
                $this->subject),
            $this->middlewareMapper);
    }

    public function mapIfExists(array $occurrencesAndReplacements): GroupReplace
    {
        return new UnmatchedGroupStrategy(
            $this->fallbackReplacer,
            $this->group,
            new IgnoreMessages(new WrappingMapper(new DictionaryMapper($occurrencesAndReplacements), $this->middlewareMapper)),
            $this->middlewareMapper);
    }

    public function orElseThrow(string $exceptionClassName = GroupNotMatchedException::class): string
    {
        return $this->replaceGroupOptional(new ThrowStrategy($exceptionClassName, new WithUnmatchedGroupMessage($this->group)));
    }

    public function orElseWith(string $replacement): string
    {
        return $this->replaceGroupOptional(new ConstantReturnStrategy($replacement));
    }

    public function orElseIgnore(): string
    {
        return $this->replaceGroupOptional(new DefaultStrategy());
    }

    public function orElseEmpty(): string
    {
        if (\is_int($this->group->nameOrIndex())) {
            return $this->performanceReplace->replaceWithGroupOrEmpty($this->group->nameOrIndex());
        }
        return $this->replaceGroupOptional(new ConstantReturnStrategy(''));
    }

    public function orElseCalling(callable $replacementProducer): string
    {
        return $this->replaceGroupOptional(new ComputedMatchStrategy($replacementProducer, "orElseCalling"));
    }

    private function replaceGroupOptional(MatchRs $substitute): string
    {
        return $this->fallbackReplacer->replaceOrFallback($this->group,
            new IgnoreMessages(new WrappingMapper(new IdentityMapper(), $this->middlewareMapper)),
            new WrappingMatchRs($substitute, $this->middlewareMapper));
    }

    public function callback(callable $callback): string
    {
        return $this->replaceCallbackInvoker->invoke($callback, new MatchGroupStrategy($this->group));
    }
}
