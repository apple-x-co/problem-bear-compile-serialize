<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type CustomerItem = array{
 *     id: string,
 *      family_name: string,
 *      given_name: string,
 *      phonetic_family_name: string,
 *      phonetic_given_name: string,
 *      gender_type: string,
 *      phone_number: string,
 *      email: string|null,
 *      password: string,
 *      joined_date: string,
 *      status: string,
 * }
 */
interface CustomerQueryInterface
{
    /** @psalm-return CustomerItem|null */
    #[DbQuery('customer_item', type: 'row')]
    public function item(int $id): array|null;

    /** @psalm-return CustomerItem|null */
    #[DbQuery('customer_item_by_email', type: 'row')]
    public function itemByEmail(string $email): array|null;

    /** @return array{count: int} */
    #[DbQuery('customer_count_by_verified_email', type: 'row')]
    public function countByVerifiedEmail(string $email): array;
}
