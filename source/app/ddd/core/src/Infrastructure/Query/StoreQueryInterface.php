<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type StoreItem = array{
 *     id: string,
 *     key: string,
 *     url: string,
 *     name: string,
 *     status: string,
 *     leave_date: string|null,
 *     void_date: string|null,
 * }
 */
interface StoreQueryInterface
{
    /**
     * @param int<1, max> $id
     *
     * @return StoreItem|null
     */
    #[DbQuery('store_item', type: 'row')]
    public function item(int $id): array|null;

    /** @psalm-return StoreItem|null */
    #[DbQuery('store_item_by_key', type: 'row')]
    public function itemByKey(string $key): array|null;
}
