<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface TaxCommandInterface
{
    /**
     * @param int<1, 100> $rate
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('tax_add', type: 'row')]
    public function add(string $name, int $rate): array;

    /**
     * @param int<1, max> $id
     * @param int<1, 100> $rate
     */
    #[DbQuery('tax_update', type: 'row')]
    public function update(int $id, string $name, int $rate): void;
}
