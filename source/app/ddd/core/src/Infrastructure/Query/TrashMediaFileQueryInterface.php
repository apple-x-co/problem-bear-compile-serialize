<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type TrashMediaFileItem = array{
 *     id: string,
 *     table_name: string,
 *     identifier: string,
 *     original_file_name: string,
 *     url: string,
 *     path: string,
 *     file_name: string,
 * }
 */
interface TrashMediaFileQueryInterface
{
    /** @return list<TrashMediaFileItem> */
    #[DbQuery('trash_media_file_list.sql')]
    public function list(): array;
}
