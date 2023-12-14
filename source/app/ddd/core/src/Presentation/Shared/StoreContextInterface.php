<?php

declare(strict_types=1);

namespace AppCore\Presentation\Shared;

interface StoreContextInterface
{
    /** @return int<1, max> */
    public function getStoreId(): int;

    public function getStoreUrl(): string;

    public function getStoreKey(): string;

    public function getStoreName(): string;
}
