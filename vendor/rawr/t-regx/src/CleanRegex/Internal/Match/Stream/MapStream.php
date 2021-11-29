<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

class MapStream implements Upstream
{
    use PreservesKey;

    /** @var Upstream */
    private $upstream;
    /** @var callable */
    private $mapFunction;

    public function __construct(Upstream $upstream, callable $mapFunction)
    {
        $this->upstream = $upstream;
        $this->mapFunction = $mapFunction;
    }

    public function all(): array
    {
        return \array_map($this->mapFunction, $this->upstream->all());
    }

    public function first()
    {
        return ($this->mapFunction)($this->upstream->first());
    }
}
