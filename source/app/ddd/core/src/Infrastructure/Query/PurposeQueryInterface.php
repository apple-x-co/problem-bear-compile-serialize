<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type PurposeItem = array{
 *      id: string,
 *      store_id: string,
 *      parent_id: string,
 *      name: string,
 *      position: string,
 *  }
 */
interface PurposeQueryInterface
{
    /**
     * @param int<1, max> $id
     *
     * @psalm-return PurposeItem|null
     */
    #[DbQuery('purpose_item', 'row')]
    public function item(int $id): array|null;

    /**
     * @param int<1, max> $storeId
     *
     * @psalm-return list<PurposeItem>
     */
    #[DbQuery('purpose_list_by_store_id')]
    public function listByStoreId(int $storeId): array;
}
