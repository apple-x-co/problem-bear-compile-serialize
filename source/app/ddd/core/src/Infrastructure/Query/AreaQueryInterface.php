<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type AreaItem = array{
 *     id: string,
 *     company_id: string,
 *     name: string,
 *     position: string,
 * }
 */
interface AreaQueryInterface
{
    /** @psalm-return AreaItem|null */
    #[DbQuery('area_item', type: 'row')]
    public function item(int $id): array|null;
}
