<?php

declare(strict_types=1);

namespace AppCore\Domain\Tax;

use function round;

final class Tax
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /**
     * @param int<1, 100> $rate
     * @param int<0, max> $id
     */
    public function __construct(
        public readonly string $name,
        public readonly int $rate,
        public readonly int $id = self::ID0,
    ) {
    }

    /**
     * @param int<1, 100> $rate
     * @param int<1, max> $id
     */
    public static function reconstruct(
        int $id,
        string $name,
        int $rate,
    ): Tax {
        return new Tax(
            $name,
            $rate,
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

    /** @param int<1, max> $price */
    public function calculate(int $price): int
    {
        return (int) round($price * $this->rate / 100);
    }
}
