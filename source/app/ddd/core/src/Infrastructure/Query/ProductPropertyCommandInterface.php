<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface ProductPropertyCommandInterface
{
    /**
     * @param int<1, max> $productId
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('product_property_add', type: 'row')]
    public function add(
        int $productId,
        string $name,
        string $value,
    ): array;

    /**
     * @param int<1, max> $productId
     *
     * @return array{row_count: int<0, max>}
     */
    #[DbQuery('product_property_update', type: 'row')]
    public function update(
        int $productId,
        string $name,
        string $value,
    ): array;
}
