<?php

declare(strict_types=1);

namespace AppCore\Domain\Store;

use function in_array;

enum StoreStatus: string
{
    case DRAFT = 'draft';
    case PRIVATE = 'private';
    case ACTIVE = 'active';
    case PAYMENT_ERROR = 'payment_error';
    case LEAVED = 'leaved';
    case VOID = 'void';

    /** @return list<self> */
    private function transitionMap(): array
    {
        return match ($this) {
            self::DRAFT, self::PAYMENT_ERROR => [self::PRIVATE],
            self::PRIVATE => [self::ACTIVE, self::LEAVED],
            self::ACTIVE => [self::PRIVATE, self::PAYMENT_ERROR],
            self::LEAVED => [self::VOID],
            self::VOID => [],
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
