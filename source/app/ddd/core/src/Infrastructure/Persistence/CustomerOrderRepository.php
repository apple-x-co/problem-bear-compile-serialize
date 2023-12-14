<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\CustomerOrder\CustomerOrder;
use AppCore\Domain\CustomerOrder\CustomerOrderNotFoundException;
use AppCore\Domain\CustomerOrder\CustomerOrderRepositoryInterface;
use AppCore\Infrastructure\Query\CustomerOrderCommandInterface;
use AppCore\Infrastructure\Query\CustomerOrderQueryInterface;

use function array_map;
use function assert;

/** @psalm-import-type CustomerOrderItem from CustomerOrderQueryInterface */
class CustomerOrderRepository implements CustomerOrderRepositoryInterface
{
    public function __construct(
        private readonly CustomerOrderCommandInterface $customerOrderCommand,
        private readonly CustomerOrderQueryInterface $customerOrderQuery,
    ) {
    }

    public function findByOrderId(int $orderId): CustomerOrder
    {
        $item = $this->customerOrderQuery->itemByOrderId($orderId);
        if ($item === null) {
            throw new CustomerOrderNotFoundException();
        }

        return $this->toModel($item);
    }

    /**
     * @param int<1, max> $storeId
     * @param int<1, max> $customerId
     *
     * @return list<CustomerOrder>
     */
    public function findByStoreCustomer(int $storeId, int $customerId): array
    {
        $items = $this->customerOrderQuery->listByStoreCustomer($storeId, $customerId);

        return array_map(
            fn (array $item) => $this->toModel($item),
            $items,
        );
    }

    public function insert(CustomerOrder $customerOrder): void
    {
        $result = $this->customerOrderCommand->add(
            $customerOrder->customerId,
            $customerOrder->storeId,
            $customerOrder->orderId,
        );

        $customerOrder->setNewId($result['id']);
    }

    /**
     * @psalm-param CustomerOrderItem $item
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    // phpcs:ignore Squiz.Commenting.FunctionComment.MissingParamName
    private function toModel(array $item): CustomerOrder
    {
        $id = (int) $item['id'];
        assert($id > 0);

        $customerId = (int) $item['customer_id'];
        assert($customerId > 0);

        $storeId = (int) $item['store_id'];
        assert($storeId > 0);

        $orderId = (int) $item['order_id'];
        assert($orderId > 0);

        return CustomerOrder::reconstruct(
            $id,
            $customerId,
            $storeId,
            $orderId,
        );
    }
}
