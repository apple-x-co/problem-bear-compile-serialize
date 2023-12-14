<?php

declare(strict_types=1);

namespace AppCore\Domain\Customer;

interface CustomerRepositoryInterface
{
    /** @param int<1, max> $id */
    public function findById(int $id): Customer;

    public function findByEmail(string $email): Customer|null;

    public function insert(Customer $customer): void;

    public function update(Customer $customer): void;
}
