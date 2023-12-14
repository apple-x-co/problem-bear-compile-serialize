<?php

declare(strict_types=1);

namespace AppCore\Domain\Shop;

use AppCore\Domain\DayOfWeek;

final class ShopRegularHoliday
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /** @param int<0, max> $id */
    public function __construct(
        public readonly DayOfWeek $dayOfWeek,
        public readonly int $id = self::ID0,
    ) {
    }

    /** @param int<0, max> $id */
    public static function reconstruct(
        int $id,
        DayOfWeek $dayOfWeek,
    ): ShopRegularHoliday {
        return new self(
            $dayOfWeek,
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
