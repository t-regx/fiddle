<?php
namespace TRegx\CleanRegex\Match\Details\Group;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\Factory\Optional\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupDetails;
use TRegx\CleanRegex\Internal\Subject;

class NotMatchedGroup implements Group
{
    /** @var Subject */
    private $subject;
    /** @var GroupDetails */
    private $details;
    /** @var NotMatchedOptionalWorker */
    private $worker;

    public function __construct(Subject $subject, GroupDetails $details, NotMatchedOptionalWorker $worker)
    {
        $this->subject = $subject;
        $this->details = $details;
        $this->worker = $worker;
    }

    public function text(): string
    {
        throw $this->groupNotMatched('text');
    }

    public function textLength(): int
    {
        throw $this->groupNotMatched('textLength');
    }

    public function textByteLength(): int
    {
        throw $this->groupNotMatched('textByteLength');
    }

    public function toInt(int $base = null): int
    {
        throw $this->groupNotMatched('toInt');
    }

    public function isInt(int $base = null): bool
    {
        throw $this->groupNotMatched('isInt');
    }

    protected function groupNotMatched(string $method): GroupNotMatchedException
    {
        return GroupNotMatchedException::forMethod($this->details->group(), $method);
    }

    public function matched(): bool
    {
        return false;
    }

    public function equals(string $expected): bool
    {
        return false;
    }

    public function name(): ?string
    {
        return $this->details->name();
    }

    public function index(): int
    {
        return $this->details->index();
    }

    /**
     * @return int|string
     */
    public function usedIdentifier()
    {
        return $this->details->nameOrIndex();
    }

    public function offset(): int
    {
        throw $this->groupNotMatched('offset');
    }

    public function tail(): int
    {
        throw $this->groupNotMatched('tail');
    }

    public function byteOffset(): int
    {
        throw $this->groupNotMatched('byteOffset');
    }

    public function byteTail(): int
    {
        throw $this->groupNotMatched('byteTail');
    }

    public function substitute(string $replacement): string
    {
        throw $this->groupNotMatched('substitute');
    }

    public function subject(): string
    {
        return $this->subject->getSubject();
    }

    public function all(): array
    {
        return $this->details->all();
    }

    public function orReturn($substitute)
    {
        return $substitute;
    }

    public function orThrow(string $exceptionClassName = null): void
    {
        throw $this->worker->throwable($exceptionClassName);
    }

    public function orElse(callable $substituteProducer)
    {
        return $substituteProducer(...$this->worker->arguments());
    }
}
