<?php
namespace TRegx\CleanRegex\Internal;

class StringSubject implements Subject
{
    /** @var string */
    private $subject;

    public function __construct(string $subject)
    {
        $this->subject = $subject;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }
}
