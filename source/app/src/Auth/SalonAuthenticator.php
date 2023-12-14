<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Auth;

use AppCore\Domain\AccessControl\AccessControl;
use AppCore\Domain\Encrypter\EncrypterInterface;
use AppCore\Domain\Hasher\PasswordHasherInterface;
use AppCore\Domain\SecureRandom\SecureRandomInterface;
use AppCore\Infrastructure\Query\StaffMemberLoginAttemptCommandInterface;
use AppCore\Infrastructure\Query\StaffMemberLoginAttemptQueryInterface;
use AppCore\Infrastructure\Query\StaffMemberLoginTokenCommandInterface;
use AppCore\Infrastructure\Query\StaffMemberLoginTokenQueryInterface;
use AppCore\Infrastructure\Query\StaffMemberQueryInterface;
use Aura\Auth\Adapter\PdoAdapter;
use Aura\Auth\Auth;
use Aura\Auth\AuthFactory;
use Aura\Auth\Service\LoginService;
use Aura\Auth\Service\LogoutService;
use Aura\Auth\Service\ResumeService;
use Aura\Auth\Verifier\PasswordVerifier;
use DateTimeImmutable;
use PDO;
use Throwable;

use function assert;
use function explode;
use function is_int;
use function is_string;
use function setcookie;
use function time;

