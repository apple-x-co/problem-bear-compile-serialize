<?php

declare(strict_types=1);

namespace AppCore\Domain\Tax;

interface TaxRepositoryInterface
{
    /** @param int<1, max> $id */
    public function findById(int $id): Tax;

    /** @return list<Tax> */
    public function findAll(): array;

    public function insert(Tax $tax): void;

    public function update(Tax $tax): void;
}
