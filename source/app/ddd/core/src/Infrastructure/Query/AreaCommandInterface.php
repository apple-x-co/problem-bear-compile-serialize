<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface AreaCommandInterface
{
    /**
     * @param int<1, max> $companyId
     * @param int<1, max> $position
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('area_add', 'row')]
    public function add(
        int $companyId,
        string $name,
        int $position,
    ): array;

    /**
     * @param int<1, max> $id
     * @param int<1, max> $position
     */
    #[DbQuery('area_update', 'row')]
    public function update(
        int $id,
        string $name,
        int $position,
    ): void;
}
