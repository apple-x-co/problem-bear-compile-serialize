<?php

declare(strict_types=1);

namespace AppCore\Domain\FeaturedProduct;

final class FeaturedProduct
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /**
     * @param int<1, max> $storeId
     * @param int<1, max> $productId
     * @param int<1, max> $position
     * @param int<0, max> $id
     */
    public function __construct(
        public readonly int $storeId,
        public readonly int $productId,
        public readonly int $position,
        public readonly int $id = self::ID0,
    ) {
    }

    /**
     * @param int<1, max> $storeId
     * @param int<1, max> $productId
     * @param int<1, max> $position
     * @param int<1, max> $id
     */
    public static function reconstruct(
        int $id,
        int $storeId,
        int $productId,
        int $position,
    ): FeaturedProduct {
        return new self(
            $storeId,
            $productId,
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

    /** @param int<1, max> $position */
    public function changePosition(int $position): self
    {
        return new self(
            $this->storeId,
            $this->productId,
            $position,
            $this->id,
        );
    }
}
