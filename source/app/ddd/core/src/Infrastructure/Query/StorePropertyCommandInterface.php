<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface StorePropertyCommandInterface
{
    /**
     * @param int<1, max> $storeId
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('store_property_add', type: 'row')]
    public function add(
        int $storeId,
        string $name,
        string $value,
    ): array;

    /**
     * @param int<1, max> $storeId
     *
     * @return array{row_count: int<0, max>}
     */
    #[DbQuery('store_property_update', type: 'row')]
    public function update(
        int $storeId,
        string $name,
        string $value,
    ): array;
}
