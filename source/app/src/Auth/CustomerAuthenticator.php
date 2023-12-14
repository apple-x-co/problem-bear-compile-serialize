<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Auth;

use AppCore\Domain\Hasher\PasswordHasherInterface;
//use AppCore\Infrastructure\Shared\StoreContext;
use Aura\Auth\Adapter\PdoAdapter;
use Aura\Auth\Auth;
use Aura\Auth\AuthFactory;
use Aura\Auth\Service\LoginService;
use Aura\Auth\Service\LogoutService;
use Aura\Auth\Service\ResumeService;
use Aura\Auth\Verifier\PasswordVerifier;
use PDO;
//use Ray\Di\ProviderInterface;

use function assert;
use function is_int;
use function is_string;

/** Customer 認証基盤 */
class CustomerAuthenticator implements CustomerAuthenticatorInterface
{
//    /** @param ProviderInterface<StoreContext> $storeContextProvider */
    public function __construct(
        private readonly PasswordHasherInterface $passwordHasher,
        private readonly AuthFactory $authFactory,
        private readonly int $sessionGcMaxlifetime,
        private readonly string $pdoDsn,
        private readonly string $pdoUsername,
        private readonly string $pdoPassword,
        private readonly string $authRedirect,
        private readonly string $unauthRedirect,
//        private readonly ProviderInterface $storeContextProvider,
    ) {
    }

    private function adapter(): PdoAdapter
    {
        return $this->authFactory->newPdoAdapter(
            new PDO($this->pdoDsn, $this->pdoUsername, $this->pdoPassword),
            new PasswordVerifier($this->passwordHasher->hashType()),
            [
                'customers.email',
                'customers.password',
                'customers.id', // as UserData[0]
                'CONCAT(customers.family_name, customers.given_name) AS display_name', // as UserData[1]
            ],
            'customers',
            'customers.status = \'verified\'',
        );
    }

    private function auth(): Auth
    {
        return $this->authFactory->newInstance();
    }

    private function loginService(): LoginService
    {
        return $this->authFactory->newLoginService($this->adapter());
    }

    private function logoutService(): LogoutService
    {
        return $this->authFactory->newLogoutService($this->adapter());
    }

    private function resumeService(): ResumeService
    {
        return $this->authFactory->newResumeService($this->adapter(), $this->sessionGcMaxlifetime);
    }

    public function login(string $username, string $password): void
    {
        $auth = $this->auth();
        $loginService = $this->loginService();
        $loginService->login(
            $auth,
            ['username' => $username, 'password' => $password],
        );
    }

    public function logout(): void
    {
        $auth = $this->auth();
        $this->logoutService()->forceLogout($auth);
    }

    private function resume(): Auth
    {
        $auth = $this->auth();
        $this->resumeService()->resume($auth);

        return $auth;
    }

    public function isValid(): bool
    {
        return $this->resume()->isValid();
    }

    public function isExpired(): bool
    {
        return $this->resume()->isExpired();
    }

    public function getUserName(): string|null
    {
        return $this->auth()->getUserName();
    }

    /**
     * {@inheritdoc}
     */
    public function getUserData(): array
    {
        return $this->auth()->getUserData();
    }

    public function getAuthRedirect(): string
    {
//        $storeContext = $this->storeContextProvider->get();
//
//        return $storeContext->getStoreUrl() . $this->authRedirect;

        return $this->authRedirect;
    }

    public function getUnauthRedirect(): string
    {
//        $storeContext = $this->storeContextProvider->get();
//
//        return $storeContext->getStoreUrl() . $this->unauthRedirect;

        return $this->unauthRedirect;
    }

    public function getIdentity(): CustomerIdentity
    {
        $auth = $this->auth();
        if (! $auth->isValid()) {
            throw new UnauthorizedException();
        }

        $userData = $auth->getUserData();
        assert(isset($userData['id']) && is_int($userData['id']) && $userData['id'] > 0);
        assert(isset($userData['display_name']) && is_string($userData['display_name']));

        return new CustomerIdentity(
            $userData['id'],
            $userData['display_name'],
        );
    }
}
