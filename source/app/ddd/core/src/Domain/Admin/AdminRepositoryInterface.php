<?php

declare(strict_types=1);

namespace AppCore\Domain\Admin;

interface AdminRepositoryInterface
{
    /** @param int<1, max> $id */
    public function findById(int $id): Admin;

    public function insert(Admin $admin): void;

    public function update(Admin $admin): void;
}
