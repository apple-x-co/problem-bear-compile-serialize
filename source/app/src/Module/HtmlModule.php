<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use AppCore\Domain\AdminSession\AdminGenericSessionStoreInterface;
use AppCore\Domain\AdminSession\AdminPasswordProtectSessionStoreInterface;
use AppCore\Domain\Cart\CartStoreInterface;
use AppCore\Domain\CustomerSession\CustomerGenericSessionStoreInterface;
use AppCore\Domain\SalonSession\SalonGenericSessionStoreInterface;
use AppCore\Domain\SalonSession\SalonPasswordProtectSessionStoreInterface;
use AppCore\Infrastructure\Persistence\AdminGenericSessionStore;
use AppCore\Infrastructure\Persistence\AdminPasswordProtectSessionStore;
use AppCore\Infrastructure\Persistence\CartStore;
use AppCore\Infrastructure\Persistence\CustomerGenericSessionStore;
use AppCore\Infrastructure\Persistence\SalonGenericSessionStore;
use AppCore\Infrastructure\Persistence\SalonPasswordProtectSessionStore;
use AppCore\Infrastructure\Shared\EncryptCookies;
//use AppCore\Infrastructure\Shared\StoreContext;
use AppCore\Presentation\Shared\EncryptCookiesInterface;
use AppCore\Qualifier\Cookie;
use AppCore\Qualifier\CookieNamePrefix;
use BEAR\Package\AbstractAppModule;
use MyVendor\MyProject\Form\Admin as AdminForm;
use MyVendor\MyProject\Form\Customer as CustomerForm;
use MyVendor\MyProject\Form\Salon as SalonForm;
use MyVendor\MyProject\Provider\CookieProvider;
use MyVendor\MyProject\TemplateEngine\QiqAdminHelpers;
use MyVendor\MyProject\TemplateEngine\QiqCustomHelpers;
use MyVendor\MyProject\TemplateEngine\QiqErrorModule;
use MyVendor\MyProject\TemplateEngine\QiqModule;
use MyVendor\MyProject\TemplateEngine\QiqSalonHelpers;
use MyVendor\MyProject\TemplateEngine\QiqStoreHelpers;
use Qiq\Helpers;
use Ray\Di\Scope;
use Ray\WebFormModule\AuraInputModule;
use Ray\WebFormModule\FormInterface;

use function sha1;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
final class HtmlModule extends AbstractAppModule
{
    protected function configure(): void
    {
        $this->renderer();
        $this->input();
        $this->authentication();
        $this->cookie();

        $this->admin();
        $this->store();
        $this->salon();
    }

    private function renderer(): void
    {
        $this->bind(QiqAdminHelpers::class);
        $this->bind(QiqStoreHelpers::class);
        $this->bind(QiqSalonHelpers::class);
        $this->bind(Helpers::class)->to(QiqCustomHelpers::class);
        $this->install(new QiqModule([$this->appMeta->appDir . '/var/qiq/template']));
        $this->install(new QiqErrorModule('DebugTrace'));
    }

    private function input(): void
    {
        $this->install(new AuraInputModule());
    }

    private function authentication(): void
    {
        $this->install(new SessionAuthModule());
        $this->install(new CaptchaModule());
//        $this->install(new ThrottlingModule());
    }

    private function cookie(): void
    {
        $this->bind()->annotatedWith(Cookie::class)->toProvider(CookieProvider::class);
        $this->bind(EncryptCookiesInterface::class)->to(EncryptCookies::class)->in(Scope::SINGLETON);
        $this->bind()->annotatedWith(CookieNamePrefix::class)->toInstance(sha1(CookieNamePrefix::class));
    }

    private function admin(): void
    {
        //@formatter:off
        $this->bind(AdminGenericSessionStoreInterface::class)->to(AdminGenericSessionStore::class)->in(Scope::SINGLETON);
        $this->bind(AdminPasswordProtectSessionStoreInterface::class)->to(AdminPasswordProtectSessionStore::class)->in(Scope::SINGLETON);

        $this->bind(FormInterface::class)->annotatedWith('admin_login_form')->to(AdminForm\LoginForm::class);
        $this->bind(FormInterface::class)->annotatedWith('admin_password_form')->to(AdminForm\PasswordForm::class);
        //@formatter:on
    }

    private function store(): void
    {
        //@formatter:off
        $this->bind(CustomerGenericSessionStoreInterface::class)->to(CustomerGenericSessionStore::class)->in(Scope::SINGLETON);
        $this->bind(CartStoreInterface::class)->to(CartStore::class)->in(Scope::SINGLETON);

        $this->bind(FormInterface::class)->annotatedWith('customer_forgot_password_form')->to(CustomerForm\ForgotPasswordForm::class);
        $this->bind(FormInterface::class)->annotatedWith('customer_login_form')->to(CustomerForm\LoginForm::class);
        $this->bind(FormInterface::class)->annotatedWith('customer_reset_password_form')->to(CustomerForm\ResetPasswordForm::class);
        $this->bind(FormInterface::class)->annotatedWith('customer_sign_up_form')->to(CustomerForm\SignUpForm::class);

//        $this->bind(StoreContext::class);
        //@formatter:on
    }

    private function salon(): void
    {
        //@formatter:off
        $this->bind(SalonGenericSessionStoreInterface::class)->to(SalonGenericSessionStore::class)->in(Scope::SINGLETON);
        $this->bind(SalonPasswordProtectSessionStoreInterface::class)->to(SalonPasswordProtectSessionStore::class)->in(Scope::SINGLETON);

        $this->bind(FormInterface::class)->annotatedWith('salon_login_form')->to(SalonForm\LoginForm::class);
        $this->bind(FormInterface::class)->annotatedWith('salon_password_form')->to(SalonForm\PasswordForm::class);
        //@formatter:on
    }
}
