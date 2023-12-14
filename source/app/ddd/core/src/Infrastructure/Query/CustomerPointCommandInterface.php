<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface CustomerPointCommandInterface
{
    /**
     * @param int<1, max> $customerRewardId
     * @param int<1, max> $point
     * @param int<0, max> $remainingPoint
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('customer_point_add', 'row')]
    public function add(
        int $customerRewardId,
        string $uuid,
        string $type,
        DateTimeImmutable $transactionDate,
        DateTimeImmutable $expireDate,
        int $point,
        int $remainingPoint,
        DateTimeImmutable|null $invalidationDate,
        string|null $invalidationReason,
    ): array;

    /**
     * @param int<1, max> $id
     * @param int<0, max> $remainingPoint
     */
    #[DbQuery('customer_point_update', 'row')]
    public function update(
        int $id,
        int $remainingPoint,
        DateTimeImmutable|null $invalidationDate,
        string|null $invalidationReason,
    ): void;
}
