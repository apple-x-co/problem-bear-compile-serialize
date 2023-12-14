<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface CustomerFavoriteProductCommandInterface
{
    /**
     * @param int<1, max> $customerId
     * @param int<1, max> $storeId
     * @param int<1, max> $productId
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('customer_favorite_product_add', 'row')]
    public function add(
        int $customerId,
        int $storeId,
        int $productId,
        DateTimeImmutable $favoritedDate,
    ): array;

    /**
     * @param int<1, max> $id
     * @param int<1, max> $customerId
     * @param int<1, max> $productId
     */
    #[DbQuery('customer_favorite_product_delete', 'row')]
    public function delete(
        int $id,
        int $customerId,
        int $productId,
    ): void;
}
