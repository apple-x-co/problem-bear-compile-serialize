<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\ShopNotificationRecipient\ShopNotificationRecipient;
use AppCore\Domain\ShopNotificationRecipient\ShopNotificationRecipientRepositoryInterface;
use AppCore\Domain\ShopNotificationRecipient\ShopNotificationRecipientType;
use AppCore\Infrastructure\Query\ShopNotificationRecipientCommandInterface;
use AppCore\Infrastructure\Query\ShopNotificationRecipientQueryInterface;

use function array_map;
use function assert;

/**
 * @psalm-import-type ShopNotificationRecipientItem from ShopNotificationRecipientQueryInterface
 * @SuppressWarnings(PHPMD.LongClassName)
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ShopNotificationRecipientRepository implements ShopNotificationRecipientRepositoryInterface
{
    public function __construct(
        private readonly ShopNotificationRecipientCommandInterface $shopNotificationRecipientCommand,
        private readonly ShopNotificationRecipientQueryInterface $shopNotificationRecipientQuery,
    ) {
    }

    /**
     * @param int<1, max> $shopId
     *
     * @return list<ShopNotificationRecipient>
     */
    public function findByShopId(int $shopId): array
    {
        $items = $this->shopNotificationRecipientQuery->listByShopId($shopId);

        return array_map(
            fn (array $item) => $this->toModel($item),
            $items,
        );
    }

    /** @psalm-param ShopNotificationRecipientItem $item */
    private function toModel(array $item): ShopNotificationRecipient
    {
        $id = (int) $item['id'];
        assert($id > 0);

        $shopId = (int) $item['shop_id'];
        assert($shopId > 0);

        return ShopNotificationRecipient::reconstruct(
            $id,
            $shopId,
            ShopNotificationRecipientType::from($item['type']),
            $item['email'],
        );
    }

    public function insert(ShopNotificationRecipient $shopNotificationRecipient): void
    {
        $result = $this->shopNotificationRecipientCommand->add(
            $shopNotificationRecipient->shopId,
            $shopNotificationRecipient->type->value,
            $shopNotificationRecipient->email,
        );

        $shopNotificationRecipient->setNewId($result['id']);
    }

    public function delete(ShopNotificationRecipient $shopNotificationRecipient): void
    {
        if (empty($shopNotificationRecipient->id)) {
            return;
        }

        $this->shopNotificationRecipientCommand->delete($shopNotificationRecipient->id);
    }
}
