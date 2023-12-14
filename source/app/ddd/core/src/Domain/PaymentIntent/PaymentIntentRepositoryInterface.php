<?php

declare(strict_types=1);

namespace AppCore\Domain\PaymentIntent;

interface PaymentIntentRepositoryInterface
{
    /** @param int<1, max> $orderId */
    public function findByOrderId(int $orderId): PaymentIntent;

    public function insert(PaymentIntent $paymentIntent): void;

    public function update(PaymentIntent $paymentIntent): void;
}
