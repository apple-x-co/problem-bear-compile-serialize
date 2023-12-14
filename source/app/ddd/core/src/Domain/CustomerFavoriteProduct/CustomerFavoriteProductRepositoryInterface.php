<?php

declare(strict_types=1);

namespace AppCore\Domain\CustomerFavoriteProduct;

/** @SuppressWarnings(PHPMD.LongClassName) */
interface CustomerFavoriteProductRepositoryInterface
{
    /**
     * @param int<1, max> $customerId
     * @param int<1, max> $productId
     */
    public function findByUniqueKey(int $customerId, int $productId): CustomerFavoriteProduct;

    /**
     * @param int<1, max> $storeId
     * @param int<1, max> $customerId
     *
     * @return list<CustomerFavoriteProduct>
     */
    public function findByStoreCustomer(int $storeId, int $customerId): array;

    public function insert(CustomerFavoriteProduct $customerFavorite): void;

    public function delete(CustomerFavoriteProduct $customerFavorite): void;
}
