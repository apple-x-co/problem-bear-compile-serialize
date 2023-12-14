<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface StoreFeePaymentIntentCommandInterface
{
    /**
     * @param int<1, max> $storeUsageBillingId
     * @param int<1, max> $paymentMethodId
     * @param int<1, max> $chargeAmount
     * @param int<0, max> $captureAmount
     * @param int<0, max> $refundedAmount
     *
     * @return array{id: int<1, max>}
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    #[DbQuery('store_fee_payment_intent_add', type: 'row')]
    public function add(
        int $storeUsageBillingId,
        int $paymentMethodId,
        string $idempotencyToken,
        string $gateway,
        int $chargeAmount,
        int $captureAmount,
        int $refundedAmount,
        string|null $authorization,
        DateTimeImmutable|null $authorizedDate,
        DateTimeImmutable|null $cancelDate,
        string $status,
    ): array;

    /**
     * @param int<0, max> $captureAmount
     * @param int<0, max> $refundedAmount
     */
    #[DbQuery('store_fee_payment_intent_update', type: 'row')]
    public function update(
        int $id,
        string $idempotencyToken,
        int $captureAmount,
        int $refundedAmount,
        string|null $authorization,
        DateTimeImmutable|null $authorizedDate,
        DateTimeImmutable|null $cancelDate,
        string $status,
    ): void;
}
