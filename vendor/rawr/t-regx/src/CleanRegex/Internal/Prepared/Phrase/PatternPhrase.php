<?php
namespace TRegx\CleanRegex\Internal\Prepared\Phrase;

class PatternPhrase extends Phrase
{
    /** @var string */
    private $pattern;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    public function conjugated(string $delimiter): string
    {
        return $this->pattern;
    }

    protected function unconjugated(string $delimiter): string
    {
        return $this->pattern;
    }
}
