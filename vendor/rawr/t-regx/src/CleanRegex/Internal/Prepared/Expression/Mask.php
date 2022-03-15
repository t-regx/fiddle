<?php
namespace TRegx\CleanRegex\Internal\Prepared\Expression;

use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Internal\Candidates;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Delimiter\UndelimitablePatternException;
use TRegx\CleanRegex\Internal\Expression\Expression;
use TRegx\CleanRegex\Internal\Expression\StrictInterpretation;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Template\MaskToken;

class Mask implements Expression
{
    use StrictInterpretation;

    /** @var MaskToken */
    private $token;
    /** @var Candidates */
    private $candidates;
    /** @var Flags */
    private $flags;
    /** @var string[] */
    private $keywords;
    /** @var string */
    private $mask;

    public function __construct(string $mask, array $keywords, Flags $flags)
    {
        $this->token = new MaskToken($mask, $keywords);
        $this->candidates = new Candidates($this->token);
        $this->flags = $flags;
        $this->keywords = $keywords;
        $this->mask = $mask;
    }

    protected function phrase(): Phrase
    {
        return $this->token->phrase();
    }

    protected function delimiter(): Delimiter
    {
        try {
            return $this->candidates->delimiter();
        } catch (UndelimitablePatternException $exception) {
            throw ExplicitDelimiterRequiredException::forMask($this->keywords);
        }
    }

    protected function flags(): Flags
    {
        return $this->flags;
    }

    protected function undevelopedInput(): string
    {
        return $this->mask;
    }
}
