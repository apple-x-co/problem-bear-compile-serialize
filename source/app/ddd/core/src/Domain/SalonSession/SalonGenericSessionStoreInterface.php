<?php

declare(strict_types=1);

namespace AppCore\Domain\SalonSession;

interface SalonGenericSessionStoreInterface
{
    public function get(): SalonGenericSession;

    public function update(SalonGenericSession $salonGenericSession): void;
}
