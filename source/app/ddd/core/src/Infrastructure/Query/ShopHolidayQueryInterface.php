<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type ShopHolidayItem = array{
 *     id: string,
 *     shop_id: string,
 *     name: string,
 *     date: string,
 * }
 */
interface ShopHolidayQueryInterface
{
    /**
     * @param int<1, max> $companyId
     *
     * @return list<ShopHolidayItem>
     */
    #[DbQuery('shop_holiday_list_by_company_id')]
    public function listByCompanyId(int $companyId): array;

    /**
     * @param int<1, max> $shopId
     *
     * @return list<ShopHolidayItem>
     */
    #[DbQuery('shop_holiday_list_by_shop_id')]
    public function listByShopId(int $shopId): array;
}
