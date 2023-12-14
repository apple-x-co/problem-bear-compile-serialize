<?php

declare(strict_types=1);

namespace AppCore\Domain\Purpose;

interface PurposeRepositoryInterface
{
    /** @param int<1, max> $id */
    public function findById(int $id): Purpose;

    /**
     * @param int<1, max> $storeId
     *
     * @return list<Purpose>
     */
    public function findByStoreId(int $storeId): array;

    public function insert(Purpose $purpose): void;

    public function update(Purpose $purpose): void;
}
