<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface PaymentIntentCommandInterface
{
    /**
     * @param int<1, max> $orderId
     * @param int<1, max> $billingId
     * @param int<1, max> $paymentMethodId
     * @param int<1, max> $chargeAmount
     * @param int<0, max> $captureAmount
     *
     * @return array{id: int<1, max>}
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    #[DbQuery('payment_intent_add', 'row')]
    public function add(
        int $orderId,
        int $billingId,
        int $paymentMethodId,
        string $idempotencyToken,
        string $gateway,
        int $chargeAmount,
        int $captureAmount,
        string|null $authorization,
        DateTimeImmutable|null $authorizedDate,
        string $status,
    ): array;

    /**
     * @param int<1, max> $id
     * @param int<0, max> $captureAmount
     */
    #[DbQuery('payment_intent_update', 'row')]
    public function update(
        int $id,
        int $captureAmount,
        string|null $authorization,
        DateTimeImmutable|null $authorizedDate,
        string $status,
    ): void;
}
