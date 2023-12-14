<?php

declare(strict_types=1);

namespace AppCore\Domain\ProductStockistNotificationRecipient;

/** @SuppressWarnings(PHPMD.LongClassName) */
interface ProductStockistNotificationRecipientRepositoryInterface
{
    /**
     * @param int<1, max> $productId
     *
     * @return list<ProductStockistNotificationRecipient>
     */
    public function findByProductId(int $productId): array;

    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function insert(ProductStockistNotificationRecipient $productStockistNotificationRecipient): void;
}
