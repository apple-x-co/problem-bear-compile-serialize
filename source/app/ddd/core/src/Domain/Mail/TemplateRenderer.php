<?php

declare(strict_types=1);

namespace AppCore\Domain\Mail;

use DateTimeImmutable;
use Throwable;

use function extract;
use function file_exists;
use function is_readable;
use function ob_end_clean;
use function ob_get_clean;
use function ob_start;

use const EXTR_SKIP;

class TemplateRenderer implements TemplateRendererInterface
{
    /** @param array<string, mixed> $vars */
    public function __invoke(string $path, array $vars = []): string
    {
        if (! file_exists($path) || ! is_readable($path)) {
            return '';
        }

        $vars['_now'] = new DateTimeImmutable();

        try {
            ob_start();
            extract($vars, EXTR_SKIP); // phpcs:ignore
            include $path;

            return (string) ob_get_clean();
        } catch (Throwable $throwable) {
            ob_end_clean();

            throw $throwable;
        }
    }
}
