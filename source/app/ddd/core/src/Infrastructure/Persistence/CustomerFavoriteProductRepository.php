<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\CustomerFavoriteProduct\CustomerFavoriteProduct;
use AppCore\Domain\CustomerFavoriteProduct\CustomerFavoriteProductNotFoundException;
use AppCore\Domain\CustomerFavoriteProduct\CustomerFavoriteProductRepositoryInterface;
use AppCore\Infrastructure\Query\CustomerFavoriteProductCommandInterface;
use AppCore\Infrastructure\Query\CustomerFavoriteProductQueryInterface;
use DateTimeImmutable;

use function array_map;
use function assert;

/** @psalm-import-type CustomerFavoriteItem from CustomerFavoriteProductQueryInterface */
class CustomerFavoriteProductRepository implements CustomerFavoriteProductRepositoryInterface
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        private readonly CustomerFavoriteProductCommandInterface $customerFavoriteCommand,
        private readonly CustomerFavoriteProductQueryInterface $customerFavoriteQuery,
    ) {
    }

    public function findByUniqueKey(int $customerId, int $productId): CustomerFavoriteProduct
    {
        $item = $this->customerFavoriteQuery->itemByUniqueKey($customerId, $productId);
        if ($item === null) {
            throw new CustomerFavoriteProductNotFoundException((string) $customerId);
        }

        return $this->toModel($item);
    }

    /**
     * @param int<1, max> $storeId
     * @param int<1, max> $customerId
     *
     * @return list<CustomerFavoriteProduct>
     */
    public function findByStoreCustomer(int $storeId, int $customerId): array
    {
        $items = $this->customerFavoriteQuery->listByStoreCustomer($storeId, $customerId);

        return array_map(
            fn (array $item) => $this->toModel($item),
            $items,
        );
    }

    public function insert(CustomerFavoriteProduct $customerFavorite): void
    {
        $result = $this->customerFavoriteCommand->add(
            $customerFavorite->customerId,
            $customerFavorite->storeId,
            $customerFavorite->productId,
            $customerFavorite->favoritedDate,
        );

        $customerFavorite->setNewId($result['id']);
    }

    public function delete(CustomerFavoriteProduct $customerFavorite): void
    {
        if (empty($customerFavorite->id)) {
            return;
        }

        $this->customerFavoriteCommand->delete(
            $customerFavorite->id,
            $customerFavorite->customerId,
            $customerFavorite->productId,
        );
    }

    /**
     * @psalm-param CustomerFavoriteItem $item
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    // phpcs:ignore Squiz.Commenting.FunctionComment.MissingParamName
    public function toModel(array $item): CustomerFavoriteProduct
    {
        $id = (int) $item['id'];
        assert($id > 0);

        $customerId = (int) $item['customer_id'];
        assert($customerId > 0);

        $storeId = (int) $item['store_id'];
        assert($storeId > 0);

        $productId = (int) $item['product_id'];
        assert($productId > 0);

        return CustomerFavoriteProduct::reconstruct(
            $id,
            $customerId,
            $storeId,
            $productId,
            new DateTimeImmutable($item['favorited_date']),
        );
    }
}
