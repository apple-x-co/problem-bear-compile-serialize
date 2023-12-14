<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\CustomerStore\CustomerStore;
use AppCore\Domain\CustomerStore\CustomerStoreNotFoundException;
use AppCore\Domain\CustomerStore\CustomerStoreRepositoryInterface;
use AppCore\Infrastructure\Query\CustomerStoreCommandInterface;
use AppCore\Infrastructure\Query\CustomerStoreQueryInterface;
use DateTimeImmutable;

use function assert;

/** @psalm-import-type CustomerStoreItem from CustomerStoreQueryInterface */
class CustomerStoreRepository implements CustomerStoreRepositoryInterface
{
    public function __construct(
        private readonly CustomerStoreCommandInterface $customerStoreCommand,
        private readonly CustomerStoreQueryInterface $customerStoreQuery,
    ) {
    }

    public function findByUniqueKey(int $customerId, int $storeId): CustomerStore
    {
        $item = $this->customerStoreQuery->itemByUniqueKey($customerId, $storeId);
        if ($item === null) {
            throw new CustomerStoreNotFoundException();
        }

        return $this->toModel($item);
    }

    public function insert(CustomerStore $customerStore): void
    {
        $result = $this->customerStoreCommand->add(
            $customerStore->customerId,
            $customerStore->storeId,
            $customerStore->shopId,
            $customerStore->staffMemberId,
            $customerStore->lastOrderDate,
            $customerStore->numberOfOrders,
            $customerStore->numberOfOrderCancels,
            $customerStore->remainingPoint,
            $customerStore->customerNote,
        );

        $customerStore->setNewId($result['id']);
    }

    public function update(CustomerStore $customerStore): void
    {
        if (empty($customerStore->id)) {
            return;
        }

        $this->customerStoreCommand->update(
            $customerStore->id,
            $customerStore->shopId,
            $customerStore->staffMemberId,
            $customerStore->lastOrderDate,
            $customerStore->numberOfOrders,
            $customerStore->numberOfOrderCancels,
            $customerStore->remainingPoint,
            $customerStore->customerNote,
        );
    }

    /**
     * @psalm-param CustomerStoreItem $item
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    // phpcs:ignore Squiz.Commenting.FunctionComment.MissingParamName
    private function toModel(array $item): CustomerStore
    {
        $id = (int) $item['id'];
        assert($id > 0);

        $customerId = (int) $item['customer_id'];
        assert($customerId > 0);

        $storeId = (int) $item['store_id'];
        assert($storeId > 0);

        $shopId = empty($item['shop_id']) ? null : (int) $item['shop_id'];
        assert($shopId === null || $shopId > 0);

        $staffMemberId = empty($item['staff_member_id']) ? null : (int) $item['staff_member_id'];
        assert($staffMemberId === null || $staffMemberId > 0);

        $numberOfOrders = (int) $item['number_of_orders'];
        assert($numberOfOrders > 0);

        $numberOfOrderCancels = (int) $item['number_of_order_cancels'];
        assert($numberOfOrderCancels > 0);

        $remainingPoint = (int) $item['remaining_point'];
        assert($remainingPoint > 0);

        return CustomerStore::reconstruct(
            $id,
            $customerId,
            $storeId,
            $shopId,
            $staffMemberId,
            empty($item['last_order_date']) ? null : new DateTimeImmutable($item['last_order_date']),
            $numberOfOrders,
            $numberOfOrderCancels,
            $remainingPoint,
            $item['customer_note'],
        );
    }
}
