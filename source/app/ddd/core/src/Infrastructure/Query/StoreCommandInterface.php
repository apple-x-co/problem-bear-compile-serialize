<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface StoreCommandInterface
{
    /** @return array{id: int<1, max>} */
    #[DbQuery('store_add', type: 'row')]
    public function add(
        string $url,
        string $key,
        string $name,
        string $status,
        DateTimeImmutable|null $leaveDate,
        DateTimeImmutable|null $voidDate,
    ): array;

    /** @param int<1, max> $id */
    #[DbQuery('store_update', type: 'row')]
    public function update(
        int $id,
        string $url,
        string $key,
        string $name,
        string $status,
        DateTimeImmutable|null $leaveDate,
        DateTimeImmutable|null $voidDate,
    ): void;
}
