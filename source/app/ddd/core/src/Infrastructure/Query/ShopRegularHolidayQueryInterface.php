<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type ShopRegularHolidayItem = array{
 *     id: string,
 *     shop_id: string,
 *     day_of_week: string,
 * }
 */
interface ShopRegularHolidayQueryInterface
{
    /**
     * @param int<1, max> $companyId
     *
     * @return list<ShopRegularHolidayItem>
     */
    #[DbQuery('shop_regular_holiday_list_by_company_id')]
    public function listByCompanyId(int $companyId): array;

    /**
     * @param int<1, max> $shopId
     *
     * @return list<ShopRegularHolidayItem>
     */
    #[DbQuery('shop_regular_holiday_list_by_shop_id')]
    public function listByShopId(int $shopId): array;
}
