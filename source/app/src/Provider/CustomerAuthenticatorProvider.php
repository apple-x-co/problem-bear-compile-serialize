<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Provider;

use AppCore\Domain\Hasher\PasswordHasherInterface;
//use AppCore\Infrastructure\Shared\StoreContext;
use Aura\Auth\AuthFactory;
use Aura\Auth\Session\Segment;
use Aura\Auth\Session\Session;
use MyVendor\MyProject\Annotation\Qualifier\Cookie;
use MyVendor\MyProject\Annotation\Qualifier\PdoDns;
use MyVendor\MyProject\Annotation\Qualifier\PdoPassword;
use MyVendor\MyProject\Annotation\Qualifier\PdoUsername;
use MyVendor\MyProject\Auth\CustomerAuthenticator;
//use Ray\Di\Di\Set;
use Ray\Di\ProviderInterface;

use function ini_get;

/**
 * Customer 認証基盤を提供
 *
 * @implements ProviderInterface<CustomerAuthenticator>
 */
class CustomerAuthenticatorProvider implements ProviderInterface
{
    /**
     * @param array<array-key, mixed>         $cookie
     */
    public function __construct(
        private readonly PasswordHasherInterface $passwordHasher,
        #[Cookie]
        private readonly array $cookie,
        #[PdoDns]
        private readonly string $pdoDsn,
        #[PdoUsername]
        private readonly string $pdoUsername,
        #[PdoPassword]
        private readonly string $pdoPassword,
//        #[Set(StoreContext::class)]
//        private readonly ProviderInterface $storeContextProvider,
    ) {
    }

    /**
     * CustomerAuthenticator を生成する
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
        $authSegment = new Segment('App\Auth\Customer');
        $authFactory = new AuthFactory($this->cookie, $authSession, $authSegment);

        return new CustomerAuthenticator(
            $this->passwordHasher,
            $authFactory,
            (int) ini_get('session.gc_maxlifetime'),
            $this->pdoDsn,
            $this->pdoUsername,
            $this->pdoPassword,
            '/index',
            '/login',
//            $this->storeContextProvider,
        );
    }
}
