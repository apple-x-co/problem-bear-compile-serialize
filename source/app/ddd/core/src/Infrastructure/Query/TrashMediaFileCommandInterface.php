<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface TrashMediaFileCommandInterface
{
    /** @param int<1, max> $id */
    #[DbQuery('trash_media_file_delete.sql')]
    public function delete(int $id): void;
}
