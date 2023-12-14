<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface TaxonomyCommandInterface
{
    /**
     * @param int<1, max>      $storeId
     * @param int<1, max>|null $parentId
     * @param int<1, max>      $position
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('taxonomy_add', type: 'row')]
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
    #[DbQuery('taxonomy_update', type: 'row')]
    public function update(
        int $id,
        int|null $parentId,
        string $name,
        int $position,
    ): void;
}
