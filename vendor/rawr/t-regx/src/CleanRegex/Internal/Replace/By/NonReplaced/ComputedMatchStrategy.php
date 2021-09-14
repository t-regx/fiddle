<?php
namespace TRegx\CleanRegex\Internal\Replace\By\NonReplaced;

use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Internal\Type\ValueType;
use TRegx\CleanRegex\Match\Details\Detail;

class ComputedMatchStrategy implements MatchRs
{
    /** @var callable */
    private $mapper;
    /** @var string */
    private $callingMethod;

    public function __construct(callable $mapper, string $callingMethod)
    {
        $this->mapper = $mapper;
        $this->callingMethod = $callingMethod;
    }

    public function substituteGroup(Detail $detail): string
    {
        $result = ($this->mapper)($detail);
        if ($result === null) {
            throw new InvalidReturnValueException($this->callingMethod, 'string', new ValueType(null));
        }
        return $result;
    }
}
