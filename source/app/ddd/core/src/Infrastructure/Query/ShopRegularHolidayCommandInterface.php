<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface ShopRegularHolidayCommandInterface
{
    /**
     * @param int<1, max> $shopId
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('shop_regular_holiday_add', type: 'row')]
    public function add(int $shopId, string $dayOfWeek): array;

    /**
     * @param int<1, max>       $shopId
     * @param list<int<1, max>> $aliveIds
     */
    #[DbQuery('shop_regular_holiday_delete_old', type: 'row')]
    public function deleteOld(int $shopId, array $aliveIds): void;
}
