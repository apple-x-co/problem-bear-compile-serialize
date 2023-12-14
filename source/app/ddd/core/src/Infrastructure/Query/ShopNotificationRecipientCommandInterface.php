<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/** @SuppressWarnings(PHPMD.LongClassName) */
interface ShopNotificationRecipientCommandInterface
{
    /**
     * @param int<1, max> $shopId
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('shop_notification_recipient_add', type: 'row')]
    public function add(
        int $shopId,
        string $type,
        string $email,
    ): array;

    /** @param int<1, max> $id */
    #[DbQuery('shop_notification_recipient_delete', type: 'row')]
    public function delete(int $id): void;
}
