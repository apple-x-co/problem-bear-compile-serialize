<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type PaymentMethodItem = array{
 *      id: string,
 *      name: string,
 *      key: string,
 *      role: string,
 *      fee: string,
 *      available: string,
 *      position: string,
 *  }
 */
interface PaymentMethodQueryInterface
{
    /** @psalm-return PaymentMethodItem|null */
    #[DbQuery('payment_method_item', type: 'row')]
    public function item(int $id): array|null;

    /** @psalm-return list<PaymentMethodItem> */
    #[DbQuery('payment_method_list_for_seller')]
    public function listForSeller(): array;

    /** @psalm-return list<PaymentMethodItem> */
    #[DbQuery('payment_method_list_for_consumer')]
    public function listForConsumer(): array;
}
