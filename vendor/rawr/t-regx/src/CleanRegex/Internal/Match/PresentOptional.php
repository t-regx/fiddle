<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Match\Optional;

class PresentOptional implements Optional
{
    /** @var mixed */
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function orThrow(string $exceptionClassName = null)
    {
        return $this->value;
    }

    public function orReturn($substitute)
    {
        return $this->value;
    }

    public function orElse(callable $substituteProducer)
    {
        return $this->value;
    }

    public function map(callable $mapper): Optional
    {
        return new PresentOptional($mapper($this->value));
    }
}
