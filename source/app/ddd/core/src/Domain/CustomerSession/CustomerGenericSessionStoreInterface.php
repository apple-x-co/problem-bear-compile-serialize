<?php

declare(strict_types=1);

namespace AppCore\Domain\CustomerSession;

/** @SuppressWarnings(PHPMD.LongVariable) */
interface CustomerGenericSessionStoreInterface
{
    public function get(): CustomerGenericSession;

    public function update(CustomerGenericSession $customerGenericSession): void;
}
