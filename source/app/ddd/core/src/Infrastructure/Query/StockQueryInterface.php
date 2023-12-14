<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type StockItem = array{
 *     id: string,
 *     store_id: string,
 *     product_variant_id: string,
 *     idempotency_token: string,
 *     quantity: string,
 * }
 */
interface StockQueryInterface
{
    /**
     * @param int<1, max> $storeId
     *
     * @return list<StockItem>
     */
    #[DbQuery('stock_list_by_store_id')]
    public function listByStoreId(int $storeId): array;
}
