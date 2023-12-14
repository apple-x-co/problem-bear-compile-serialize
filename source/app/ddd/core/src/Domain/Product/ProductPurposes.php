<?php

declare(strict_types=1);

namespace AppCore\Domain\Product;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

use function array_map;

/** @template-implements IteratorAggregate<ProductPurpose> */
final class ProductPurposes implements IteratorAggregate
{
    /** @param list<ProductPurpose> $values */
    public function __construct(
        private readonly array $values,
    ) {
    }

    /** @return Traversable<ProductPurpose> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->values);
    }

    public function add(ProductPurpose $productPurpose): self
    {
        $values = array_map(
            static function (ProductPurpose $item) {
                return clone $item;
            },
            $this->values,
        );

        $values[] = $productPurpose;

        return new self($values);
    }
}
