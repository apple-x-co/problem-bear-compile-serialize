<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface CompanyPropertyCommandInterface
{
    /**
     * @param int<1, max> $companyId
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('company_property_add', 'row')]
    public function add(
        int $companyId,
        string $name,
        string $value,
    ): array;

    /**
     * @param int<1, max> $companyId
     *
     * @return array{row_count: int<0, max>}
     */
    #[DbQuery('company_property_update', 'row')]
    public function update(
        int $companyId,
        string $name,
        string $value,
    ): array;
}
