<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type StaffMemberPermissionItem = array{
 *     id: string,
 *     staff_member_id: string,
 *     access: string,
 *     resource_name: string,
 *     permission: string,
 * }
 */
interface StaffMemberPermissionQueryInterface
{
    /**
     * @param int<1, max> $companyId
     *
     * @return list<StaffMemberPermissionItem>
     */
    #[DbQuery('staff_member_permission_list_by_company_id')]
    public function listByCompanyId(int $companyId): array;

    /**
     * @param int<1, max> $shopId
     *
     * @return list<StaffMemberPermissionItem>
     */
    #[DbQuery('staff_member_permission_list_by_shop_id')]
    public function listByShopId(int $shopId): array;

    /**
     * @param int<1, max> $staffMemberId
     *
     * @return list<StaffMemberPermissionItem>
     */
    #[DbQuery('staff_member_permission_list_by_staff_member_id')]
    public function listByStaffMemberId(int $staffMemberId): array;
}
