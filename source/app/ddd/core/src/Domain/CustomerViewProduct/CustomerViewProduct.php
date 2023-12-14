<?php

declare(strict_types=1);

namespace AppCore\Domain\CustomerViewProduct;

use DateTimeImmutable;

// TODO: Cookie で productId を保持して、別処理でこのクラスに変換する
final class CustomerViewProduct
{
    /**
     * @param int<1, max> $customerId
     * @param int<1, max> $productId
     * @param int<0, max> $id
     */
    public function __construct(
        public readonly int $customerId,
        public readonly int $productId,
        public readonly DateTimeImmutable $viewedDate,
        public readonly int $id = 0,
    ) {
    }

    /**
     * @param int<1, max> $customerId
     * @param int<1, max> $productId
     * @param int<1, max> $id
     */
    public static function reconstruct(
        int $id,
        int $customerId,
        int $productId,
        DateTimeImmutable $viewedDate,
    ): CustomerViewProduct {
        return new self(
            $customerId,
            $productId,
            $viewedDate,
            $id,
        );
    }
}
