<?php

declare(strict_types=1);

namespace AppCore\Application\Store;

use AppCore\Domain\UrlSignature\InvalidSignatureException;
use AppCore\Domain\UrlSignature\UrlSignatureInterface;
use DateTimeImmutable;

use function serialize;
use function unserialize;

class JoinCustomerUrlSignature implements UrlSignatureInterface
{
    public function __construct(
        public readonly int $customerId,
        public readonly DateTimeImmutable $expiresAt,
        public readonly string $phoneNumber,
        public readonly string $email,
    ) {
    }

    public function serialize(string $random): string
    {
        return serialize([
            '_' => $random,
            'i' => $this->customerId,
            't' => $this->expiresAt->getTimestamp(),
            'p' => $this->phoneNumber,
            'e' => $this->email,
        ]);
    }

    public static function deserialize(string $string): self
    {
        $array = unserialize($string, ['allowed_classes' => false]);
        if (! isset($array['_'], $array['i'], $array['t'], $array['p'], $array['e'])) {
            throw new InvalidSignatureException();
        }

        return new self(
            $array['i'],
            (new DateTimeImmutable())->setTimestamp($array['t']),
            $array['p'],
            $array['e'],
        );
    }
}
