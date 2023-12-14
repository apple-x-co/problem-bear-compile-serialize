<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\SalonSession\SalonGenericSession;
use AppCore\Domain\SalonSession\SalonGenericSessionStoreInterface;
use Aura\Session\Session as AuraSession;

class SalonGenericSessionStore implements SalonGenericSessionStoreInterface
{
    private const SEGMENT = 'App\Session\Salon\Generic';

    public function __construct(private readonly AuraSession $session)
    {
    }

    public function get(): SalonGenericSession
    {
        $segment = $this->session->getSegment(self::SEGMENT);

        return new SalonGenericSession($segment->get('continueUrlPath'));
    }

    public function update(SalonGenericSession $salonGenericSession): void
    {
        $segment = $this->session->getSegment(self::SEGMENT);
        $segment->set('continueUrlPath', $salonGenericSession->continueUrlPath);
    }
}
