<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type CustomerEmailItem = array{
 *     id: string,
 *      customer_id: string,
 *      email: string,
 *      token: string,
 *      expire_date: string,
 *      verified_date: string|null,
 * }
 */
interface CustomerEmailQueryInterface
{
    /** @psalm-return CustomerEmailItem|null */
    #[DbQuery('customer_email_item_by_token', type: 'row')]
    public function itemByToken(string $token): array|null;
}
