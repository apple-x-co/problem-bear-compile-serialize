<?php

declare(strict_types=1);

namespace AppCore\Domain\StoreUsage;

use DateTimeImmutable;

final class StoreUsage
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /**
     * @param int<1, max> $storeId
     * @param int<1, max> $totalPrice
     * @param int<0, max> $id
     */
    public function __construct(
        public readonly int $storeId,
        public readonly string $description,
        public readonly DateTimeImmutable $targetDate,
        public readonly int $totalPrice,
        public readonly StoreUsageBilling $billing,
        public readonly StoreUsageStatus $status = StoreUsageStatus::DRAFT,
        public readonly int $id = self::ID0,
    ) {
    }

    /**
     * @param int<1, max> $storeId
     * @param int<1, max> $totalPrice
     * @param int<1, max> $id
     */
    public static function reconstruct(
        int $id,
        int $storeId,
        string $description,
        DateTimeImmutable $targetDate,
        int $totalPrice,
        StoreUsageBilling $billing,
        StoreUsageStatus $status,
    ): StoreUsage {
        return new self(
            $storeId,
            $description,
            $targetDate,
            $totalPrice,
            $billing,
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

    private function changeStatus(
        StoreUsageBilling $billing,
        StoreUsageStatus $status,
    ): self {
        return new self(
            $this->storeId,
            $this->description,
            clone $this->targetDate,
            $this->totalPrice,
            $billing,
            $this->status->transitionTo($status),
            $this->id,
        );
    }

    public function open(): self
    {
        return $this->changeStatus($this->billing->open(), $this->status->transitionTo(StoreUsageStatus::OPEN));
    }

    public function archive(): self
    {
        return $this->changeStatus($this->billing->paid(), $this->status->transitionTo(StoreUsageStatus::ARCHIVE));
    }

    public function problem(): self
    {
        return $this->changeStatus($this->billing->void(), $this->status->transitionTo(StoreUsageStatus::PROBLEM));
    }
}
