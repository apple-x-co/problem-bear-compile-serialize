<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type MakerItem = array{
 *      id: string,
 *      store_id: string,
 *      parent_id: string,
 *      name: string,
 *      position: string,
 *  }
 */
interface MakerQueryInterface
{
    /**
     * @param int<1, max> $id
     *
     * @psalm-return MakerItem|null
     */
    #[DbQuery('maker_item', 'row')]
    public function item(int $id): array|null;

    /**
     * @param int<1, max> $storeId
     *
     * @psalm-return list<MakerItem>
     */
    #[DbQuery('maker_list_by_store_id')]
    public function listByStoreId(int $storeId): array;
}
