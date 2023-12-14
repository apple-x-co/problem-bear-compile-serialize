<?php

declare(strict_types=1);

namespace AppCore\Domain\StaffMember;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

use function array_map;

/** @template-implements IteratorAggregate<StaffMemberPermission> */
final class StaffMemberPermissions implements IteratorAggregate
{
    /** @param list<StaffMemberPermission> $values */
    public function __construct(
        private readonly array $values,
    ) {
    }

    /** @return Traversable<StaffMemberPermission> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->values);
    }

    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function add(StaffMemberPermission $staffMemberPermission): self
    {
        $values = array_map(
            static function (StaffMemberPermission $item) {
                return clone $item;
            },
            $this->values,
        );

        $values[] = $staffMemberPermission;

        return new self($values);
    }
}
