<?php

declare(strict_types=1);

namespace AppCore\Domain\Order;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

use function array_map;

/** @template-implements IteratorAggregate<OrderLineItem> */
final class OrderLineItems implements IteratorAggregate
{
    /** @param list<OrderLineItem> $values */
    public function __construct(
        private readonly array $values,
    ) {
    }

    /** @return Traversable<OrderLineItem> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->values);
    }

    public function add(OrderLineItem $orderLineItem): self
    {
        $values = array_map(
            static function (OrderLineItem $item) {
                return clone $item;
            },
            $this->values,
        );

        $values[] = $orderLineItem;

        return new self($values);
    }
}
