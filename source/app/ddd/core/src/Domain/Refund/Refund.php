<?php

declare(strict_types=1);

namespace AppCore\Domain\Refund;

final class Refund
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /**
     * @param int<1, max> $orderId
     * @param int<0, max> $refundedAmount
     * @param int<0, max> $id
     */
    public function __construct(
        public readonly int $orderId,
        public readonly int $refundedAmount = 0,
        public readonly RefundStatus $status = RefundStatus::UNREFUNDED,
        public readonly int $id = self::ID0,
    ) {
    }

    /**
     * @param int<1, max> $orderId
     * @param int<0, max> $refundedAmount
     * @param int<1, max> $id
     */
    public static function reconstruct(
        int $id,
        int $orderId,
        int $refundedAmount,
        RefundStatus $status,
    ): Refund {
        return new self(
            $orderId,
            $refundedAmount,
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

    /** @param int<0, max> $refundedAmount */
    public function refunded(int $refundedAmount): self
    {
        return new self(
            $this->orderId,
            $refundedAmount,
            $this->status->transitionTo(RefundStatus::REFUNDED),
            $this->id,
        );
    }
}
