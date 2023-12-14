<?php

declare(strict_types=1);

namespace AppCore\Domain\Product;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

use function array_map;

/** @template-implements IteratorAggregate<ProductMaker> */
final class ProductMakers implements IteratorAggregate
{
    /** @param list<ProductMaker> $values */
    public function __construct(
        private readonly array $values,
    ) {
    }

    /** @return Traversable<ProductMaker> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->values);
    }

    public function add(ProductMaker $productMaker): self
    {
        $values = array_map(
            static function (ProductMaker $item) {
                return clone $item;
            },
            $this->values,
        );

        $values[] = $productMaker;

        return new self($values);
    }
}
