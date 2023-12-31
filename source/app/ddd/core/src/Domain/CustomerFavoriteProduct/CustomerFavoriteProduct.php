<?php

declare(strict_types=1);

namespace AppCore\Domain\CustomerFavoriteProduct;

use DateTimeImmutable;

final class CustomerFavoriteProduct
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /**
     * @param int<1, max> $customerId
     * @param int<1, max> $storeId
     * @param int<1, max> $productId
     * @param int<0, max> $id
     */
    public function __construct(
        public readonly int $customerId,
        public readonly int $storeId,
        public readonly int $productId,
        public readonly DateTimeImmutable $favoritedDate,
        public readonly int $id = self::ID0,
    ) {
    }

    /**
     * @param int<1, max> $customerId
     * @param int<1, max> $storeId
     * @param int<1, max> $productId
     * @param int<1, max> $id
     */
    public static function reconstruct(
        int $id,
        int $customerId,
        int $storeId,
        int $productId,
        DateTimeImmutable $favoriteDate,
    ): CustomerFavoriteProduct {
        return new self(
            $customerId,
            $storeId,
            $productId,
            $favoriteDate,
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
