<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\AdminSession\AdminPasswordLocking;
use AppCore\Domain\AdminSession\AdminPasswordProtectSession;
use AppCore\Domain\AdminSession\AdminPasswordProtectSessionStoreInterface;
use Aura\Session\Session as AuraSession;
use DateTimeImmutable;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.LongVariable)
 */
final class AdminPasswordProtectSessionStore implements AdminPasswordProtectSessionStoreInterface
{
    private const SEGMENT = 'App\Session\Admin\PasswordProtect';

    public function __construct(private readonly AuraSession $session)
    {
    }

    public function get(): AdminPasswordProtectSession
    {
        $segment = $this->session->getSegment(self::SEGMENT);

        $locking = $segment->get('locking');
        $expireDate = $segment->get('expireDate');

        return new AdminPasswordProtectSession(
            $segment->get('continueUrlPath'),
            empty($locking) ? null : AdminPasswordLocking::from($locking),
            empty($expireDate) ? null : (new DateTimeImmutable())->setTimestamp($expireDate),
        );
    }

    public function update(AdminPasswordProtectSession $adminPasswordProtectSession): void
    {
        $segment = $this->session->getSegment(self::SEGMENT);
        $segment->set('continueUrlPath', $adminPasswordProtectSession->continueUrlPath);
        $segment->set('locking', $adminPasswordProtectSession->locking?->value);
        $segment->set('expireDate', $adminPasswordProtectSession->expireDate?->getTimestamp());
    }
}
