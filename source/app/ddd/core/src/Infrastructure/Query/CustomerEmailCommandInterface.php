<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface CustomerEmailCommandInterface
{
    /**
     * @param int<1, max> $customerId
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('customer_email_add', 'row')]
    public function add(
        int $customerId,
        string $email,
        string $token,
        DateTimeImmutable $expireDate,
        DateTimeImmutable|null $verifiedDate,
    ): array;

    /** @param int<1, max> $id */
    #[DbQuery('customer_email_update', 'row')]
    public function update(
        int $id,
        DateTimeImmutable|null $verifiedDate,
    ): void;
}
