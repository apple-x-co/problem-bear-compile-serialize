<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type ShopNotificationRecipientItem = array{
 *     id: string,
 *     shop_id: string,
 *     type: string,
 *     email: string,
 * }
 */
interface ShopNotificationRecipientQueryInterface
{
    /**
     * @param int<1, max> $shopId
     *
     * @return list<ShopNotificationRecipientItem>
     */
    #[DbQuery('shop_notification_recipient_list_by_shop_id', type: 'row')]
    public function listByShopId(int $shopId): array;
}
