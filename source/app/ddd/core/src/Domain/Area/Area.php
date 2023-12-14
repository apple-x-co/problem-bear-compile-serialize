<?php

declare(strict_types=1);

namespace AppCore\Domain\Area;

final class Area
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /**
     * @param int<1, max> $companyId
     * @param int<1, max> $position
     * @param int<0, max> $id
     */
    public function __construct(
        public readonly int $companyId,
        public readonly string $name,
        public readonly int $position,
        public readonly int $id = self::ID0,
    ) {
    }

    /**
     * @param int<1, max> $companyId
     * @param int<1, max> $position
     * @param int<1, max> $id
     */
    public static function reconstruct(
        int $id,
        int $companyId,
        string $name,
        int $position,
    ): Area {
        return new self(
            $companyId,
            $name,
            $position,
            $id,
        );
    }

    /** @param int<1, max> $position */
    public function changePosition(int $position): self
    {
        return new self(
            $this->companyId,
            $this->name,
            $position,
            $this->id,
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
