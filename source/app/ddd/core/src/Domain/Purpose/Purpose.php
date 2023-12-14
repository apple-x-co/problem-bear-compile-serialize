<?php

declare(strict_types=1);

namespace AppCore\Domain\Purpose;

final class Purpose
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /**
     * @param int<1, max>      $storeId
     * @param int<1, max>|null $parentId
     * @param int<1, max>      $position
     * @param int<0, max>      $id
     */
    public function __construct(
        public readonly int $storeId,
        public readonly string $name,
        public readonly int $position,
        public readonly int|null $parentId = null,
        public readonly int $id = self::ID0,
    ) {
    }

    /**
     * @param int<1, max>      $storeId
     * @param int<1, max>|null $parentId
     * @param int<1, max>      $position
     * @param int<1, max>      $id
     */
    public static function reconstruct(
        int $id,
        int $storeId,
        string $name,
        int $position,
        int|null $parentId,
    ): Purpose {
        return new self(
            $storeId,
            $name,
            $position,
            $parentId,
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

    /** @param int<1, max> $position */
    public function changePosition(int $position): self
    {
        return new self(
            $this->storeId,
            $this->name,
            $position,
            $this->parentId,
            $this->id,
        );
    }
}
