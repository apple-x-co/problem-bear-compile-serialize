<?php

declare(strict_types=1);

namespace AppCore\Domain\StoreUsage;

use function in_array;

enum StoreUsageStatus: string
{
    case DRAFT = 'draft';
    case OPEN = 'open';
    case ARCHIVE = 'archive';
    case PROBLEM = 'problem';

    /** @return list<self> */
    private function transitionMap(): array
    {
        return match ($this) {
            self::DRAFT => [self::OPEN],
            self::OPEN => [self::ARCHIVE, self::PROBLEM],
            self::ARCHIVE, self::PROBLEM => [],
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
