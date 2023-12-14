<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use AppCore\Domain\Encrypter\EncrypterException;
use AppCore\Domain\Encrypter\EncrypterInterface;
use AppCore\Qualifier\EncryptPass;

use function base64_decode;
use function base64_encode;
use function hash_equals;
use function hash_hmac;
use function is_array;
use function is_string;
use function json_decode;
use function json_encode;
use function json_last_error_msg;
use function openssl_cipher_iv_length;
use function openssl_decrypt;
use function openssl_encrypt;
use function openssl_random_pseudo_bytes;
use function strlen;

use const JSON_UNESCAPED_SLASHES;

class Encrypter implements EncrypterInterface
{
    private const CIPHER_ALGO = 'aes-128-cbc';
    private const CIPHER_OPTION = 0;

    public function __construct(
        #[EncryptPass]
        private readonly string $pass,
    ) {
    }

    public function encrypt(string $text): string
    {
        $ivLen = openssl_cipher_iv_length(self::CIPHER_ALGO);
        $iv = openssl_random_pseudo_bytes($ivLen, $strong);
        if ($strong === false) {
            throw new EncrypterException();
        }

        $encrypted = openssl_encrypt(
            $text,
            self::CIPHER_ALGO,
            $this->pass,
            self::CIPHER_OPTION,
            $iv,
        );
        if ($encrypted === false) {
            throw new EncrypterException();
        }

        $hmac = hash_hmac('sha256', $encrypted, $this->pass);

        $json = json_encode(
            ['iv' => base64_encode($iv), 'hmac' => $hmac, 'encrypted' => $encrypted],
            JSON_UNESCAPED_SLASHES,
        );
        if ($json === false) {
            throw new EncrypterException(json_last_error_msg());
        }

        return base64_encode($json);
    }

    public function decrypt(string $payload): string
    {
        $json = json_decode(base64_decode($payload), true);
        if (! is_array($json)) {
            throw new EncrypterException();
        }

        if (
            ! isset($json['iv'], $json['hmac'], $json['encrypted']) ||
            ! is_string($json['iv']) ||
            ! is_string($json['hmac']) ||
            ! is_string($json['encrypted'])
        ) {
            throw new EncrypterException();
        }

        $ivLen = openssl_cipher_iv_length(self::CIPHER_ALGO);
        $iv = base64_decode($json['iv']);
        if (strlen($iv) !== $ivLen) {
            throw new EncrypterException();
        }

        $decrypted = openssl_decrypt(
            $json['encrypted'],
            self::CIPHER_ALGO,
            $this->pass,
            self::CIPHER_OPTION,
            $iv,
        );
        if ($decrypted === false) {
            throw new EncrypterException();
        }

        $hmac = hash_hmac('sha256', $json['encrypted'], $this->pass, true);
        if (hash_equals($json['hmac'], $hmac)) {
            throw new EncrypterException();
        }

        return $decrypted;
    }
}
