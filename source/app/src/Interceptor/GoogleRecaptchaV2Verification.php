<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Interceptor;

use GuzzleHttp\Client as GuzzleHttp;
use MyVendor\MyProject\Annotation\GoogleRecaptchaV2;
use MyVendor\MyProject\Annotation\Qualifier\GoogleRecaptchaSecretKey;
use MyVendor\MyProject\Captcha\RecaptchaTokenMissing;
use MyVendor\MyProject\Captcha\RecaptchaVerifyError;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;

use function array_reduce;
use function assert;
use function call_user_func;
use function json_decode;

class GoogleRecaptchaV2Verification implements MethodInterceptor
{
    private const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    public function __construct(
        #[GoogleRecaptchaSecretKey]
        private readonly string $secretKey,
    ) {
    }

    /** @SuppressWarnings(PHPMD.Superglobals) */
    public function invoke(MethodInvocation $invocation): mixed
    {
        $googleRecaptchaV2 = $invocation->getMethod()->getAnnotation(GoogleRecaptchaV2::class);
        assert($googleRecaptchaV2 instanceof GoogleRecaptchaV2);

        $gRecaptchaResponse = $_POST['g-recaptcha-response'] ?? null;

        if ($gRecaptchaResponse === null) {
            return call_user_func(
                [$invocation->getThis(), $googleRecaptchaV2->onFailure],
                [new RecaptchaTokenMissing()],
            );
        }

        $guzzle = new GuzzleHttp(['verify' => false]);
        $response = $guzzle->request('POST', self::VERIFY_URL, [
            'form_params' => [
                'secret' => $this->secretKey,
                'response' => $gRecaptchaResponse,
                'remoteip' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? null,
            ],
        ]);

        if ($response->getStatusCode() !== 200) {
            return call_user_func(
                [$invocation->getThis(), $googleRecaptchaV2->onFailure],
                [new RecaptchaTokenMissing()],
            );
        }

        /** @var array{success: bool, challenge_ts: string, hostname: string, error-codes?: array<string>} $result */
        $result = json_decode($response->getBody()->getContents(), true);

        if (! $result['success']) {
            return call_user_func(
                [$invocation->getThis(), $googleRecaptchaV2->onFailure],
                array_reduce(
                    $result['error-codes'] ?? [],
                    static function (array $carry, string $item) {
                        $carry[] = new RecaptchaVerifyError($item);

                        return $carry;
                    },
                    [],
                ),
            );
        }

        return $invocation->proceed();
    }
}
