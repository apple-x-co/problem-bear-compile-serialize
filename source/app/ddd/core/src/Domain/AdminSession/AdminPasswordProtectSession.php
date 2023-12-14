<?php

declare(strict_types=1);

namespace AppCore\Domain\AdminSession;

use DateTimeImmutable;

final class AdminPasswordProtectSession
{
    public function __construct(
        public readonly string|null $continueUrlPath,
        public readonly AdminPasswordLocking|null $locking,
        public readonly DateTimeImmutable|null $expireDate,
    ) {
    }

    public function unlock(): self
    {
        return new self(
            null,
            AdminPasswordLocking::Unlocked,
            (new DateTimeImmutable())->modify('+5 min'),
        );
    }

    public function lock(): self
    {
        return new self(
            null,
            AdminPasswordLocking::Locked,
            null,
        );
    }

    public function protect(string|null $continueUrlPath): self
    {
        return new self(
            $continueUrlPath,
            $this->locking,
            $this->expireDate,
        );
    }

    public function isUnlocking(): bool
    {
        if ($this->locking === AdminPasswordLocking::Locked) {
            return false;
        }

        if ($this->expireDate === null) {
            return true;
        }

        $now = new DateTimeImmutable();

        return $now < $this->expireDate;
    }
}
