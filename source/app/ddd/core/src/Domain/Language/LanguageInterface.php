<?php

declare(strict_types=1);

namespace AppCore\Domain\Language;

interface LanguageInterface
{
    /** @param array<string, mixed> $params */
    public function get(string $key, array $params = []): string;
}
