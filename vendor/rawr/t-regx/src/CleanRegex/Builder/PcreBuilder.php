<?php
namespace TRegx\CleanRegex\Builder;

use TRegx\CleanRegex\Internal\Expression\Pcre;
use TRegx\CleanRegex\Internal\Prepared\Expression\Template;
use TRegx\CleanRegex\Internal\Prepared\Figure\InjectFigures;
use TRegx\CleanRegex\Internal\Prepared\Orthography\PcreOrthography;
use TRegx\CleanRegex\Internal\Prepared\Orthography\PcreSpelling;
use TRegx\CleanRegex\Internal\Prepared\Tokens;
use TRegx\CleanRegex\Pattern;

class PcreBuilder
{
    /**
     * Please use method {@see Pattern::of}. Method {@see PcreBuilder::of} is only present,
     * in case there's an automatic delimiters' bug, that would make {@link Pattern::of()} error-prone.
     * {@see PcreBuilder::of} is error-prone to {@see MalformedPatternException}, because of delimiters.
     *
     * @param string $delimitedPattern
     * @return Pattern
     * @see \TRegx\CleanRegex\Pattern::of
     */
    public function of(string $delimitedPattern): Pattern
    {
        return new Pattern(new Pcre($delimitedPattern));
    }

    public function inject(string $input, array $values): Pattern
    {
        return new Pattern(new Template(new PcreSpelling($input), new InjectFigures($values)));
    }

    public function template(string $pattern): PatternTemplate
    {
        return new PatternTemplate(new PcreOrthography($pattern));
    }

    public function builder(string $pattern): TemplateBuilder
    {
        return new TemplateBuilder(new PcreOrthography($pattern), new Tokens([]));
    }
}
