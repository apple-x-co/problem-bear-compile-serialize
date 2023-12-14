<?php

declare(strict_types=1);

namespace AppCore\Domain\DiscountCode;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

use function array_filter;
use function array_map;
use function array_values;

/** @template-implements IteratorAggregate<DiscountEntitledProduct> */
final class DiscountEntitledProducts implements IteratorAggregate
{
    /** @param list<DiscountEntitledProduct> $values */
    public function __construct(
        private readonly array $values,
    ) {
    }

    /** @return Traversable<DiscountEntitledProduct> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->values);
    }

    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function add(DiscountEntitledProduct $discountEntitledProduct): self
    {
        $values = array_map(
            static function (DiscountEntitledProduct $item) {
                return clone $item;
            },
            $this->values,
        );

        $values[] = $discountEntitledProduct;

        return new self($values);
    }

    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function remove(DiscountEntitledProduct $discountEntitledProduct): self
    {
        $values = array_filter(
            $this->values,
            static function (DiscountEntitledProduct $item) use ($discountEntitledProduct) {
                return ! (
                    $item->id === $discountEntitledProduct->id &&
                    $item->productId === $discountEntitledProduct->productId
                );
            },
        );

        return new self(array_values($values));
    }
}
