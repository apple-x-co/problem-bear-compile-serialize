<?php

declare(strict_types=1);

namespace AppCore\Domain\SalonSession;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.LongClassName)
 */
interface SalonPasswordProtectSessionStoreInterface
{
    public function get(): SalonPasswordProtectSession;

    public function update(SalonPasswordProtectSession $salonPasswordProtectSession): void;
}
