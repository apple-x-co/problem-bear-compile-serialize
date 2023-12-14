<?php

declare(strict_types=1);

namespace AppCore\Domain\Mail;

interface AddressInterface
{
    public function getAddress(): string;

    public function getName(): string|null;
}
