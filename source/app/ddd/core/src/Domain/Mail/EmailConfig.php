<?php

declare(strict_types=1);

namespace AppCore\Domain\Mail;

class EmailConfig implements EmailConfigInterface
{
    /** @param array{ssl?: array{verify_peer: bool, verify_peer_name: bool, allow_self_signed: bool}} $options */
    public function __construct(
        private readonly string $hostname,
        private readonly int $port,
        private readonly string $username,
        private readonly string $password,
        private readonly array $options,
    ) {
    }

    public function getHostname(): string
    {
        return $this->hostname;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * {@inheritDoc}
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
