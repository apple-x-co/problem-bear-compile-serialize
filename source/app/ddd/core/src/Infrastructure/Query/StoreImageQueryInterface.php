<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type StoreImageItem = array{
 *       id: string,
 *       store_id: string,
 *       group: string,
 *       size: string,
 *       media_type: string,
 *       width: string,
 *       height: string,
 *       original_file_name: string,
 *       url: string,
 *       path: string,
 *       file_name: string,
 *       md5: string,
 *       position: string,
 *   }
 */
interface StoreImageQueryInterface
{
    /**
     * @param int<1, max> $storeId
     *
     * @return list<StoreImageItem>
     */
    #[DbQuery('store_image_list_by_store_id')]
    public function listByStoreId(int $storeId): array;
}
