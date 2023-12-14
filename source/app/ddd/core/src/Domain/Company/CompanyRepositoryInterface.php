<?php

declare(strict_types=1);

namespace AppCore\Domain\Company;

interface CompanyRepositoryInterface
{
    /** @param int<1, max> $id */
    public function findById(int $id): Company;

    public function insert(Company $company): void;

    public function update(Company $company): void;
}
