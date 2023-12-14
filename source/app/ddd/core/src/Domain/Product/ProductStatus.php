<?php

declare(strict_types=1);

namespace AppCore\Domain\Product;

use function in_array;

enum ProductStatus: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case DELETED = 'deleted';

    /** @return list<self> */
    private function transitionMap(): array
    {
        return match ($this) {
            self::DRAFT => [self::ACTIVE, self::DELETED],
            self::ACTIVE => [self::DRAFT],
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
