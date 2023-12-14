<?php

declare(strict_types=1);

namespace AppCore\Domain\DiscountCode;

interface DiscountCodeRepositoryInterface
{
    /** @param int<1, max> $storeId */
    public function findByUniqueKey(int $storeId, string $code): DiscountCode;

    public function insert(DiscountCode $discountCode): void;

    public function update(DiscountCode $discountCode): void;
}
