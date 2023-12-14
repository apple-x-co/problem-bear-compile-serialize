<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\CustomerSession\CustomerGenericSession;
use AppCore\Domain\CustomerSession\CustomerGenericSessionStoreInterface;
use Aura\Session\Session as AuraSession;

/** @SuppressWarnings(PHPMD.LongVariable) */
final class CustomerGenericSessionStore implements CustomerGenericSessionStoreInterface
{
    private const SEGMENT = 'App\Session\Customer\Generic';

    public function __construct(private readonly AuraSession $session)
    {
    }

    public function get(): CustomerGenericSession
    {
        $segment = $this->session->getSegment(self::SEGMENT);

        return new CustomerGenericSession($segment->get('continueUrlPath'));
    }

    public function update(CustomerGenericSession $customerGenericSession): void
    {
        $segment = $this->session->getSegment(self::SEGMENT);
        $segment->set('continueUrlPath', $customerGenericSession->continueUrlPath);
    }
}
