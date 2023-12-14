<?php

declare(strict_types=1);

namespace AppCore\Domain\DiscountCodeActivity;

interface DiscountCodeActivityRepositoryInterface
{
    /**
     * @param int<1, max>      $storeId
     * @param int<1, max>|null $customerId
     *
     * @return list<DiscountCodeActivity>
     */
    public function findByStoreCustomerCode(
        int $storeId,
        int|null $customerId,
        string $mail,
        string $phoneNumber,
        string $code,
    ): array;

    public function add(DiscountCodeActivity $discountCodeActivity): void;
}
