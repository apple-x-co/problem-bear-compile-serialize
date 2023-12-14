<?php

declare(strict_types=1);

namespace AppCore\Domain\DiscountCode;

use function in_array;

enum DiscountCodeStatus: string
{
    case PRIVATE = 'private';
    case PUBLIC = 'public';

    /** @return list<self> */
    private function transitionMap(): array
    {
        return match ($this) {
            self::PRIVATE => [self::PUBLIC],
            self::PUBLIC => [self::PRIVATE],
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
