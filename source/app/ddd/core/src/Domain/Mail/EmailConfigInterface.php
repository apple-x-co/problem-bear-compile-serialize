<?php

declare(strict_types=1);

namespace AppCore\Domain\Mail;

interface EmailConfigInterface
{
    public function getHostname(): string;

    public function getPort(): int;

    public function getUsername(): string;

    public function getPassword(): string;

    /** @return array{ssl?: array{verify_peer: bool, verify_peer_name: bool, allow_self_signed: bool}} */
    public function getOptions(): array;
}
