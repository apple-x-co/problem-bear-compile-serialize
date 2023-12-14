<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type TaxItem = array{
 *     id: string,
 *     name: string,
 *     rate: string,
 * }
 */
interface TaxQueryInterface
{
    /**
     * @param int<1, max> $id
     *
     * @return TaxItem|null
     */
    #[DbQuery('tax_item', type: 'row')]
    public function item(int $id): array|null;

    /** @return list<TaxItem> */
    #[DbQuery('tax_list', type: 'row')]
    public function list(): array;
}
