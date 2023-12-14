<?php

declare(strict_types=1);

namespace AppCore\Domain\PaymentIntent;

use DateTimeImmutable;

final class PaymentIntent
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /**
     * @param int<1, max> $orderId
     * @param int<1, max> $billingId
     * @param int<1, max> $paymentMethodId
     * @param int<1, max> $chargeAmount
     * @param int<0, max> $captureAmount
     * @param int<0, max> $id
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        public readonly int $orderId,
        public readonly int $billingId,
        public readonly int $paymentMethodId,
        public readonly string $idempotencyToken,
        public readonly PaymentGateway $gateway,
        public readonly int $chargeAmount,
        public readonly int $captureAmount = 0,
        public readonly string|null $authorization = null,
        public readonly DateTimeImmutable|null $authorizedDate = null,
        public readonly PaymentIntentStatus $status = PaymentIntentStatus::UNPAID,
        public readonly int $id = self::ID0,
    ) {
    }

    /**
     * @param int<1, max> $orderId
     * @param int<1, max> $billingId
     * @param int<1, max> $paymentMethodId
     * @param int<1, max> $chargeAmount
     * @param int<0, max> $captureAmount
     * @param int<1, max> $id
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public static function reconstruct(
        int $id,
        int $orderId,
        int $billingId,
        int $paymentMethodId,
        string $idempotencyToken,
        PaymentGateway $gateway,
        int $chargeAmount,
        int $captureAmount,
        string|null $authorization,
        DateTimeImmutable|null $authorizedDate,
        PaymentIntentStatus $status,
    ): PaymentIntent {
        return new self(
            $orderId,
            $billingId,
            $paymentMethodId,
            $idempotencyToken,
            $gateway,
            $chargeAmount,
            $captureAmount,
            $authorization,
            $authorizedDate,
            $status,
            $id,
        );
    }

    /** @return int<1, max>|null */
    public function getNewId(): int|null
    {
        return $this->newId;
    }

    /** @param int<1, max> $newId */
    public function setNewId(int $newId): void
    {
        $this->newId = $newId;
    }

    /** @param int<0, max> $captureAmount */
    private function changeStatus(
        PaymentIntentStatus $status,
        int $captureAmount,
        string|null $authorization,
        DateTimeImmutable|null $authorizedDate,
    ): self {
        return new self(
            $this->orderId,
            $this->billingId,
            $this->paymentMethodId,
            $this->idempotencyToken,
            $this->gateway,
            $this->chargeAmount,
            $captureAmount,
            $authorization,
            $authorizedDate,
            $this->status->transitionTo($status),
            $this->id,
        );
    }

    public function due(): self
    {
        return $this->changeStatus(
            PaymentIntentStatus::DUE,
            0,
            null,
            null,
        );
    }

    public function declined(): self
    {
        return $this->changeStatus(
            PaymentIntentStatus::DECLINED,
            0,
            null,
            null,
        );
    }

    /** @param int<0, max> $captureAmount */
    public function paid(int $captureAmount): self
    {
        $newCaptureAmount = $this->captureAmount + $captureAmount;

        return $this->changeStatus(
            $this->chargeAmount === $newCaptureAmount ? PaymentIntentStatus::PAID : $this->status,
            $newCaptureAmount,
            $this->authorization,
            $this->authorizedDate === null ? null : clone $this->authorizedDate,
        );
    }

    public function authorized(
        string $authorization,
        DateTimeImmutable $authorizedDate,
    ): self {
        return $this->changeStatus(
            PaymentIntentStatus::AUTHORIZED,
            0,
            $authorization,
            $authorizedDate,
        );
    }

    public function expired(): self
    {
        return $this->changeStatus(
            PaymentIntentStatus::EXPIRED,
            0,
            null,
            null,
        );
    }

    public function holdOn(): self
    {
        return $this->changeStatus(
            PaymentIntentStatus::HOLD_ON,
            0,
            null,
            null,
        );
    }

    public function voided(): self
    {
        return $this->changeStatus(
            PaymentIntentStatus::VOIDED,
            0,
            null,
            null,
        );
    }

    public function refunded(): self
    {
        return $this->changeStatus(
            PaymentIntentStatus::REFUNDED,
            $this->captureAmount,
            $this->authorization,
            $this->authorizedDate === null ? null : clone $this->authorizedDate,
        );
    }
}
