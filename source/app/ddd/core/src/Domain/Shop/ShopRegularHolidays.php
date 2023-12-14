<?php

declare(strict_types=1);

namespace AppCore\Domain\Shop;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

use function array_map;

/** @template-implements IteratorAggregate<ShopRegularHoliday> */
final class ShopRegularHolidays implements IteratorAggregate
{
    /** @param list<ShopRegularHoliday> $values */
    public function __construct(
        private readonly array $values,
    ) {
    }

    /** @return Traversable<ShopRegularHoliday> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->values);
    }

    public function add(ShopRegularHoliday $shopRegularHoliday): self
    {
        $values = array_map(
            static function (ShopRegularHoliday $item) {
                return clone $item;
            },
            $this->values,
        );

        $values[] = $shopRegularHoliday;

        return new self($values);
    }
}
