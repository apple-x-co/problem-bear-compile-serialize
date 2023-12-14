<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface ShopCommandInterface
{
    /**
     * @param int<1, max> $companyId
     * @param int<1, max> $areaId
     * @param int<1, max> $position
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('shop_add', type: 'row')]
    public function add(
        int $companyId,
        int $areaId,
        string $name,
        int $position,
    ): array;

    /**
     * @param int<1, max> $id
     * @param int<1, max> $areaId
     * @param int<1, max> $position
     */
    #[DbQuery('shop_update', type: 'row')]
    public function update(
        int $id,
        int $areaId,
        string $name,
        int $position,
    ): void;
}
