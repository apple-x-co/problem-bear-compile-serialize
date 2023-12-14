<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface CompanyCommandInterface
{
    /**
     * @param int<1, max>|null $storeId
     * @param int<1, max>|null $paymentMethodId *
     *
     * @return array{id: int<1, max>}
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    #[DbQuery('company_add', 'row')]
    public function add(
        string $name,
        string $sellerSlug,
        string $sellerUrl,
        string $consumerSlug,
        string $consumerUrl,
        int|null $storeId,
        int|null $paymentMethodId,
        string $status,
        DateTimeImmutable|null $leaveDate,
        DateTimeImmutable|null $voidDate,
    ): array;

    /**
     * @param int<1, max>      $id
     * @param int<1, max>|null $storeId
     * @param int<1, max>|null $paymentMethodId
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    #[DbQuery('company_update', 'row')]
    public function update(
        int $id,
        string $name,
        string $sellerSlug,
        string $sellerUrl,
        string $consumerSlug,
        string $consumerUrl,
        int|null $storeId,
        int|null $paymentMethodId,
        string $status,
        DateTimeImmutable|null $leaveDate,
        DateTimeImmutable|null $voidDate,
    ): void;
}
