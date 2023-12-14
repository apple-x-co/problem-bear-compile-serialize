<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface DiscountCodeCommandInterface
{
    /**
     * @param int<1, max>      $storeId
     * @param int<1, max>      $value
     * @param int<0, max>      $usageCount
     * @param int<0, max>|null $usageLimit
     * @param int<0, max>|null $minimumPrice
     * @param int<0, 1>        $oncePerCustomer
     *
     * @return array{id: int<1, max>}
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    #[DbQuery('discount_code_add', type: 'row')]
    public function add(
        int $storeId,
        string $title,
        string $code,
        string $type,
        int $value,
        DateTimeImmutable|null $startDate,
        DateTimeImmutable|null $endDate,
        int $usageCount,
        int|null $usageLimit,
        int|null $minimumPrice,
        int $oncePerCustomer,
        string $targetSelection,
        string $status,
    ): array;

    /**
     * @param int<1, max>      $id
     * @param int<1, max>      $value
     * @param int<0, max>      $usageCount
     * @param int<0, max>|null $usageLimit
     * @param int<0, max>|null $minimumPrice
     * @param int<0, 1>        $oncePerCustomer
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    #[DbQuery('discount_code_update', type: 'row')]
    public function update(
        int $id,
        string $title,
        string $code,
        string $type,
        int $value,
        DateTimeImmutable|null $startDate,
        DateTimeImmutable|null $endDate,
        int $usageCount,
        int|null $usageLimit,
        int|null $minimumPrice,
        int $oncePerCustomer,
        string $targetSelection,
        string $status,
    ): void;
}
