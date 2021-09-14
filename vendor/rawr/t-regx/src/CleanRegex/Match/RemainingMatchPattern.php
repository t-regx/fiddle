<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Base\DetailPredicateBaseDecorator;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Match\MethodPredicate;

class RemainingMatchPattern extends AbstractMatchPattern
{
    /** @var ApiBase */
    private $originalBase;
    /** @var MatchAllFactory */
    private $allFactory;

    public function __construct(DetailPredicateBaseDecorator $base, Base $original, MatchAllFactory $allFactory)
    {
        parent::__construct($base, $allFactory);
        $this->originalBase = $original;
        $this->allFactory = $allFactory;
    }

    public function test(): bool
    {
        return !empty($this->getDetailObjects());
    }

    public function count(): int
    {
        return \count($this->getDetailObjects());
    }

    public function remaining(callable $predicate): RemainingMatchPattern
    {
        return new RemainingMatchPattern(
            new DetailPredicateBaseDecorator($this->base, new MethodPredicate($predicate, 'remaining')),
            $this->originalBase,
            $this->allFactory);
    }
}
