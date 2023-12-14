<?php

declare(strict_types=1);

namespace AppCore\Domain\Product;

final class ProductMaker
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /**
     * @param int<1, max> $makerId
     * @param int<1, max> $position
     * @param int<0, max> $id
     */
    public function __construct(
        public readonly int $makerId,
        public readonly int $position,
        public readonly int $id = self::ID0,
    ) {
    }

    /**
     * @param int<1, max> $makerId
     * @param int<1, max> $position
     * @param int<1, max> $id
     */
    public static function reconstruct(
        int $id,
        int $makerId,
        int $position,
    ): ProductMaker {
        return new self(
            $makerId,
            $position,
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
