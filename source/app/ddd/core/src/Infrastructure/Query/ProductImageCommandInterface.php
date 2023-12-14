<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface ProductImageCommandInterface
{
    /**
     * @param int<1, max> $productId
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
        int $productId,
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

    /** @param list<int<1, max>> $aliveIds */
    #[DbQuery('product_image_delete_old', type: 'row')]
    public function deleteOld(int $productId, string $group, array $aliveIds): void;
}
