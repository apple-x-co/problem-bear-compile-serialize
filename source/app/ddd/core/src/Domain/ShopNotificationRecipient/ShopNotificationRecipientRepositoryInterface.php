<?php

declare(strict_types=1);

namespace AppCore\Domain\ShopNotificationRecipient;

/**
 * @SuppressWarnings(PHPMD.LongClassName)
 * @SuppressWarnings(PHPMD.LongVariable)
 */
interface ShopNotificationRecipientRepositoryInterface
{
    /**
     * @param int<1, max> $shopId
     *
     * @return list<ShopNotificationRecipient>
     */
    public function findByShopId(int $shopId): array;

    public function insert(ShopNotificationRecipient $shopNotificationRecipient): void;

    public function delete(ShopNotificationRecipient $shopNotificationRecipient): void;
}
