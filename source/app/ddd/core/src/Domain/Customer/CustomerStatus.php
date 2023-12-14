<?php

declare(strict_types=1);

namespace AppCore\Domain\Customer;

use function in_array;

enum CustomerStatus: string
{
    case TEMPORARY = 'temporary';
    case VERIFIED = 'verified';
    case DELETED = 'deleted';

    /** @return list<self> */
    private function transitionMap(): array
    {
        return match ($this) {
            self::TEMPORARY => [self::VERIFIED, self::DELETED],
            self::VERIFIED => [self::DELETED],
            self::DELETED => [],
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
