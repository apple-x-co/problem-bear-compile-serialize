<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface DiscountCodeActivityCommandInterface
{
    /**
     * @param int<1, max>      $storeId
     * @param int<1, max>|null $customerId
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('discount_code_activity_add', type: 'row')]
    public function add(
        int $storeId,
        string $code,
        int|null $customerId,
        string $email,
        string $phoneNumber,
        DateTimeImmutable $usedDate,
    ): array;
}
