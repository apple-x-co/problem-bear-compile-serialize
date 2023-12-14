<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface StoreImageCommandInterface
{
    /**
     * @param int<1, max> $storeId
     * @param int<1, max> $size
     * @param int<1, max> $width
     * @param int<1, max> $height
     * @param int<1, max> $position
     *
     * @return array{id: int<1, max>}
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    #[DbQuery('product_image_add', type: 'row')]
    public function add(
        int $storeId,
        string $group,
        int $size,
        string $mediaType,
        int $width,
        int $height,
        string $originalFileName,
        string $url,
        string $path,
        string $fileName,
        string $md5,
        int $position,
    ): array;

    /**
     * @param int<1, max> $id
     * @param int<1, max> $position
     */
    #[DbQuery('store_image_update', type: 'row')]
    public function update(int $id, int $position): void;

    /**
     * @param int<1, max>       $storeId
     * @param list<int<1, max>> $aliveIds
     */
    #[DbQuery('store_image_delete_old', type: 'row')]
    public function deleteOld(int $storeId, string $group, array $aliveIds): void;
}
