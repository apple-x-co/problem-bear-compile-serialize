<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface AdminCommandInterface
{
    /** @return array{id: int<1, max>} */
    #[DbQuery('admin_add', 'row')]
    public function add(
        string $name,
        string $username,
        string $password,
    ): array;

    /** @param int<1, max> $id */
    #[DbQuery('admin_update', 'row')]
    public function update(
        int $id,
        string $name,
        string $username,
        string $password,
    ): void;
}
