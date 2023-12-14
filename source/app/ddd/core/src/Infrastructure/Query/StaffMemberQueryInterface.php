<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type StaffMemberItem = array{
 *     id: string,
 *     company_id: string,
 *     shop_id: string|null,
 *     name: string,
 *     code: string,
 *     email: string|null,
 *     password: string,
 *     message: string,
 *     position: string,
 *     status: string,
 * }
 */
interface StaffMemberQueryInterface
{
    /**
     * @param int<1, max> $id
     *
     * @return StaffMemberItem|null
     */
    #[DbQuery('staff_member_item', type: 'row')]
    public function item(int $id): array|null;

    /**
     * @param int<1, max> $companyId
     *
     * @return list<StaffMemberItem>
     */
    #[DbQuery('staff_member_list_by_company_id')]
    public function listByCompanyId(int $companyId): array;

    /**
     * @param int<1, max> $companyId
     *
     * @return list<StaffMemberItem>
     */
    #[DbQuery('staff_member_list_by_shop_id')]
    public function listByShopId(int $companyId): array;
}
