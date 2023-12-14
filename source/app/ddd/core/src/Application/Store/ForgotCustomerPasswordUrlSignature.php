<?php

declare(strict_types=1);

namespace AppCore\Application\Store;

use AppCore\Domain\UrlSignature\InvalidSignatureException;
use AppCore\Domain\UrlSignature\UrlSignatureInterface;
use DateTimeImmutable;

use function serialize;
use function unserialize;

class ForgotCustomerPasswordUrlSignature implements UrlSignatureInterface
{
    public function __construct(
        public readonly DateTimeImmutable $expiresAt,
        public readonly string $email,
    ) {
    }

    public function serialize(string $random): string
    {
        return serialize([
            '_' => $random,
            't' => $this->expiresAt->getTimestamp(),
            'e' => $this->email,
        ]);
    }

    public static function deserialize(string $string): self
    {
        $array = unserialize($string, ['allowed_classes' => false]);
        if (! isset($array['_'], $array['t'], $array['e'])) {
            throw new InvalidSignatureException();
        }

        return new self(
            (new DateTimeImmutable())->setTimestamp($array['t']),
            $array['e'],
        );
    }
}
