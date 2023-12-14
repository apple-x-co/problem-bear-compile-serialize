<?php

declare(strict_types=1);

namespace AppCore\Domain\CustomerEmail;

use DateTimeImmutable;

final class CustomerEmail
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /**
     * @param int<0, max> $id
     * @param int<1, max> $customerId
     */
    public function __construct(
        public readonly int $customerId,
        public readonly string $email,
        public readonly string $token,
        public readonly DateTimeImmutable $expireDate,
        public readonly DateTimeImmutable|null $verifiedDate,
        public readonly int $id = self::ID0,
    ) {
    }

    /**
     * @param int<1, max> $id
     * @param int<1, max> $customerId
     */
    public static function reconstruct(
        int $id,
        int $customerId,
        string $email,
        string $token,
        DateTimeImmutable $expireDate,
        DateTimeImmutable|null $verifiedDate,
    ): CustomerEmail {
        return new self(
            $customerId,
            $email,
            $token,
            $expireDate,
            $verifiedDate,
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
