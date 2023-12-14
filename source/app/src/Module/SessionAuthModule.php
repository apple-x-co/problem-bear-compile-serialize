<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use MyVendor\MyProject\Annotation\AdminGuard;
use MyVendor\MyProject\Annotation\AdminLogin;
use MyVendor\MyProject\Annotation\AdminLogout;
use MyVendor\MyProject\Annotation\AdminPasswordLock;
use MyVendor\MyProject\Annotation\AdminPasswordProtect;
use MyVendor\MyProject\Annotation\AdminVerifyPassword;
use MyVendor\MyProject\Annotation\CustomerGuard;
use MyVendor\MyProject\Annotation\CustomerLogin;
use MyVendor\MyProject\Annotation\CustomerLogout;
use MyVendor\MyProject\Annotation\MeGuard;
use MyVendor\MyProject\Annotation\Qualifier\Cookie;
use MyVendor\MyProject\Annotation\Qualifier\SessionName;
use MyVendor\MyProject\Annotation\RequiredPermission;
use MyVendor\MyProject\Annotation\SalonGuard;
use MyVendor\MyProject\Annotation\SalonLogin;
use MyVendor\MyProject\Annotation\SalonLogout;
use MyVendor\MyProject\Annotation\SalonPasswordLock;
use MyVendor\MyProject\Annotation\SalonPasswordProtect;
use MyVendor\MyProject\Annotation\SalonVerifyPassword;
use MyVendor\MyProject\Auth\AdminAuthenticatorInterface;
use MyVendor\MyProject\Auth\CustomerAuthenticatorInterface;
use MyVendor\MyProject\Auth\SalonAuthenticatorInterface;
use MyVendor\MyProject\Interceptor\AdminAuthentication;
use MyVendor\MyProject\Interceptor\AdminAuthGuardian;
use MyVendor\MyProject\Interceptor\AdminAuthorization;
use MyVendor\MyProject\Interceptor\AdminKeepAuthenticated;
use MyVendor\MyProject\Interceptor\AdminPasswordProtector;
use MyVendor\MyProject\Interceptor\CustomerAuthentication;
use MyVendor\MyProject\Interceptor\CustomerAuthGuardian;
use MyVendor\MyProject\Interceptor\MeAuthGuardian;
use MyVendor\MyProject\Interceptor\SalonAuthentication;
use MyVendor\MyProject\Interceptor\SalonAuthGuardian;
use MyVendor\MyProject\Interceptor\SalonAuthorization;
use MyVendor\MyProject\Interceptor\SalonKeepAuthenticated;
use MyVendor\MyProject\Interceptor\SalonPasswordProtector;
use MyVendor\MyProject\Provider\AdminAuthenticatorProvider;
use MyVendor\MyProject\Provider\CookieProvider;
use MyVendor\MyProject\Provider\CustomerAuthenticatorProvider;
use MyVendor\MyProject\Provider\SalonAuthenticatorProvider;
use MyVendor\MyProject\Resource\Page\BaseAdminPage;
use MyVendor\MyProject\Resource\Page\BaseSalonPage;
use MyVendor\MyProject\Resource\Page\BaseStoreMeApi;
use MyVendor\MyProject\Resource\Page\BaseStorePage;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

