<?php

declare(strict_types=1);

namespace AppCore\Domain\Mail;

class Address implements AddressInterface
{
    public function __construct(
        public readonly string $address,
        public readonly string|null $name = null,
    ) {
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getName(): string|null
    {
        return $this->name;
    }
}
