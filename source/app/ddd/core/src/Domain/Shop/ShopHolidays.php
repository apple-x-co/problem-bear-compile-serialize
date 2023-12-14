<?php

declare(strict_types=1);

namespace AppCore\Domain\Shop;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

use function array_map;

/** @template-implements IteratorAggregate<ShopHoliday> */
final class ShopHolidays implements IteratorAggregate
{
    /** @param list<ShopHoliday> $values */
    public function __construct(
        private readonly array $values,
    ) {
    }

    /** @return Traversable<ShopHoliday> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->values);
    }

    public function add(ShopHoliday $shopHoliday): self
    {
        $values = array_map(
            static function (ShopHoliday $item) {
                return clone $item;
            },
            $this->values,
        );

        $values[] = $shopHoliday;

        return new self($values);
    }
}