/**
 * Salon 認証基盤
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class SalonAuthenticator implements SalonAuthenticatorInterface
{
    private const REMEMBER_SEPARATOR = ':';
    private const ATTEMPT_MAX_TIMES = 5;
    private const ACCOUNT_LOCK_MINUTES = '+10 minutes';

    /**
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function __construct(
        private readonly StaffMemberQueryInterface $staffMemberQuery,
        private readonly StaffMemberLoginAttemptCommandInterface $staffMemberLoginAttemptCommand,
        private readonly StaffMemberLoginAttemptQueryInterface $staffMemberLoginAttemptQuery,
        private readonly StaffMemberLoginTokenCommandInterface $staffMemberLoginTokenCommand,
        private readonly StaffMemberLoginTokenQueryInterface $staffMemberLoginTokenQuery,
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
                'staff_members.email',
                'staff_members.password',
                'staff_members.id', // as UserData[0]
                'staff_members.name AS display_name', // as UserData[1]
            ],
            'staff_members',
            'staff_members.status = \'public\' ',
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
        if (! $this->canAttemptLogin($username)) {
            throw new AttemptException();
        }

        $auth = $this->auth();
        $loginService = $this->loginService();
        try {
            $loginService->login(
                $auth,
                ['username' => $username, 'password' => $password],
            );
        } catch (Throwable $throwable) {
            $this->writeAttemptLogin($username, $throwable);

            throw $throwable;
        }

        $staffMemberId = $this->getUserId();
        if ($staffMemberId === null) {
            return;
        }

        $this->clearRemember($staffMemberId);
        $this->deleteAttemptLogin($username);
    }

    public function rememberLogin(string $username, string $password): void
    {
        if (! $this->canAttemptLogin($username)) {
            throw new AttemptException();
        }

        $auth = $this->auth();
        $loginService = $this->loginService();
        try {
            $loginService->login(
                $auth,
                ['username' => $username, 'password' => $password],
            );
        } catch (Throwable $throwable) {
            $this->writeAttemptLogin($username, $throwable);

            throw $throwable;
        }

        $staffMemberId = $this->getUserId();
        if ($staffMemberId === null) {
            return;
        }

        $this->setUpRemember($staffMemberId);
        $this->deleteAttemptLogin($username);
    }

    public function getRememberCookieName(): string
    {
        return $this->rememberCookieName;
    }

    public function continueLogin(string $payload): bool
    {
        [$encrypted, $token] = explode(self::REMEMBER_SEPARATOR, $payload);

        $staffMemberLoginTokenItem = $this->staffMemberLoginTokenQuery->itemByToken($token);
        if ($staffMemberLoginTokenItem === null) {
            return false;
        }

        $now = new DateTimeImmutable();
        $staffMemberId = (int) $staffMemberLoginTokenItem['staff_member_id'];
        assert($staffMemberId > 0);
        $expireDate = new DateTimeImmutable($staffMemberLoginTokenItem['expire_date']);

        $decrypted = $this->encrypter->decrypt($encrypted);
        if (($decrypted !== (string) $staffMemberId) || ($expireDate < $now)) {
            $this->clearRemember($staffMemberId);

            return false;
        }

        $staffMemberItem = $this->staffMemberQuery->item($staffMemberId);
        if ($staffMemberItem === null) {
            return false;
        }

        $auth = $this->auth();
        $loginService = $this->loginService();
        /** @psalm-suppress InvalidArgument */
        $loginService->forceLogin(
            $auth,
            $staffMemberItem['email'],
            ['id' => $staffMemberItem['id'], 'display_name' => $staffMemberItem['name']],
        );

        $this->clearRemember($staffMemberId);
        $this->setUpRemember($staffMemberId);

        return true;
    }

    public function verifyPassword(string $username, string $password): void
    {
        $pdoAdapter = $this->adapter();
        $pdoAdapter->login(['username' => $username, 'password' => $password]);
    }

    public function logout(): void
    {
        $staffMemberId = $this->getUserId();
        if ($staffMemberId !== null) {
            $this->clearRemember($staffMemberId);
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
            $staffMemberId = (int) $userData['id'];
            assert($staffMemberId > 0);

            return $staffMemberId;
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

    /** @param int<1, max> $staffMemberId */
    private function setUpRemember(int $staffMemberId): void
    {
        $token = $this->secureRandom->hmac($this->secureRandom->randomBytes(32));
        $expireAt = (new DateTimeImmutable())->modify('+8 days');

        $this->staffMemberLoginTokenCommand->add(
            $staffMemberId,
            $token,
            $expireAt,
        );

        $encrypted = $this->encrypter->encrypt((string) $staffMemberId);

        setcookie(
            $this->rememberCookieName,
            $encrypted . self::REMEMBER_SEPARATOR . $token,
            [
                'expires' => $expireAt->getTimestamp(),
                'path' => '/salon/',
                'httponly' => true,
            ],
        );
    }

    /** @param int<1, max> $staffMemberId */
    private function clearRemember(int $staffMemberId): void
    {
        $this->staffMemberLoginTokenCommand->deleteByStaffMemberId($staffMemberId);

        setcookie(
            $this->rememberCookieName,
            '',
            [
                'expires' => time() - 1,
                'path' => '/',
                'httponly' => true,
            ],
        );
    }

    private function writeAttemptLogin(string $email, Throwable $throwable): void
    {
        $now = new DateTimeImmutable();

        $item = $this->staffMemberLoginAttemptQuery->itemByEmail($email);
        if ($item === null) {
            $this->staffMemberLoginAttemptCommand->add(
                $email,
                $throwable::class,
                $now,
                $now->modify(self::ACCOUNT_LOCK_MINUTES),
            );

            return;
        }

        $id = (int) $item['id'];
        assert($id > 0);

        $this->staffMemberLoginAttemptCommand->update(
            $id,
            $throwable::class,
            new DateTimeImmutable(),
            $now->modify(self::ACCOUNT_LOCK_MINUTES),
        );
    }

    private function deleteAttemptLogin(string $email): void
    {
        $this->staffMemberLoginAttemptCommand->deleteByEmail($email);
    }

    private function canAttemptLogin(string $email): bool
    {
        $item = $this->staffMemberLoginAttemptQuery->itemByEmail($email);
        if ($item === null) {
            return true;
        }

        $now = new DateTimeImmutable();
        $numberOfTrials = (int) $item['number_of_trials'];
        $expireDate = new DateTimeImmutable($item['expire_date']);

        return ! ($numberOfTrials > self::ATTEMPT_MAX_TIMES && $expireDate > $now);
    }

    public function getIdentity(): SalonIdentity
    {
        $auth = $this->auth();
        if (! $auth->isValid()) {
            throw new UnauthorizedException();
        }

        $userData = $auth->getUserData();
        assert(isset($userData['id']) && is_int($userData['id']));
        assert(isset($userData['display_name']) && is_string($userData['display_name']));

        return new SalonIdentity(
            $userData['id'],
            $userData['display_name'],
        );
    }

    public function getAccessControl(): AccessControl
    {
        return new AccessControl();
    }
}
