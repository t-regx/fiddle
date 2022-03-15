<?php
namespace TRegx\CleanRegex\Match\Details\Group;

use TRegx\CleanRegex\Match\Optional;

interface CapturingGroup extends Optional
{
    public function text(): string;

    public function textLength(): int;

    public function textByteLength(): int;

    public function toInt(int $base = null): int;

    public function isInt(int $base = null): bool;

    public function matched(): bool;

    public function equals(string $expected): bool;

    public function name(): ?string;

    /**
     * @return int|string
     */
    public function usedIdentifier();

    public function offset(): int;

    public function tail(): int;

    public function byteOffset(): int;

    public function byteTail(): int;

    /**
     * @deprecated
     */
    public function substitute(string $replacement): string;

    public function subject(): string;

    public function all(): array;
}