use function session_name;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class SessionAuthModule extends AbstractModule
{
    protected function configure(): void
    {
        $this->bind()
            ->annotatedWith(Cookie::class)
            ->toProvider(CookieProvider::class);

        $this->bind()
            ->annotatedWith(SessionName::class)
            ->toInstance(session_name());

        $this->admin();
        $this->salon();
        $this->store();
    }

    private function admin(): void
    {
        $this->bind(AdminAuthenticatorInterface::class)
            ->toProvider(AdminAuthenticatorProvider::class)
            ->in(Scope::SINGLETON);

        $this->bindInterceptor(
            $this->matcher->subclassesOf(BaseAdminPage::class),
            $this->matcher->logicalAnd(
                $this->matcher->startsWith('on'),
                $this->matcher->logicalOr(
                    $this->matcher->logicalOr(
                        $this->matcher->annotatedWith(AdminLogin::class),
                        $this->matcher->annotatedWith(AdminLogout::class),
                    ),
                    $this->matcher->annotatedWith(AdminVerifyPassword::class),
                ),
            ),
            [AdminAuthentication::class],
        );

        $this->bind(AdminKeepAuthenticated::class); // 複数の Interceptor を渡すと Untargeted が発生するので事前に束縛をする
        $this->bind(AdminAuthGuardian::class); // 複数の Interceptor を渡すと Untargeted が発生するので事前に束縛をする
        $this->bindInterceptor(
            $this->matcher->subclassesOf(BaseAdminPage::class),
            $this->matcher->annotatedWith(AdminGuard::class),
            [AdminKeepAuthenticated::class, AdminAuthGuardian::class],
        );

        $this->bindInterceptor(
            $this->matcher->subclassesOf(BaseAdminPage::class),
            $this->matcher->logicalOr(
                $this->matcher->annotatedWith(AdminPasswordProtect::class),
                $this->matcher->annotatedWith(AdminPasswordLock::class),
            ),
            [AdminPasswordProtector::class],
        );

        $this->bindInterceptor(
            $this->matcher->subclassesOf(BaseAdminPage::class),
            $this->matcher->annotatedWith(RequiredPermission::class),
            [AdminAuthorization::class],
        );
    }

    private function salon(): void
    {
        $this->bind(SalonAuthenticatorInterface::class)
            ->toProvider(SalonAuthenticatorProvider::class)
            ->in(Scope::SINGLETON);

        $this->bindInterceptor(
            $this->matcher->subclassesOf(BaseSalonPage::class),
            $this->matcher->logicalAnd(
                $this->matcher->startsWith('on'),
                $this->matcher->logicalOr(
                    $this->matcher->logicalOr(
                        $this->matcher->annotatedWith(SalonLogin::class),
                        $this->matcher->annotatedWith(SalonLogout::class),
                    ),
                    $this->matcher->annotatedWith(SalonVerifyPassword::class),
                ),
            ),
            [SalonAuthentication::class],
        );

        $this->bind(SalonKeepAuthenticated::class); // 複数の Interceptor を渡すと Untargeted が発生するので事前に束縛をする
        $this->bind(SalonAuthGuardian::class); // 複数の Interceptor を渡すと Untargeted が発生するので事前に束縛をする
        $this->bindInterceptor(
            $this->matcher->subclassesOf(BaseSalonPage::class),
            $this->matcher->annotatedWith(SalonGuard::class),
            [SalonKeepAuthenticated::class, SalonAuthGuardian::class],
        );

        $this->bindInterceptor(
            $this->matcher->subclassesOf(BaseSalonPage::class),
            $this->matcher->logicalOr(
                $this->matcher->annotatedWith(SalonPasswordProtect::class),
                $this->matcher->annotatedWith(SalonPasswordLock::class),
            ),
            [SalonPasswordProtector::class],
        );

        $this->bindInterceptor(
            $this->matcher->subclassesOf(BaseSalonPage::class),
            $this->matcher->annotatedWith(RequiredPermission::class),
            [SalonAuthorization::class],
        );
    }

    private function store(): void
    {
        $this->bind(CustomerAuthenticatorInterface::class)
            ->toProvider(CustomerAuthenticatorProvider::class)
            ->in(Scope::SINGLETON);

        $this->bindInterceptor(
            $this->matcher->subclassesOf(BaseStorePage::class),
            $this->matcher->annotatedWith(CustomerLogin::class),
            [CustomerAuthentication::class],
        );

        $this->bindInterceptor(
            $this->matcher->subclassesOf(BaseStorePage::class),
            $this->matcher->annotatedWith(CustomerLogout::class),
            [CustomerAuthentication::class],
        );

        $this->bindInterceptor(
            $this->matcher->subclassesOf(BaseStorePage::class),
            $this->matcher->annotatedWith(CustomerGuard::class),
            [CustomerAuthGuardian::class],
        );

        $this->bindInterceptor(
            $this->matcher->subclassesOf(BaseStoreMeApi::class),
            $this->matcher->annotatedWith(MeGuard::class),
            [MeAuthGuardian::class],
        );
    }
}
