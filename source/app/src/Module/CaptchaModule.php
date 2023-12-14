<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use BEAR\Resource\ResourceObject;
use MyVendor\MyProject\Annotation\GoogleRecaptchaV2;
use MyVendor\MyProject\Annotation\Qualifier\GoogleRecaptchaSecretKey;
use MyVendor\MyProject\Annotation\Qualifier\GoogleRecaptchaSiteKey;
use MyVendor\MyProject\Interceptor\GoogleRecaptchaV2Verification;
use Ray\Di\AbstractModule;

use function getenv;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class CaptchaModule extends AbstractModule
{
    protected function configure(): void
    {
        $this->bind()->annotatedWith(GoogleRecaptchaSiteKey::class)->toInstance((string) getenv('GOOGLE_RECAPTCHA_SITE_KEY'));
        $this->bind()->annotatedWith(GoogleRecaptchaSecretKey::class)->toInstance((string) getenv('GOOGLE_RECAPTCHA_SECRET_KEY'));

        $this->bindInterceptor(
            $this->matcher->subclassesOf(ResourceObject::class),
            $this->matcher->annotatedWith(GoogleRecaptchaV2::class),
            [GoogleRecaptchaV2Verification::class],
        );
    }
}
