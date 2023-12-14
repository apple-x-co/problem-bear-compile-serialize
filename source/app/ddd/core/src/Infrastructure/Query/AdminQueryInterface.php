<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type AdminItem = array{
 *     id: string,
 *     name: string,
 *     username: string,
 *     password: string,
 * }
 */
interface AdminQueryInterface
{
    /** @psalm-return AdminItem|null */
    #[DbQuery('admin_item', type: 'row')]
    public function item(int $id): array|null;
}
