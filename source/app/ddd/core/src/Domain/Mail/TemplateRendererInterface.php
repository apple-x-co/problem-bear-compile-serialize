<?php

declare(strict_types=1);

namespace AppCore\Domain\Mail;

interface TemplateRendererInterface
{
    /** @param array<string, mixed> $vars */
    public function __invoke(string $path, array $vars = []): string;
}
