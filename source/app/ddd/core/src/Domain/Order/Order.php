<?php

declare(strict_types=1);

namespace AppCore\Domain\Order;

use DateTimeImmutable;

final class Order
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /**
     * @param int<1, max>       $storeId
     * @param int<min, -1>|null $discountPrice
     * @param int<1, 100>|null  $pointRate
     * @param int<min, 0>|null  $spendingPoint
     * @param int<0, max>|null  $earningPoint
     * @param int<0, max>       $totalPrice
     * @param int<0, max>       $totalTax
     * @param int<0, max>       $subtotalPrice
     * @param int<0, max>       $paymentFee
     * @param int<1, max>       $paymentMethodId
     * @param int<0, max>       $id
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        public readonly int $storeId,
        public readonly string $orderNo,
        public readonly DateTimeImmutable $orderDate,
        public readonly DateTimeImmutable|null $closeDate,
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
        public readonly string|null $discountCode,
        public readonly int|null $discountPrice,
        public readonly int|null $pointRate,
        public readonly int|null $spendingPoint,
        public readonly int|null $earningPoint,
        public readonly int $totalPrice,
        public readonly int $totalTax,
        public readonly int $subtotalPrice,
        public readonly int $paymentMethodId,
        public readonly string $paymentMethodName,
        public readonly int $paymentFee,
        public readonly string|null $note,
        public readonly string|null $orderNote,
        public readonly OrderLineItems $orderLineItems,
        public readonly Billing $billing,
        public readonly OrderPickup|null $orderPickup,
        public readonly PickUpStatus $pickUpStatus = PickUpStatus::UNAVAILABLE,
        public readonly OrderStatus $status = OrderStatus::DRAFT,
        public readonly int $id = self::ID0,
    ) {
    }

    /**
     * @param int<1, max>       $storeId
     * @param int<min, -1>|null $discountPrice
     * @param int<1, 100>|null  $pointRate
     * @param int<min, 0>|null  $spendingPoint
     * @param int<0, max>|null  $earningPoint
     * @param int<0, max>       $totalPrice
     * @param int<0, max>       $totalTax
     * @param int<0, max>       $subtotalPrice
     * @param int<0, max>       $paymentFee
     * @param int<1, max>       $paymentMethodId
     * @param int<1, max>       $id
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public static function reconstruct(
        int $id,
        int $storeId,
        string $orderNo,
        DateTimeImmutable $orderDate,
        DateTimeImmutable|null $closeDate,
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
        string|null $discountCode,
        int|null $discountPrice,
        int|null $pointRate,
        int|null $spendingPoint,
        int|null $earningPoint,
        int $totalPrice,
        int $totalTax,
        int $subtotalPrice,
        int $paymentMethodId,
        string $paymentMethodName,
        int $paymentFee,
        string|null $note,
        string|null $orderNote,
        PickUpStatus $pickUpStatus,
        OrderStatus $status,
        OrderLineItems $orderLineItems,
        Billing $billing,
        OrderPickup|null $orderPickup,
    ): Order {
        return new self(
            $storeId,
            $orderNo,
            $orderDate,
            $closeDate,
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
            $discountCode,
            $discountPrice,
            $pointRate,
            $spendingPoint,
            $earningPoint,
            $totalPrice,
            $totalTax,
            $subtotalPrice,
            $paymentMethodId,
            $paymentMethodName,
            $paymentFee,
            $note,
            $orderNote,
            $orderLineItems,
            $billing,
            $orderPickup,
            $pickUpStatus,
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

    private function changeStatus(OrderStatus $status): self
    {
        $pickupStatus = match ($status) {
            OrderStatus::WAITING_PICKUP => $this->pickUpStatus->transitionTo(PickUpStatus::AVAILABLE),
            OrderStatus::ARCHIVED => $this->pickUpStatus->transitionTo(PickUpStatus::PICKED_UP),
            OrderStatus::CANCELED => $this->pickUpStatus->transitionTo(PickUpStatus::CANCELED),
            default => $this->pickUpStatus,
        };

        $closeDay = $this->closeDate;
        if ($closeDay === null && $status === OrderStatus::ARCHIVED) {
            $closeDay = new DateTimeImmutable();
        }

        return new self(
            $this->storeId,
            $this->orderNo,
            $this->orderDate,
            $closeDay,
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
            $this->discountCode,
            $this->discountPrice,
            $this->pointRate,
            $this->spendingPoint,
            $this->earningPoint,
            $this->totalPrice,
            $this->totalTax,
            $this->subtotalPrice,
            $this->paymentMethodId,
            $this->paymentMethodName,
            $this->paymentFee,
            $this->note,
            $this->orderNote,
            $this->orderLineItems,
            $this->billing,
            $this->orderPickup,
            $pickupStatus,
            $this->status->transitionTo($status),
            $this->id,
        );
    }

    public function open(): self
    {
        return $this->changeStatus(OrderStatus::OPEN);
    }

    public function canceled(): self
    {
        return $this->changeStatus(OrderStatus::CANCELED);
    }

    public function waitingPayment(): self
    {
        return $this->changeStatus(OrderStatus::WAITING_PAYMENT);
    }

    public function waitingPickup(): self
    {
        return $this->changeStatus(OrderStatus::WAITING_PICKUP);
    }

    public function archived(): self
    {
        return $this->changeStatus(OrderStatus::ARCHIVED);
    }

    public function problem(): self
    {
        return $this->changeStatus(OrderStatus::PROBLEM);
    }
}
