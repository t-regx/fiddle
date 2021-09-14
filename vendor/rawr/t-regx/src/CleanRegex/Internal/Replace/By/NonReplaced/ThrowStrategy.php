<?php
namespace TRegx\CleanRegex\Internal\Replace\By\NonReplaced;

use TRegx\CleanRegex\Internal\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\SignatureExceptionFactory;
use TRegx\CleanRegex\Internal\StringSubject;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Detail;

class ThrowStrategy implements SubjectRs, MatchRs
{
    /** @var SignatureExceptionFactory */
    private $factory;
    /** @var string */
    private $className;

    public function __construct(string $className, NotMatchedMessage $message)
    {
        $this->factory = new SignatureExceptionFactory($message);
        $this->className = $className;
    }

    public function substitute(Subject $subject): string
    {
        throw $this->factory->create($this->className, $subject);
    }

    public function substituteGroup(Detail $detail): string
    {
        throw $this->factory->create($this->className, new StringSubject($detail->subject()));
    }
}
