<?php

declare(strict_types=1);

namespace AppCore\Domain\StoreFeePaymentIntent;

/** @SuppressWarnings(PHPMD.LongVariable) */
interface StoreFeePaymentIntentRepositoryInterface
{
    /** @param int<1, max> $id */
    public function findById(int $id): StoreFeePaymentIntent;

    /**
     * @param int<1, max> $storeId
     *
     * @return list<StoreFeePaymentIntent>
     */
    public function findByStoreId(int $storeId): array;

    /**
     * @param int<1, max> $storeUsageId
     *
     * @return list<StoreFeePaymentIntent>
     */
    public function findByStoreUsageId(int $storeUsageId): array;

    public function insert(StoreFeePaymentIntent $storeFeePaymentIntent): void;

    public function update(StoreFeePaymentIntent $storeFeePaymentIntent): void;
}
