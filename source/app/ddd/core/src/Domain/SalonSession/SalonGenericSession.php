<?php

declare(strict_types=1);

namespace AppCore\Domain\SalonSession;

final class SalonGenericSession
{
    public function __construct(public readonly string|null $continueUrlPath)
    {
    }

    public function clearContinueUrlPath(): self
    {
        return new self(null);
    }

    public function changeContinueUrlPath(string $continueUrlPath): self
    {
        return new self($continueUrlPath);
    }
}
