<?php

declare(strict_types=1);

namespace AppCore\Domain\Store;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

use function array_map;

/** @template-implements IteratorAggregate<StoreHero> */
final class StoreHeroes implements IteratorAggregate
{
    /** @param list<StoreHero> $values */
    public function __construct(
        private readonly array $values,
    ) {
    }

    /** @return Traversable<StoreHero> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->values);
    }

    public function add(StoreHero $storeHero): self
    {
        $values = array_map(
            static function (StoreHero $item) {
                return clone $item;
            },
            $this->values,
        );

        $values[] = $storeHero;

        return new self($values);
    }
}
