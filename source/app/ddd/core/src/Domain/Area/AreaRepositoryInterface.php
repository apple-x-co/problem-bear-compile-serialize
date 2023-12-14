<?php

declare(strict_types=1);

namespace AppCore\Domain\Area;

interface AreaRepositoryInterface
{
    /** @param int<1, max> $id */
    public function findById(int $id): Area;

    public function insert(Area $area): void;

    public function update(Area $area): void;
}
