<?php

declare(strict_types=1);

namespace AppCore\Domain\SellerTopic;

use DateTimeImmutable;

final class SellerTopic
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /** @param int<0, max> $id */
    public function __construct(
        public readonly DateTimeImmutable $publishStartDate,
        public readonly DateTimeImmutable|null $publishEndDate,
        public readonly string $title,
        public readonly string $text,
        public readonly int $id = self::ID0,
    ) {
    }

    /** @param int<1, max> $id */
    public static function reconstruct(
        int $id,
        DateTimeImmutable $publishStartDate,
        DateTimeImmutable|null $publishEndDate,
        string $title,
        string $text,
    ): SellerTopic {
        return new self(
            $publishStartDate,
            $publishEndDate,
            $title,
            $text,
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
