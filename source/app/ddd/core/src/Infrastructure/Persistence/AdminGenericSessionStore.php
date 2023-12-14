<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\AdminSession\AdminGenericSession;
use AppCore\Domain\AdminSession\AdminGenericSessionStoreInterface;
use Aura\Session\Session as AuraSession;

class AdminGenericSessionStore implements AdminGenericSessionStoreInterface
{
    private const SEGMENT = 'App\Session\Admin\Generic';

    public function __construct(private readonly AuraSession $session)
    {
    }

    public function get(): AdminGenericSession
    {
        $segment = $this->session->getSegment(self::SEGMENT);

        return new AdminGenericSession($segment->get('continueUrlPath'));
    }

    public function update(AdminGenericSession $adminGenericSession): void
    {
        $segment = $this->session->getSegment(self::SEGMENT);
        $segment->set('continueUrlPath', $adminGenericSession->continueUrlPath);
    }
}
