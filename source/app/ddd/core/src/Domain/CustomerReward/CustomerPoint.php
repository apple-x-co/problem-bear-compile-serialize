<?php

declare(strict_types=1);

namespace AppCore\Domain\CustomerReward;

use DateTimeImmutable;

final class CustomerPoint
{
    /**
     * @param int<1, max> $point
     * @param int<0, max> $remainingPoint
     * @param int<0, max> $id
     */
    public function __construct(
        public readonly string $uuid,
        public readonly PointType $type,
        public readonly DateTimeImmutable $transactionDate,
        public readonly DateTimeImmutable $expireDate,
        public readonly int $point,
        public readonly int $remainingPoint,
        public readonly DateTimeImmutable|null $invalidationDate = null,
        public readonly string|null $invalidationReason = null,
        public readonly int $id = 0,
    ) {
    }

    /**
     * @param int<1, max> $point
     * @param int<0, max> $remainingPoint
     * @param int<1, max> $id
     */
    public static function reconstruct(
        int $id,
        string $uuid,
        PointType $type,
        DateTimeImmutable $transactionDate,
        DateTimeImmutable $expireDate,
        int $point,
        int $remainingPoint,
        DateTimeImmutable|null $invalidationDate,
        string|null $invalidationReason,
    ): CustomerPoint {
        return new self(
            $uuid,
            $type,
            $transactionDate,
            $expireDate,
            $point,
            $remainingPoint,
            $invalidationDate,
            $invalidationReason,
            $id,
        );
    }

    public function canUsePoint(): bool
    {
        $now = new DateTimeImmutable();

        return $this->type === PointType::EARNING &&
            $this->expireDate > $now &&
            $this->remainingPoint > 0;
    }

    /** @param int<1, max> $point */
    public function usePoint(int $point): self
    {
        $remainingPoint = $this->remainingPoint - $point;
        if ($remainingPoint < 0) {
            throw new InvalidPointException();
        }

        return new self(
            $this->uuid,
            $this->type,
            clone $this->transactionDate,
            clone $this->expireDate,
            $this->point,
            $remainingPoint,
            $this->invalidationDate === null ? null : clone $this->invalidationDate,
            $this->invalidationReason,
            $this->id,
        );
    }
}
