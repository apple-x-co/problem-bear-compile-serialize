<?php

declare(strict_types=1);

namespace AppCore\Domain\AdminSession;

/**
 * @SuppressWarnings(PHPMD.LongClassName)
 * @SuppressWarnings(PHPMD.LongVariable)
 */
interface AdminPasswordProtectSessionStoreInterface
{
    public function get(): AdminPasswordProtectSession;

    public function update(AdminPasswordProtectSession $adminPasswordProtectSession): void;
}
