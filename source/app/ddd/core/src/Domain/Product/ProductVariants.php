<?php

declare(strict_types=1);

namespace AppCore\Domain\Product;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

use function array_map;

/** @template-implements IteratorAggregate<ProductVariant> */
final class ProductVariants implements IteratorAggregate
{
    /** @param list<ProductVariant> $values */
    public function __construct(
        private readonly array $values,
    ) {
    }

    /** @return Traversable<ProductVariant> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->values);
    }

    public function add(ProductVariant $productVariant): self
    {
        $values = array_map(
            static function (ProductVariant $item) {
                return clone $item;
            },
            $this->values,
        );

        $values[] = $productVariant;

        return new self($values);
    }
}
