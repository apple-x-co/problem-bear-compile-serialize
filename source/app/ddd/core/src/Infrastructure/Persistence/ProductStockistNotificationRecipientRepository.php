<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\ProductStockistNotificationRecipient\ProductStockistNotificationRecipient;
use AppCore\Domain\ProductStockistNotificationRecipient\ProductStockistNotificationRecipientRepositoryInterface;
use AppCore\Infrastructure\Query\ProductStockistNotificationRecipientCommandInterface;
use AppCore\Infrastructure\Query\ProductStockistNotificationRecipientQueryInterface;

use function array_map;
use function assert;

/**
 * @psalm-import-type ProductStockistNotificationRecipientItem from ProductStockistNotificationRecipientQueryInterface
 * @SuppressWarnings(PHPMD.LongClassName)
 */
class ProductStockistNotificationRecipientRepository implements
    ProductStockistNotificationRecipientRepositoryInterface
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        private readonly ProductStockistNotificationRecipientCommandInterface $productStockistNotificationRecipientCommand,
        private readonly ProductStockistNotificationRecipientQueryInterface $productStockistNotificationRecipientQuery,
    ) {
    }

    /**
     * @param int<1, max> $productId
     *
     * @return list<ProductStockistNotificationRecipient>
     */
    public function findByProductId(int $productId): array
    {
        $items = $this->productStockistNotificationRecipientQuery->listByProductId($productId);

        return array_map(
            fn (array $item): ProductStockistNotificationRecipient => $this->toModel($item),
            $items,
        );
    }

    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function insert(ProductStockistNotificationRecipient $productStockistNotificationRecipient): void
    {
        $result = $this->productStockistNotificationRecipientCommand->add(
            $productStockistNotificationRecipient->productId,
            $productStockistNotificationRecipient->email,
        );

        $productStockistNotificationRecipient->setNewId($result['id']);
    }

    /**
     * @psalm-param ProductStockistNotificationRecipientItem $item
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    // phpcs:ignore Squiz.Commenting.FunctionComment.MissingParamName
    private function toModel(array $item): ProductStockistNotificationRecipient
    {
        $id = (int) $item['id'];
        assert($id > 0);

        $productId = (int) $item['product_id'];
        assert($productId > 0);

        return ProductStockistNotificationRecipient::reconstruct(
            $id,
            $productId,
            $item['email'],
        );
    }
}
