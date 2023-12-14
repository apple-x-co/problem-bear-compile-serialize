<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface ProductVariantCommandInterface
{
    /**
     * @param int<1, max>      $productId
     * @param int<0, max>      $originalPrice
     * @param int<0, max>      $price
     * @param int<min, 0>|null $discountPrice
     * @param int<0, max>      $pickupDurationDays
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('product_variant_add', type: 'row')]
    public function add(
        int $productId,
        string $title,
        string $code,
        string $sku,
        int $originalPrice,
        int $price,
        int|null $discountPrice,
        int $pickupDurationDays,
    ): array;

    /**
     * @param int<1, max>      $id
     * @param int<0, max>      $originalPrice
     * @param int<0, max>      $price
     * @param int<min, 0>|null $discountPrice
     * @param int<0, max>      $pickupDurationDays
     */
    #[DbQuery('product_variant_update', type: 'row')]
    public function update(
        int $id,
        string $title,
        string $code,
        string $sku,
        int $originalPrice,
        int $price,
        int|null $discountPrice,
        int $pickupDurationDays,
    ): void;

    /** @param list<int<1, max>> $aliveIds */
    #[DbQuery('product_variant_delete_old', 'row')]
    public function deleteOld(int $productId, array $aliveIds): void;
}
