<?php

declare(strict_types=1);

namespace AppCore\Domain\Admin;

final class Admin
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /** @param int<0, max> $id */
    public function __construct(
        public readonly string $name,
        public readonly string $username,
        public readonly string $password,
        public readonly int $id = self::ID0,
    ) {
    }

    /** @param int<1, max> $id */
    public static function reconstruct(
        int $id,
        string $name,
        string $username,
        string $password,
    ): Admin {
        return new self(
            $name,
            $username,
            $password,
            $id,
        );
    }

    /** @return int<1, max>|null */
    public function getNewId(): int|null
    {
        return $this->newId;
    }

    /** @param int<1, max> $newId */
    public function setNewId(int $newId): void
    {
        $this->newId = $newId;
    }
}
