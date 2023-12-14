<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface FeaturedProductCommandInterface
{
    /**
     * @param int<1, max> $storeId
     * @param int<1, max> $productId
     * @param int<1, max> $position
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('featured_product_add', type: 'row')]
    public function add(
        int $storeId,
        int $productId,
        int $position,
    ): array;

    /**
     * @param int<1, max> $id
     * @param int<1, max> $position
     */
    #[DbQuery('featured_product_update', type: 'row')]
    public function update(
        int $id,
        int $position,
    ): void;
}
