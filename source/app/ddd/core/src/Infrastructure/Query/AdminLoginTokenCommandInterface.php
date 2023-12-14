<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface AdminLoginTokenCommandInterface
{
    /**
     * @param int<1, max> $adminId
     *
     * @return array{id: int<1, max>}
     */
    #[DbQuery('admin_login_token_add', type: 'row')]
    public function add(
        int $adminId,
        string $token,
        DateTimeImmutable $expireDate,
    ): array;

    /** @param int<1, max> $adminId */
    #[DbQuery('admin_login_token_delete_by_admin_id', type: 'row')]
    public function deleteByAdminId(int $adminId): void;
}
