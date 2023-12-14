<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type DiscountEntitledProductItem = array{
 *     id: string,
 *      discount_code_id: string,
 *      product_id: string,
 * }
 */
interface DiscountEntitledProductQueryInterface
{
    /**
     * @param int<1, max> $discountCodeId
     *
     * @psalm-return list<DiscountEntitledProductItem>
     */
    #[DbQuery('discount_entitled_product_list_by_discount_code_id')]
    public function listByDiscountCodeId(int $discountCodeId): array;
}
