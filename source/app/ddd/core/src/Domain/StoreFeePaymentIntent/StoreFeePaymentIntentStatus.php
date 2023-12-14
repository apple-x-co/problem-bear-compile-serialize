<?php

declare(strict_types=1);

namespace AppCore\Domain\StoreFeePaymentIntent;

use function in_array;

enum StoreFeePaymentIntentStatus: string
{
    case UNPAID = 'unpaid';
    case EXPIRED = 'expired';
    case VOIDED = 'voided';
    case HOLD_ON = 'hold_on';
    case AUTHORIZED = 'authorized';
    case PAID = 'paid';
    case REFUNDED = 'refunded';

    /** @return list<self> */
    private function transitionMap(): array
    {
        return match ($this) {
            self::UNPAID => [self::EXPIRED, self::VOIDED, self::PAID, self::HOLD_ON, self::AUTHORIZED],
            self::EXPIRED, self::VOIDED, self::REFUNDED => [],
            self::HOLD_ON => [self::AUTHORIZED],
            self::AUTHORIZED => [self::PAID],
            self::PAID => [self::REFUNDED],
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
