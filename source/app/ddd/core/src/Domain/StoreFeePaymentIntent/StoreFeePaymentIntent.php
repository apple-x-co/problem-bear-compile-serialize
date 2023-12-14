<?php

declare(strict_types=1);

namespace AppCore\Domain\StoreFeePaymentIntent;

use DateTimeImmutable;

final class StoreFeePaymentIntent
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /**
     * @param int<1, max> $storeUsageBillingId
     * @param int<1, max> $paymentMethodId
     * @param int<1, max> $chargeAmount
     * @param int<0, max> $captureAmount
     * @param int<0, max> $refundedAmount
     * @param int<0, max> $id
     *
     * @SuppressWarnings(PHPMD.LongVariable)
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        public readonly int $storeUsageBillingId,
        public readonly int $paymentMethodId,
        public readonly string $idempotencyToken,
        public readonly StoreFeePaymentGateway $gateway,
        public readonly int $chargeAmount,
        public readonly int $captureAmount = 0,
        public readonly int $refundedAmount = 0,
        public readonly string|null $authorization = null,
        public readonly DateTimeImmutable|null $authorizedDate = null,
        public readonly DateTimeImmutable|null $cancelDate = null,
        public readonly StoreFeePaymentIntentStatus $status = StoreFeePaymentIntentStatus::UNPAID,
        public readonly int $id = self::ID0,
    ) {
    }

    /**
     * @param int<1, max> $storeUsageBillingId
     * @param int<1, max> $paymentMethodId
     * @param int<1, max> $chargeAmount
     * @param int<0, max> $captureAmount
     * @param int<0, max> $refundedAmount
     * @param int<1, max> $id
     *
     * @SuppressWarnings(PHPMD.LongVariable)
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public static function reconstruct(
        int $id,
        int $storeUsageBillingId,
        int $paymentMethodId,
        string $idempotencyToken,
        StoreFeePaymentGateway $gateway,
        int $chargeAmount,
        int $captureAmount,
        int $refundedAmount,
        string|null $authorization,
        DateTimeImmutable|null $authorizedDate,
        DateTimeImmutable|null $cancelDate,
        StoreFeePaymentIntentStatus $status,
    ): StoreFeePaymentIntent {
        return new self(
            $storeUsageBillingId,
            $paymentMethodId,
            $idempotencyToken,
            $gateway,
            $chargeAmount,
            $captureAmount,
            $refundedAmount,
            $authorization,
            $authorizedDate,
            $cancelDate,
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

    /**
     * @param int<0, max> $captureAmount
     * @param int<0, max> $refundedAmount
     */
    private function changeStatus(
        StoreFeePaymentIntentStatus $status,
        int $captureAmount,
        int $refundedAmount,
        DateTimeImmutable|null $cancelDate,
        string|null $authorization,
        DateTimeImmutable|null $authorizedDate,
    ): self {
        return new self(
            $this->storeUsageBillingId,
            $this->paymentMethodId,
            $this->idempotencyToken,
            $this->gateway,
            $this->chargeAmount,
            $captureAmount,
            $refundedAmount,
            $authorization,
            $authorizedDate,
            $cancelDate,
            $this->status->transitionTo($status),
            $this->id,
        );
    }

    public function expired(): self
    {
        return $this->changeStatus(
            StoreFeePaymentIntentStatus::EXPIRED,
            $this->captureAmount,
            0,
            null,
            null,
            null,
        );
    }

    public function void(): self
    {
        return $this->changeStatus(
            StoreFeePaymentIntentStatus::VOIDED,
            $this->captureAmount,
            0,
            null,
            null,
            null,
        );
    }

    public function paid(): self
    {
        return $this->changeStatus(
            StoreFeePaymentIntentStatus::PAID,
            $this->chargeAmount,
            0,
            null,
            null,
            null,
        );
    }

    public function authorized(
        string $authorization,
        DateTimeImmutable $authorizedDate,
    ): self {
        return $this->changeStatus(
            StoreFeePaymentIntentStatus::AUTHORIZED,
            $this->captureAmount,
            0,
            null,
            $authorization,
            $authorizedDate,
        );
    }

    public function holdOn(): self
    {
        return $this->changeStatus(
            StoreFeePaymentIntentStatus::HOLD_ON,
            $this->captureAmount,
            0,
            null,
            null,
            null,
        );
    }

    public function refunded(): self
    {
        return $this->changeStatus(
            StoreFeePaymentIntentStatus::REFUNDED,
            $this->captureAmount,
            $this->chargeAmount,
            new DateTimeImmutable(),
            $this->authorization,
            $this->authorizedDate === null ? null : clone $this->authorizedDate,
        );
    }
}
