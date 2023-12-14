<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\SalonSession\SalonPasswordLocking;
use AppCore\Domain\SalonSession\SalonPasswordProtectSession;
use AppCore\Domain\SalonSession\SalonPasswordProtectSessionStoreInterface;
use Aura\Session\Session as AuraSession;
use DateTimeImmutable;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.LongVariable)
 */
final class SalonPasswordProtectSessionStore implements SalonPasswordProtectSessionStoreInterface
{
    private const SEGMENT = 'App\Session\Salon\PasswordProtect';

    public function __construct(private readonly AuraSession $session)
    {
    }

    public function get(): SalonPasswordProtectSession
    {
        $segment = $this->session->getSegment(self::SEGMENT);

        $locking = $segment->get('locking');
        $expireDate = $segment->get('expireDate');

        return new SalonPasswordProtectSession(
            $segment->get('continueUrlPath'),
            empty($locking) ? null : SalonPasswordLocking::from($locking),
            empty($expireDate) ? null : (new DateTimeImmutable())->setTimestamp($expireDate),
        );
    }

    public function update(SalonPasswordProtectSession $salonPasswordProtectSession): void
    {
        $segment = $this->session->getSegment(self::SEGMENT);
        $segment->set('continueUrlPath', $salonPasswordProtectSession->continueUrlPath);
        $segment->set('locking', $salonPasswordProtectSession->locking?->value);
        $segment->set('expireDate', $salonPasswordProtectSession->expireDate?->getTimestamp());
    }
}
