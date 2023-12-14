<?php

declare(strict_types=1);

namespace AppCore\Domain\Company;

use function in_array;

enum CompanyStatus: string
{
    case IN_PREPARATION = 'in_preparation';
    case ACTIVE = 'active';
    case LEAVED = 'leaved';
    case VOID = 'void';

    /** @return list<self> */
    private function transitionMap(): array
    {
        return match ($this) {
            self::IN_PREPARATION => [self::ACTIVE],
            self::ACTIVE => [self::LEAVED],
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
