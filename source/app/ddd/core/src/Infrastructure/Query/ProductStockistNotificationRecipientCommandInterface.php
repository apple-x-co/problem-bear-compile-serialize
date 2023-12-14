<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/** @SuppressWarnings(PHPMD.LongClassName) */
interface ProductStockistNotificationRecipientCommandInterface
{
    /**
     * @param int<1, max> $productId
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('product_stockist_notification_recipient_add', type: 'row')]
    public function add(
        int $productId,
        string $email,
    ): array;
}
