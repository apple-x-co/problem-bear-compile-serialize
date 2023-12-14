<?php

declare(strict_types=1);

namespace AppCore\Domain\PaymentIntent;

use function in_array;

enum PaymentIntentStatus: string
{
    case UNPAID = 'unpaid';
    case DUE = 'due';
    case DECLINED = 'declined';
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
            self::UNPAID => [self::DUE, self::VOIDED],
            self::DUE => [self::DUE, self::DECLINED, self::PAID, self::AUTHORIZED, self::EXPIRED, self::HOLD_ON],
            self::DECLINED, self::EXPIRED, self::REFUNDED => [],
            self::VOIDED, self::AUTHORIZED => [self::PAID],
            self::HOLD_ON => [self::AUTHORIZED],
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
