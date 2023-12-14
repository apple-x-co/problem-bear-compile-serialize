<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type ShopImageItem = array{
 *       id: string,
 *       shop_id: string,
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
interface ShopImageQueryInterface
{
    /**
     * @param int<1, max> $shopId
     *
     * @return ShopImageItem|null
     */
    #[DbQuery('shop_image_item_by_shop_id', type: 'row')]
    public function itemByShopId(int $shopId): array|null;

    /**
     * @param int<1, max> $companyId
     *
     * @return list<ShopImageItem>
     */
    #[DbQuery('shop_image_list_by_company_id', type: 'row')]
    public function listByCompanyId(int $companyId): array;
}
