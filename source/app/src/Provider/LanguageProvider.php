<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Provider;

use AppCore\Domain\Language\Language;
use AppCore\Qualifier\LangDir;
use Ray\Di\ProviderInterface;

/**
 * 言語ファイルを提供
 *
 * @implements ProviderInterface<Language>
 */
class LanguageProvider implements ProviderInterface
{
    public function __construct(
        #[LangDir]
        private readonly string $langDir,
    ) {
    }

    /** @psalm-suppress UnresolvableInclude */
    public function get(): Language
    {
        // TODO: header から言語を判断する

        return new Language(require $this->langDir . '/ja/messages.php');
    }
}
