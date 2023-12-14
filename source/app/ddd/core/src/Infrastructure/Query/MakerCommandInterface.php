<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface MakerCommandInterface
{
    /**
     * @param int<1, max>      $storeId
     * @param int<1, max>|null $parentId
     * @param int<1, max>      $position
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('maker_add', type: 'row')]
    public function add(
        int $storeId,
        int|null $parentId,
        string $name,
        int $position,
    ): array;

    /**
     * @param int<1, max>      $id
     * @param int<1, max>|null $parentId
     * @param int<1, max>      $position
     */
    #[DbQuery('maker_update', type: 'row')]
    public function update(
        int $id,
        int|null $parentId,
        string $name,
        int $position,
    ): void;
}
