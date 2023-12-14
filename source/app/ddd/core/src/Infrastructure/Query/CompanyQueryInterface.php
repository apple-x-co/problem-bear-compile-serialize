<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type CompanyItem = array{
 *     id: string,
 *      name: string,
 *      seller_slug: string,
 *      seller_url: string,
 *      consumer_slug: string,
 *      consumer_url: string,
 *      store_id: string|null,
 *      payment_method_id: string|null,
 *      status: string,
 *      leave_date: string|null,
 *      void_date: string|null,
 * }
 */
interface CompanyQueryInterface
{
    /** @psalm-return CompanyItem|null */
    #[DbQuery('company_item', type: 'row')]
    public function item(int $id): array|null;
}
