<?php

declare(strict_types=1);

namespace AppCore\Domain\StoreUsage;

use function in_array;

enum StoreUsageBillingStatus: string
{
    case DRAFT = 'draft';
    case OPEN = 'open';
    case PAID = 'paid';
    case VOID = 'void';

    /** @return list<self> */
    private function transitionMap(): array
    {
        return match ($this) {
            self::DRAFT => [self::OPEN],
            self::OPEN => [self::PAID, self::VOID],
            self::PAID, self::VOID => [],
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
