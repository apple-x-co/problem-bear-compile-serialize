<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

/**
 * @psalm-type AdminTokenItem = array{
 *     id: string,
 *     admin_id: string,
 *     token: string,
 *     expire_date: string,
 * }
 */
interface AdminLoginTokenQueryInterface
{
    /** @psalm-return AdminTokenItem|null */
    public function itemByToken(string $token): array|null;
}
