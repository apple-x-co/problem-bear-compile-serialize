<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Provider;

use AppCore\Domain\Encrypter\EncrypterInterface;
use AppCore\Domain\Hasher\PasswordHasherInterface;
use AppCore\Domain\SecureRandom\SecureRandomInterface;
use AppCore\Infrastructure\Query\AdminLoginTokenCommandInterface;
use AppCore\Infrastructure\Query\AdminLoginTokenQueryInterface;
use AppCore\Infrastructure\Query\AdminQueryInterface;
use Aura\Auth\AuthFactory;
use Aura\Auth\Session\Segment;
use Aura\Auth\Session\Session;
use MyVendor\MyProject\Annotation\Qualifier\Cookie;
use MyVendor\MyProject\Annotation\Qualifier\PdoDns;
use MyVendor\MyProject\Annotation\Qualifier\PdoPassword;
use MyVendor\MyProject\Annotation\Qualifier\PdoUsername;
use MyVendor\MyProject\Auth\AdminAuthenticator;
use Ray\Di\ProviderInterface;

use function ini_get;
use function sha1;

/**
 * Admin 認証基盤を提供
 *
 * @implements ProviderInterface<AdminAuthenticator>
 */
class AdminAuthenticatorProvider implements ProviderInterface
{
    /**
     * @param array<array-key, mixed> $cookie
     *
     * @SuppressWarnings(PHPMD.LongVariable)
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        private readonly AdminQueryInterface $adminQuery,
        private readonly AdminLoginTokenCommandInterface $adminLoginTokenCommand,
        private readonly AdminLoginTokenQueryInterface $adminLoginTokenQuery,
        private readonly EncrypterInterface $encrypter,
        private readonly PasswordHasherInterface $passwordHasher,
        private readonly SecureRandomInterface $secureRandom,
        #[Cookie]
        private readonly array $cookie,
        #[PdoDns]
        private readonly string $pdoDsn,
        #[PdoUsername]
        private readonly string $pdoUsername,
        #[PdoPassword]
        private readonly string $pdoPassword,
    ) {
    }

    /**
     * AdminAuthenticator を生成する
     *
     * Provider の中で loginService や logoutService などを生成すると
     * PdoAdapter が動いてしまいDB接続がない環境（たとえば GitHub Actions）で PDOException が発生する
     * そのため各 service は Authenticator の中で生成する。
     *
     * {@inheritDoc}
     */
    public function get()
    {
        $authSession = new Session($this->cookie);
        $authSegment = new Segment('App\Auth\Admin');
        $authFactory = new AuthFactory($this->cookie, $authSession, $authSegment);

        $rememberCookieName = sha1(AdminAuthenticator::class);

        return new AdminAuthenticator(
            $this->adminQuery,
            $this->adminLoginTokenCommand,
            $this->adminLoginTokenQuery,
            $this->encrypter,
            $this->passwordHasher,
            $this->secureRandom,
            $rememberCookieName,
            $authFactory,
            (int) ini_get('session.gc_maxlifetime'),
            $this->pdoDsn,
            $this->pdoUsername,
            $this->pdoPassword,
            '/admin/index',
            '/admin/login',
            '/admin/password-confirm',
        );
    }
}
