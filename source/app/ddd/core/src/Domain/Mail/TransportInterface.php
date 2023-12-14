<?php

declare(strict_types=1);

namespace AppCore\Domain\Mail;

interface TransportInterface
{
    public function send(Email $email): void;
}
