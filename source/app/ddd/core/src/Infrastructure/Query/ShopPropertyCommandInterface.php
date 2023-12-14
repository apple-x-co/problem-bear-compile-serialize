<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface ShopPropertyCommandInterface
{
    /**
     * @param int<1, max> $shopId
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('shop_property_add', type: 'row')]
    public function add(
        int $shopId,
        string $name,
        string $value,
    ): array;

    /**
     * @param int<1, max> $shopId
     *
     * @return array{row_count: int}
     */
    #[DbQuery('shop_property_update', type: 'row')]
    public function update(int $shopId, string $name, string $value): array;
}
