<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface CustomerCommandInterface
{
    /**
     * @return array{id: int<1, max>}
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    #[DbQuery('customer_add', 'row')]
    public function add(
        string $familyName,
        string $givenName,
        string $phoneticFamilyName,
        string $phoneticGivenName,
        string $genderType,
        string $phoneNumber,
        string|null $email,
        string $password,
        DateTimeImmutable $joinedDate,
        string $status,
    ): array;

    /** @SuppressWarnings(PHPMD.ExcessiveParameterList) */
    #[DbQuery('customer_update', 'row')]
    public function update(
        int $id,
        string $familyName,
        string $givenName,
        string $phoneticFamilyName,
        string $phoneticGivenName,
        string $genderType,
        string $phoneNumber,
        string|null $email,
        string $password,
        DateTimeImmutable $joinedDate,
        string $status,
    ): void;
}
