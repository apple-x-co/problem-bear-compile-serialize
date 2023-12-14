<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type ShopItem = array{
 *     id: string,
 *     company_id: string,
 *     area_id: string,
 *     name: string,
 *     position: string,
 * }
 */
interface ShopQueryInterface
{
    /**
     * @param int<1, max> $id
     *
     * @return ShopItem|null
     */
    #[DbQuery('shop_item', type: 'row')]
    public function item(int $id): array|null;

    /**
     * @param int<1, max> $companyId
     *
     * @return list<ShopItem>
     */
    #[DbQuery('shop_list_by_company_id')]
    public function listByCompanyId(int $companyId): array;
}
