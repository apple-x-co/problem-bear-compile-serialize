<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type CompanyPropertyItem = array{
 *     id: string,
 *     company_id: string,
 *     name: string,
 *     value: string,
 * }
 */
interface CompanyPropertyQueryInterface
{
    /**
     * @param int<1, max> $companyId
     *
     * @psalm-return list<CompanyPropertyItem>
     */
    #[DbQuery('company_property_list_by_company_id')]
    public function listByCompanyId(int $companyId): array;
}
