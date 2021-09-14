<?php
namespace TRegx\CleanRegex\Internal\Offset;

use TRegx\CleanRegex\Internal\Model\Match\Entry;
use TRegx\CleanRegex\Internal\Subject;

class SubjectCoordinates
{
    /** @var Entry */
    private $entry;
    /** @var Coordinate */
    private $coordinate;
    /** @var Subject */
    private $subject;

    public function __construct(Entry $entry, Subject $subject)
    {
        $this->entry = $entry;
        $this->coordinate = new Coordinate($entry);
        $this->subject = $subject;
    }

    public function characterOffset(): int
    {
        return $this->coordinate->offset()->characters($this->subject->getSubject());
    }

    public function characterTail(): int
    {
        return $this->coordinate->tail()->characters($this->subject->getSubject());
    }

    public function characterLength(): int
    {
        return \mb_strlen($this->entry->text());
    }

    public function byteOffset(): int
    {
        return $this->coordinate->offset()->bytes();
    }

    public function byteTail(): int
    {
        return $this->coordinate->tail()->bytes();
    }

    public function byteLength(): int
    {
        return \strlen($this->entry->text());
    }
}
