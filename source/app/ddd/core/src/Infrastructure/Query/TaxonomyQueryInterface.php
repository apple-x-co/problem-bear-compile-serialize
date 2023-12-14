<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type TaxonomyItem = array{
 *      id: string,
 *      store_id: string,
 *      parent_id: string,
 *      name: string,
 *      position: string,
 *  }
 */
interface TaxonomyQueryInterface
{
    /**
     * @param int<1, max> $id
     *
     * @psalm-return TaxonomyItem|null
     */
    #[DbQuery('taxonomy_item', 'row')]
    public function item(int $id): array|null;

    /**
     * @param int<1, max> $storeId
     *
     * @psalm-return list<TaxonomyItem>
     */
    #[DbQuery('taxonomy_list_by_store_id')]
    public function listByStoreId(int $storeId): array;
}
