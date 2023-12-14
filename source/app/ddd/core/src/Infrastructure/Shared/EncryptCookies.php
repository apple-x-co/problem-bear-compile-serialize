<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use AppCore\Domain\Encrypter\EncrypterInterface;
use AppCore\Presentation\Shared\EncryptCookiesInterface;
use AppCore\Qualifier\Cookie;
use AppCore\Qualifier\CookieNamePrefix;

use function setcookie;
use function time;

final class EncryptCookies implements EncryptCookiesInterface
{
    /** @param array<string, string|array<string, string>> $cookies */
    public function __construct(
        #[Cookie]
        private readonly array $cookies,
        #[CookieNamePrefix]
        private readonly string $namePrefix,
        private readonly EncrypterInterface $encrypter,
    ) {
    }

    public function get(string $name): string|null
    {
        $encryptedValue = $this->cookies[$this->namePrefix][$name] ?? null;
        if ($encryptedValue === null) {
            return null;
        }

        return $this->encrypter->decrypt($encryptedValue);
    }

    public function set(
        string $name,
        string $value,
        int $hours,
        string $path = '/',
        string $sameSite = 'strict',
    ): void {
        $name = $this->namePrefix . '[' . $name . ']';
        $encryptedValue = $this->encrypter->encrypt($value);

        setcookie(
            $name,
            $encryptedValue,
            [
                'expires' => time() + (60 * 60 * $hours),
                'path' => $path,
                'samesite' => $sameSite,
                'httponly' => true,
            ],
        );
    }

    public function revoke(
        string $name,
        string $path = '/',
        string $sameSite = 'strict',
    ): void {
        $name = $this->namePrefix . '[' . $name . ']';

        setcookie(
            $name,
            '',
            [
                'expires' => time() - 1,
                'path' => $path,
                'samesite' => $sameSite,
                'httponly' => true,
            ],
        );
    }
}
