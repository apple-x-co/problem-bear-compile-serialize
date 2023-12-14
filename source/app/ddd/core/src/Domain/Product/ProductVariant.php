<?php

declare(strict_types=1);

namespace AppCore\Domain\Product;

final class ProductVariant
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /**
     * @param int<0, max>      $originalPrice
     * @param int<0, max>      $price
     * @param int<min, 0>|null $discountPrice
     * @param int<0, max>      $pickupDurationDays
     * @param int<0, max>      $id
     */
    public function __construct(
        public readonly string $title,
        public readonly string $code,
        public readonly string $sku,
        public readonly int $originalPrice,
        public readonly int $price,
        public readonly int|null $discountPrice,
        public readonly int $pickupDurationDays,
        public readonly int $id = self::ID0,
    ) {
    }

    /**
     * @param int<0, max>      $originalPrice
     * @param int<0, max>      $price
     * @param int<min, 0>|null $discountPrice
     * @param int<0, max>      $pickupDurationDays
     * @param int<1, max>      $id
     */
    public static function reconstruct(
        int $id,
        string $title,
        string $code,
        string $sku,
        int $originalPrice,
        int $price,
        int|null $discountPrice,
        int $pickupDurationDays,
    ): ProductVariant {
        return new self(
            $title,
            $code,
            $sku,
            $originalPrice,
            $price,
            $discountPrice,
            $pickupDurationDays,
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
