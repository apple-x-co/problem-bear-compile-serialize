<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type DiscountCodeActivityItem = array{
 *     id: string,
 *      store_id: string,
 *      code: string,
 *      customer_id: string|null,
 *      email: string,
 *      phone_number: string,
 *      used_date: string,
 * }
 */
interface DiscountCodeActivityQueryInterface
{
    /**
     * @param int<1, max>      $storeId
     * @param int<1, max>|null $customerId
     *
     * @psalm-return list<DiscountCodeActivityItem>
     */
    #[DbQuery('discount_code_activity_list_by_store_customer_code')]
    public function listByStoreCustomerCode(
        int $storeId,
        int|null $customerId,
        string $mail,
        string $phoneNumber,
        string $code,
    ): array;
}
