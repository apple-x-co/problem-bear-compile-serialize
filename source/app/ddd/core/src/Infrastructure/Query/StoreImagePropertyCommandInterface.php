<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface StoreImagePropertyCommandInterface
{
    /**
     * @param int<1, max> $storeImageId
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('store_image_property_add', type: 'row')]
    public function add(
        int $storeImageId,
        string $name,
        string $value,
    ): array;

    /**
     * @param int<1, max> $storeImageId
     *
     * @return array{row_count: int<0, max>}
     */
    #[DbQuery('store_image_property_update', type: 'row')]
    public function update(
        int $storeImageId,
        string $name,
        string $value,
    ): array;
}
