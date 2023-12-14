<?php

declare(strict_types=1);

namespace AppCore\Domain\Maker;

interface MakerRepositoryInterface
{
    /** @param int<1, max> $id */
    public function findById(int $id): Maker;

    /**
     * @param int<1, max> $storeId
     *
     * @return list<Maker>
     */
    public function findByStoreId(int $storeId): array;

    public function insert(Maker $maker): void;

    public function update(Maker $maker): void;
}
