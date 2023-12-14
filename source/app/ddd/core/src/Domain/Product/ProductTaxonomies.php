<?php

declare(strict_types=1);

namespace AppCore\Domain\Product;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

use function array_map;

/** @template-implements IteratorAggregate<ProductTaxonomy> */
final class ProductTaxonomies implements IteratorAggregate
{
    /** @param list<ProductTaxonomy> $values */
    public function __construct(
        private readonly array $values,
    ) {
    }

    /** @return Traversable<ProductTaxonomy> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->values);
    }

    public function add(ProductTaxonomy $productTaxonomy): self
    {
        $values = array_map(
            static function (ProductTaxonomy $item) {
                return clone $item;
            },
            $this->values,
        );

        $values[] = $productTaxonomy;

        return new self($values);
    }
}
