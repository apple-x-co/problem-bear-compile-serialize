<?php

declare(strict_types=1);

namespace AppCore\Domain\Mail;

enum Format
{
    case Text;
    case Html;
    case Both;

    public function isText(): bool
    {
        return match ($this) {
            self::Text, self::Both => true,
            default => false,
        };
    }

    public function isHtml(): bool
    {
        return match ($this) {
            self::Html, self::Both => true,
            default => false,
        };
    }
}
