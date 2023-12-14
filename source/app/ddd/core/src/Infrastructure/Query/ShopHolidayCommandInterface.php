<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface ShopHolidayCommandInterface
{
    /**
     * @param int<1, max> $shopId
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('shop_holiday_add', type: 'row')]
    public function add(int $shopId, DateTimeImmutable $date): array;

    /**
     * @param int<1, max>       $shopId
     * @param list<int<1, max>> $aliveIds
     */
    #[DbQuery('shop_holiday_delete_old', type: 'row')]
    public function deleteOld(int $shopId, array $aliveIds): void;
}
