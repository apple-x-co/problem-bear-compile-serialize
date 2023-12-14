<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use AppCore\Domain\AdminSession\AdminGenericSessionStoreInterface;
use AppCore\Domain\AdminSession\AdminPasswordProtectSessionStoreInterface;
use AppCore\Domain\Cart\CartStoreInterface;
use AppCore\Domain\CustomerSession\CustomerGenericSessionStoreInterface;
use AppCore\Domain\SalonSession\SalonGenericSessionStoreInterface;
use AppCore\Domain\SalonSession\SalonPasswordProtectSessionStoreInterface;
use AppCore\Presentation\Shared\EncryptCookiesInterface;
use MyVendor\MyProject\Annotation\Qualifier\GoogleRecaptchaSecretKey;
use MyVendor\MyProject\Annotation\Qualifier\GoogleRecaptchaSiteKey;
use MyVendor\MyProject\Auth\AdminAuthenticatorInterface;
use MyVendor\MyProject\Auth\CustomerAuthenticatorInterface;
use MyVendor\MyProject\Auth\SalonAuthenticatorInterface;
use MyVendor\MyProject\Form\UploadFilesInterface;
use Qiq\Helpers;
use Ray\Di\AbstractModule;
use Ray\WebFormModule\FormInterface;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class DefaultModule extends AbstractModule
{
    protected function configure(): void
    {
        $this->bind(UploadFilesInterface::class)->toNull();

        $this->bind()->annotatedWith('qiq_extension')->toInstance('.php');
        $this->bind()->annotatedWith('qiq_paths')->toInstance([]);
        $this->bind(Helpers::class)->to(Helpers::class);

        $this->bind()->annotatedWith(GoogleRecaptchaSiteKey::class)->toInstance('');
        $this->bind()->annotatedWith(GoogleRecaptchaSecretKey::class)->toInstance('');

        $this->bind(EncryptCookiesInterface::class)->toNull();

//        $this->bind(ThrottleInterface::class)->toNull();

        $this->admin();
        $this->store();
        $this->salon();
    }

    private function admin(): void
    {
        $this->bind(AdminGenericSessionStoreInterface::class)->toNull();
        $this->bind(AdminPasswordProtectSessionStoreInterface::class)->toNull();

        $this->bind(AdminAuthenticatorInterface::class)->toNull();

        $this->bind(FormInterface::class)->annotatedWith('admin_login_form')->toNull();
        $this->bind(FormInterface::class)->annotatedWith('admin_password_form')->toNull();
    }

    private function store(): void
    {
        $this->bind(CustomerGenericSessionStoreInterface::class)->toNull();
        $this->bind(CartStoreInterface::class)->toNull();

        $this->bind(CustomerAuthenticatorInterface::class)->toNull();

        $this->bind(FormInterface::class)->annotatedWith('customer_forgot_password_form')->toNull();
        $this->bind(FormInterface::class)->annotatedWith('customer_login_form')->toNull();
        $this->bind(FormInterface::class)->annotatedWith('customer_reset_password_form')->toNull();
        $this->bind(FormInterface::class)->annotatedWith('customer_sign_up_form')->toNull();
    }

    private function salon(): void
    {
        $this->bind(SalonGenericSessionStoreInterface::class)->toNull();
        $this->bind(SalonPasswordProtectSessionStoreInterface::class)->toNull();

        $this->bind(SalonAuthenticatorInterface::class)->toNull();

        $this->bind(FormInterface::class)->annotatedWith('salon_login_form')->toNull();
        $this->bind(FormInterface::class)->annotatedWith('salon_password_form')->toNull();
    }
}
