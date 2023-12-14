<?php

declare(strict_types=1);

namespace AppCore\Domain\Refund;

use function in_array;

enum RefundStatus: string
{
    case UNREFUNDED = 'unrefunded';
    case REFUNDED = 'refunded';

    /** @return list<self> */
    private function transitionMap(): array
    {
        return match ($this) {
            self::UNREFUNDED => [self::REFUNDED],
            self::REFUNDED => [],
        };
    }

    public function transitionTo(self $status): self
    {
        if (! in_array($status, $this->transitionMap(), true)) {
            throw new ForbiddenTransitionException();
        }

        return self::from($status->value);
    }
}
