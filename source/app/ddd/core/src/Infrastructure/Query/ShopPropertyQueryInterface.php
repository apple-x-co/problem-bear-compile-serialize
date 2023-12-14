<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type ShopPropertyItem = array{
 *     id: string,
 *     shop_id: string,
 *     name: string,
 *     value: string,
 * }
 */
interface ShopPropertyQueryInterface
{
    /**
     * @param int<1, max> $companyId
     *
     * @return list<ShopPropertyItem>
     */
    #[DbQuery('shop_property_list_by_company_id')]
    public function listByCompanyId(int $companyId): array;

    /**
     * @param int<1, max> $shopId
     *
     * @return list<ShopPropertyItem>
     */
    #[DbQuery('shop_property_list_by_shop_id')]
    public function listByShopId(int $shopId): array;
}
