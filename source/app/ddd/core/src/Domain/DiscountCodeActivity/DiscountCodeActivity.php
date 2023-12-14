<?php

declare(strict_types=1);

namespace AppCore\Domain\DiscountCodeActivity;

use DateTimeImmutable;

final class DiscountCodeActivity
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /**
     * @param int<1, max>      $storeId
     * @param int<1, max>|null $customerId
     * @param int<0, max>      $id
     */
    public function __construct(
        public readonly int $storeId,
        public readonly string $code,
        public readonly int|null $customerId,
        public readonly string $email,
        public readonly string $phoneNumber,
        public readonly DateTimeImmutable $usedDate,
        public readonly int $id = self::ID0,
    ) {
    }

    /**
     * @param int<1, max>      $storeId
     * @param int<1, max>|null $customerId
     * @param int<1, max>      $id
     */
    public static function reconstruct(
        int $id,
        int $storeId,
        string $code,
        int|null $customerId,
        string $email,
        string $phoneNumber,
        DateTimeImmutable $usedDate,
    ): self {
        return new self(
            $storeId,
            $code,
            $customerId,
            $email,
            $phoneNumber,
            $usedDate,
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
}
