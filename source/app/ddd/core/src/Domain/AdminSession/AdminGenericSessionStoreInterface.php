<?php

declare(strict_types=1);

namespace AppCore\Domain\AdminSession;

interface AdminGenericSessionStoreInterface
{
    public function get(): AdminGenericSession;

    public function update(AdminGenericSession $adminGenericSession): void;
}
