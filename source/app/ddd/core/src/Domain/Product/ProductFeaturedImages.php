<?php

declare(strict_types=1);

namespace AppCore\Domain\Product;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

use function array_map;

/** @template-implements IteratorAggregate<ProductFeaturedImage> */
final class ProductFeaturedImages implements IteratorAggregate
{
    /** @param list<ProductFeaturedImage> $values */
    public function __construct(
        private readonly array $values,
    ) {
    }

    /** @return Traversable<ProductFeaturedImage> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->values);
    }

    public function add(ProductFeaturedImage $productImage): self
    {
        $values = array_map(
            static function (ProductFeaturedImage $item) {
                return clone $item;
            },
            $this->values,
        );

        $values[] = $productImage;

        return new self($values);
    }
}
