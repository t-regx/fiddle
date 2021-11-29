<?php
namespace TRegx\CleanRegex\Builder;

use TRegx\CleanRegex\Internal\Prepared\Expression\Template;
use TRegx\CleanRegex\Internal\Prepared\Orthography\Orthography;
use TRegx\CleanRegex\Internal\Prepared\Template\AlterationToken;
use TRegx\CleanRegex\Internal\Prepared\Template\LiteralToken;
use TRegx\CleanRegex\Internal\Prepared\Template\MaskToken;
use TRegx\CleanRegex\Internal\Prepared\Template\PatternToken;
use TRegx\CleanRegex\Internal\Prepared\Template\Token;
use TRegx\CleanRegex\Internal\Prepared\Tokens;
use TRegx\CleanRegex\Pattern;

class TemplateBuilder
{
    /** @var Orthography */
    private $orthography;
    /** @var Tokens */
    private $tokens;

    public function __construct(Orthography $orthography, Tokens $tokens)
    {
        $this->orthography = $orthography;
        $this->tokens = $tokens;
    }

    public function mask(string $mask, array $keywords): TemplateBuilder
    {
        return $this->next(new MaskToken($mask, $keywords));
    }

    public function literal(string $text): TemplateBuilder
    {
        return $this->next(new LiteralToken($text));
    }

    public function alteration(array $figures): TemplateBuilder
    {
        return $this->next(new AlterationToken($figures));
    }

    public function pattern(string $pattern): TemplateBuilder
    {
        return $this->next(new PatternToken($pattern));
    }

    private function next(Token $token): TemplateBuilder
    {
        return new TemplateBuilder($this->orthography, $this->tokens->next($token));
    }

    public function build(): Pattern
    {
        return new Pattern(new Template($this->orthography->spelling($this->tokens->condition()), $this->tokens->figures()));
    }
}
