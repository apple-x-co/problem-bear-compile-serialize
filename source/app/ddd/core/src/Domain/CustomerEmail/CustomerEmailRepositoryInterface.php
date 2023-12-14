<?php

declare(strict_types=1);

namespace AppCore\Domain\CustomerEmail;

interface CustomerEmailRepositoryInterface
{
    public function findByToken(string $token): CustomerEmail;

    public function insert(CustomerEmail $customerEmail): void;

    public function update(CustomerEmail $customerEmail): void;
}
