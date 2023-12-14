<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface ShopImageCommandInterface
{
    /**
     * @param int<1, max> $shopId
     * @param int<1, max> $size
     * @param int<1, max> $width
     * @param int<1, max> $height
     *
     * @return array{id: int<1, max>}
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    #[DbQuery('shop_image_add', type: 'row')]
    public function add(
        int $shopId,
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
    ): array;

    /**
     * @param int<1, max>       $shopId
     * @param list<int<1, max>> $aliveIds
     */
    #[DbQuery('shop_image_delete_old', type: 'row')]
    public function deleteOld(int $shopId, string $group, array $aliveIds): void;
}
