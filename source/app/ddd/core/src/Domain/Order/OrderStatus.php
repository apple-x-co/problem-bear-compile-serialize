<?php

declare(strict_types=1);

namespace AppCore\Domain\Order;

use function in_array;

enum OrderStatus: string
{
    case DRAFT = 'draft';
    case OPEN = 'open';
    case WAITING_PAYMENT = 'waiting_payment';
    case WAITING_PICKUP = 'waiting_pickup';
    case ARCHIVED = 'archived';
    case CANCELED = 'canceled';
    case PROBLEM = 'problem';

    /** @return list<self> */
    private function transitionMap(): array
    {
        return match ($this) {
            self::DRAFT => [self::OPEN, self::CANCELED],
            self::OPEN => [self::WAITING_PAYMENT, self::WAITING_PICKUP, self::CANCELED],
            self::WAITING_PAYMENT=> [self::WAITING_PICKUP, self::PROBLEM],
            self::WAITING_PICKUP => [self::ARCHIVED],
            self::ARCHIVED, self::CANCELED, self::PROBLEM => [],
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
