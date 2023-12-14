<?php

declare(strict_types=1);

namespace AppCore\Domain\CustomerReward;

use DateTimeImmutable;

final class CustomerReward
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /**
     * @param int<1, max> $customerId
     * @param int<1, max> $storeId
     * @param int<0, max> $remainingPoint
     * @param int<0, max> $id
     */
    public function __construct(
        public readonly int $customerId,
        public readonly int $storeId,
        public readonly int $remainingPoint = 0,
        public readonly CustomerPoints $points = new CustomerPoints([]),
        public readonly int $id = self::ID0,
    ) {
    }

    /**
     * @param int<1, max> $customerId
     * @param int<1, max> $storeId
     * @param int<0, max> $remainingPoint
     * @param int<1, max> $id
     */
    public static function reconstruct(
        int $id,
        int $customerId,
        int $storeId,
        int $remainingPoint,
        CustomerPoints $points,
    ): CustomerReward {
        return new self(
            $customerId,
            $storeId,
            $remainingPoint,
            $points,
            $id,
        );
    }

    /** @param int<1, max> $point */
    public function earn(
        DateTimeImmutable $transactionDate,
        DateTimeImmutable $expireDate,
        int $point,
    ): self {
        $points = $this->points->earn($transactionDate, $expireDate, $point);

        return new self(
            $this->customerId,
            $this->storeId,
            $points->calculateRemainingPoint(),
            $points,
            $this->id,
        );
    }

    /** @param int<1, max> $point */
    public function spend(int $point): self
    {
        if ($this->remainingPoint < $point) {
            throw new InvalidPointException();
        }

        $points = $this->points->spend($point);

        return new self(
            $this->customerId,
            $this->storeId,
            $points->calculateRemainingPoint(),
            $points,
            $this->id,
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
}
