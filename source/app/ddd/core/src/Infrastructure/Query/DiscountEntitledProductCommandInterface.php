<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface DiscountEntitledProductCommandInterface
{
    /**
     * @param int<1, max> $discountCodeId ,
     * @param int<1, max> $productId
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('discount_entitled_product_add', 'row')]
    public function add(
        int $discountCodeId,
        int $productId,
    ): array;

    /** @param list<int<1, max>> $ids */
    #[DbQuery('discount_entitled_product_delete_old', 'row')]
    public function deleteOld(int $discountCodeId, array $ids): void;
}
