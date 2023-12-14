<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Auth;

use AppCore\Domain\AccessControl\AccessControl;
use AppCore\Domain\Encrypter\EncrypterInterface;
use AppCore\Domain\Hasher\PasswordHasherInterface;
use AppCore\Domain\SecureRandom\SecureRandomInterface;
use AppCore\Infrastructure\Query\AdminLoginTokenCommandInterface;
use AppCore\Infrastructure\Query\AdminLoginTokenQueryInterface;
use AppCore\Infrastructure\Query\AdminQueryInterface;
use Aura\Auth\Adapter\PdoAdapter;
use Aura\Auth\Auth;
use Aura\Auth\AuthFactory;
use Aura\Auth\Service\LoginService;
use Aura\Auth\Service\LogoutService;
use Aura\Auth\Service\ResumeService;
use Aura\Auth\Verifier\PasswordVerifier;
use DateTimeImmutable;
use PDO;

use function assert;
use function explode;
use function is_int;
use function is_string;
use function setcookie;
use function time;

/**
 * Admin 認証基盤
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AdminAuthenticator implements AdminAuthenticatorInterface
{
    private const REMEMBER_SEPARATOR = ':';

    /**
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function __construct(
        private readonly AdminQueryInterface $adminQuery,
        private readonly AdminLoginTokenCommandInterface $adminLoginTokenCommand,
        private readonly AdminLoginTokenQueryInterface $adminLoginTokenQuery,
        private readonly EncrypterInterface $encrypter,
        private readonly PasswordHasherInterface $passwordHasher,
        private readonly SecureRandomInterface $secureRandom,
        private readonly string $rememberCookieName,
        private readonly AuthFactory $authFactory,
        private readonly int $sessionGcMaxlifetime,
        private readonly string $pdoDsn,
        private readonly string $pdoUsername,
        private readonly string $pdoPassword,
        private readonly string $authRedirect,
        private readonly string $unauthRedirect,
        private readonly string $passwordRedirect,
    ) {
    }

    private function adapter(): PdoAdapter
    {
        return $this->authFactory->newPdoAdapter(
            new PDO($this->pdoDsn, $this->pdoUsername, $this->pdoPassword),
            new PasswordVerifier($this->passwordHasher->hashType()),
            [
                'admins.username',
                'admins.password',
                'admins.id', // as UserData[0]
                'admins.name AS display_name', // as UserData[1]
            ],
            'admins',
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

        $adminId = $this->getUserId();
        if ($adminId === null) {
            return;
        }

        $this->clearRemember($adminId);
    }

    public function rememberLogin(string $username, string $password): void
    {
        $auth = $this->auth();
        $loginService = $this->loginService();
        $loginService->login(
            $auth,
            ['username' => $username, 'password' => $password],
        );

        $adminId = $this->getUserId();
        if ($adminId === null) {
            return;
        }

        $this->setUpRemember($adminId);
    }

    public function getRememberCookieName(): string
    {
        return $this->rememberCookieName;
    }

    public function continueLogin(string $payload): bool
    {
        [$encrypted, $token] = explode(self::REMEMBER_SEPARATOR, $payload);

        $adminLoginTokenItem = $this->adminLoginTokenQuery->itemByToken($token);
        if ($adminLoginTokenItem === null) {
            return false;
        }

        $now = new DateTimeImmutable();
        $adminId = (int) $adminLoginTokenItem['admin_id'];
        assert($adminId > 0);
        $expireDate = new DateTimeImmutable($adminLoginTokenItem['expire_date']);

        $decrypted = $this->encrypter->decrypt($encrypted);
        if (($decrypted !== (string) $adminId) || ($expireDate < $now)) {
            $this->clearRemember($adminId);

            return false;
        }

        $adminItem = $this->adminQuery->item($adminId);
        if ($adminItem === null) {
            return false;
        }

        $auth = $this->auth();
        $loginService = $this->loginService();
        /** @psalm-suppress InvalidArgument */
        $loginService->forceLogin(
            $auth,
            $adminItem['username'],
            ['id' => $adminItem['id'], 'display_name' => $adminItem['name']],
        );

        $this->clearRemember($adminId);
        $this->setUpRemember($adminId);

        return true;
    }

    public function verifyPassword(string $username, string $password): void
    {
        $pdoAdapter = $this->adapter();
        $pdoAdapter->login(['username' => $username, 'password' => $password]);
    }

    public function logout(): void
    {
        $adminId = $this->getUserId();
        if ($adminId !== null) {
            $this->clearRemember($adminId);
        }

        $this->logoutService()->forceLogout($this->auth());
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

    public function getDisplayName(): string|null
    {
        $userData = $this->getUserData();

        return $userData['display_name'] ?? null;
    }

    public function getUserId(): int|null
    {
        $userData = $this->getUserData();
        if (isset($userData['id'])) {
            $adminId = (int) $userData['id'];
            assert($adminId > 0);

            return $adminId;
        }

        return null;
    }

    /** {@inheritdoc} */
    public function getUserData(): array
    {
        return $this->auth()->getUserData();
    }

    public function getAuthRedirect(): string
    {
        return $this->authRedirect;
    }

    public function getUnauthRedirect(): string
    {
        return $this->unauthRedirect;
    }

    public function getPasswordRedirect(): string
    {
        return $this->passwordRedirect;
    }

    /** @param int<1, max> $adminId */
    private function setUpRemember(int $adminId): void
    {
        $token = $this->secureRandom->hmac($this->secureRandom->randomBytes(32));
        $expireAt = (new DateTimeImmutable())->modify('+8 days');

        $this->adminLoginTokenCommand->add(
            $adminId,
            $token,
            $expireAt,
        );

        $encrypted = $this->encrypter->encrypt((string) $adminId);

        setcookie(
            $this->rememberCookieName,
            $encrypted . self::REMEMBER_SEPARATOR . $token,
            [
                'expires' => $expireAt->getTimestamp(),
                'path' => '/',
                'httponly' => true,
            ],
        );
    }

    /** @param int<1, max> $adminId */
    private function clearRemember(int $adminId): void
    {
        $this->adminLoginTokenCommand->deleteByAdminId($adminId);

        setcookie(
            $this->rememberCookieName,
            '',
            [
                'expires' => time() - 1,
                'path' => '/admin/',
                'httponly' => true,
            ],
        );
    }

    public function getIdentity(): AdminIdentity
    {
        $auth = $this->auth();
        if (! $auth->isValid()) {
            throw new UnauthorizedException();
        }

        $userData = $auth->getUserData();
        assert(isset($userData['id']) && is_int($userData['id']));
        assert(isset($userData['display_name']) && is_string($userData['display_name']));

        return new AdminIdentity(
            $userData['id'],
            $userData['display_name'],
        );
    }

    public function getAccessControl(): AccessControl
    {
        return new AccessControl();
    }
}
