<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface OrderLineItemCommandInterface
{
    /**
     * @param int<1, max>      $orderId
     * @param int<1, max>      $productId
     * @param int<1, max>      $productVariantId
     * @param int<min, 0>|null $discountPrice
     * @param int<0, max>      $originalPrice
     * @param int<0, max>      $originalTax
     * @param int<0, max>      $originalLinePrice
     * @param int<0, max>      $finalPrice
     * @param int<0, max>      $finalTax
     * @param int<0, max>      $finalLinePrice
     * @param int<0, max>      $taxRate
     * @param int<1, max>      $quantity
     *
     * @return array{id: int<1, max>}
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    #[DbQuery('order_line_item_add')]
    public function add(
        int $orderId,
        int $productId,
        int $productVariantId,
        string $title,
        string $makerName,
        string $taxonomyName,
        int|null $discountPrice,
        int $originalPrice,
        int $originalTax,
        int $originalLinePrice,
        int $finalPrice,
        int $finalTax,
        int $finalLinePrice,
        int $taxRate,
        int $quantity,
    ): array;
}
