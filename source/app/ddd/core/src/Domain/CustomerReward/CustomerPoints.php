<?php

declare(strict_types=1);

namespace AppCore\Domain\CustomerReward;

use AppCore\Domain\Uuid;
use ArrayIterator;
use DateTimeImmutable;
use IteratorAggregate;
use Traversable;

use function array_map;
use function array_merge;
use function array_reduce;
use function min;

/** @template-implements IteratorAggregate<CustomerPoint> */
final class CustomerPoints implements IteratorAggregate
{
    /** @param list<CustomerPoint> $values */
    public function __construct(
        private readonly array $values,
    ) {
    }

    /** @return Traversable<CustomerPoint> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->values);
    }

    /**
     * @return int<0, max>
     *
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     */
    public function calculateRemainingPoint(): int
    {
        return array_reduce(
            $this->values,
            static function (int $carry, CustomerPoint $item) {
                if (! $item->canUsePoint()) {
                    return $carry;
                }

                return $carry + $item->remainingPoint;
            },
            0,
        );
    }

    /** @param int<1, max> $point */
    public function earn(
        DateTimeImmutable $transactionDate,
        DateTimeImmutable $expireDate,
        int $point,
    ): self {
        $values = array_map(
            static function (CustomerPoint $item) {
                return clone $item;
            },
            $this->values,
        );

        $values[] = new CustomerPoint(
            (new Uuid())(),
            PointType::EARNING,
            $transactionDate,
            $expireDate,
            $point,
            $point,
        );

        return new self($values);
    }

    /** @param int<1, max> $point */
    public function spend(int $point): self
    {
        $oldValues = [];
        $newValues = [];

        $spendingPoint = $point;

        foreach ($this->values as $value) {
            if ($spendingPoint === 0 || ! $value->canUsePoint()) {
                $oldValues[] = clone $value;

                continue;
            }

            $minPoint = min($value->remainingPoint, $spendingPoint);
            $spendingPoint -= $minPoint;

            /** @psalm-suppress InvalidArgument */
            $oldValues[] = $value->usePoint($minPoint);

            /** @psalm-suppress InvalidArgument */
            $newValues[] = new CustomerPoint(
                $value->uuid,
                PointType::SPENDING,
                new DateTimeImmutable(),
                clone $value->expireDate,
                $minPoint,
                0,
            );
        }

        return new self(array_merge($oldValues, $newValues));
    }
}
