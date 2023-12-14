<?php

declare(strict_types=1);

namespace AppCore\Domain\UrlSignature;

interface UrlSignatureInterface
{
    public function serialize(string $random): string;

    public static function deserialize(string $string): self;
}
