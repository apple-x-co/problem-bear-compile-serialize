<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type ProductStockistNotificationRecipientItem = array{
 *      id: string,
 *      product_id: string,
 *      email: string,
 *  }
 * @SuppressWarnings(PHPMD.LongClassName)
 */
interface ProductStockistNotificationRecipientQueryInterface
{
    /**
     * @param int<1, max> $productId
     *
     * @psalm-return list<ProductStockistNotificationRecipientItem>
     */
    #[DbQuery('product_stockist_notification_recipient_list_by_product_id')]
    public function listByProductId(int $productId): array;
}
