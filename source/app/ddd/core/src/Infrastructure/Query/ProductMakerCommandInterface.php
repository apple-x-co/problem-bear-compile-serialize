<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface ProductMakerCommandInterface
{
    /**
     * @param int<1, max> $productId
     * @param int<1, max> $makerId
     * @param int<1, max> $position
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('product_maker_add', type: 'row')]
    public function add(
        int $productId,
        int $makerId,
        int $position,
    ): array;

    /**
     * @param int<1, max> $id
     * @param int<1, max> $position
     */
    #[DbQuery('product_maker_update', type: 'row')]
    public function update(int $id, int $position): void;

    /** @param list<int<1, max>> $aliveIds */
    #[DbQuery('product_maker_delete_old', type: 'row')]
    public function deleteOld(int $productId, array $aliveIds): void;
}
