<?php

declare(strict_types=1);

namespace AppCore\Domain\StoreUsage;

use DateTimeImmutable;

final class StoreUsageBilling
{
    /**
     * @param int<1, max> $chargeAmount
     * @param int<0, max> $id
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        public readonly int $chargeAmount,
        public readonly DateTimeImmutable $billingDate,
        public readonly DateTimeImmutable $scheduledPaymentDate,
        public readonly DateTimeImmutable $dueDate,
        public readonly string $familyName,
        public readonly string $givenName,
        public readonly string $phoneticFamilyName,
        public readonly string $phoneticGivenName,
        public readonly string $postalCode,
        public readonly string $state,
        public readonly string $city,
        public readonly string $addressLine1,
        public readonly string $addressLine2,
        public readonly string $phoneNumber,
        public readonly string $email,
        public readonly DateTimeImmutable|null $paidDate = null,
        public readonly StoreUsageBillingStatus $status = StoreUsageBillingStatus::DRAFT,
        public readonly int $id = 0,
    ) {
    }

    /**
     * @param int<1, max> $chargeAmount
     * @param int<1, max> $id
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public static function reconstruct(
        int $id,
        int $chargeAmount,
        DateTimeImmutable $billingDate,
        DateTimeImmutable $scheduledPaymentDate,
        DateTimeImmutable $dueDate,
        DateTimeImmutable|null $paidDate,
        string $familyName,
        string $givenName,
        string $phoneticFamilyName,
        string $phoneticGivenName,
        string $postalCode,
        string $state,
        string $city,
        string $addressLine1,
        string $addressLine2,
        string $phoneNumber,
        string $email,
        StoreUsageBillingStatus $status,
    ): StoreUsageBilling {
        return new self(
            $chargeAmount,
            $billingDate,
            $scheduledPaymentDate,
            $dueDate,
            $familyName,
            $givenName,
            $phoneticFamilyName,
            $phoneticGivenName,
            $postalCode,
            $state,
            $city,
            $addressLine1,
            $addressLine2,
            $phoneNumber,
            $email,
            $paidDate,
            $status,
            $id,
        );
    }

    private function changeStatus(DateTimeImmutable|null $paidDate, StoreUsageBillingStatus $status): self
    {
        return new self(
            $this->chargeAmount,
            clone $this->billingDate,
            clone $this->scheduledPaymentDate,
            clone $this->dueDate,
            $this->familyName,
            $this->givenName,
            $this->phoneticFamilyName,
            $this->phoneticGivenName,
            $this->postalCode,
            $this->state,
            $this->city,
            $this->addressLine1,
            $this->addressLine2,
            $this->phoneNumber,
            $this->email,
            $paidDate,
            $this->status->transitionTo($status),
            $this->id,
        );
    }

    public function open(): self
    {
        return $this->changeStatus(
            null,
            $this->status->transitionTo(StoreUsageBillingStatus::OPEN),
        );
    }

    public function paid(): self
    {
        return $this->changeStatus(
            new DateTimeImmutable(),
            $this->status->transitionTo(StoreUsageBillingStatus::PAID),
        );
    }

    public function void(): self
    {
        return $this->changeStatus(
            null,
            $this->status->transitionTo(StoreUsageBillingStatus::VOID),
        );
    }
}
