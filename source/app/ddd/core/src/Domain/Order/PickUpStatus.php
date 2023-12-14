<?php

declare(strict_types=1);

namespace AppCore\Domain\Order;

use function in_array;

enum PickUpStatus: string
{
    case UNAVAILABLE = 'unavailable';
    case AVAILABLE = 'available';
    case PICKED_UP = 'picked_up';
    case CANCELED = 'canceled';

    /** @return list<self> */
    private function transitionMap(): array
    {
        return match ($this) {
            self::UNAVAILABLE => [self::AVAILABLE, self::CANCELED],
            self::AVAILABLE => [self::PICKED_UP, self::CANCELED],
            self::PICKED_UP, self::CANCELED => [],
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
