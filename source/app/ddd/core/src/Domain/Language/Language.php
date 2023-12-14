<?php

declare(strict_types=1);

namespace AppCore\Domain\Language;

use function preg_replace;
use function str_replace;
use function str_starts_with;

class Language implements LanguageInterface
{
    /** @param array<string, string> $messages */
    public function __construct(
        private readonly array $messages,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $key, array $params = []): string
    {
        if (! str_starts_with($key, 'message:')) {
            return $key;
        }

        $key = preg_replace('/^message:/', '', $key);

        $message = $this->messages[$key] ?? $key;
        foreach ($params as $paramName => $paramValue) {
            $message = str_replace(':' . $paramName, $paramValue, $message);
        }

        return $message;
    }
}
