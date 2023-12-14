<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type StaffMemberImageItem = array{
 *     id: string,
 *     staff_member_id: string,
 *     group: string,
 *     size: string,
 *     media_type: string,
 *     width: string,
 *     height: string,
 *     original_file_name: string,
 *     url: string,
 *     path: string,
 *     file_name: string,
 *     md5: string,
 *     position: string,
 * }
 */
interface StaffMemberImageQueryInterface
{
    /**
     * @param int<1, max> $staffMemberId
     *
     * @return StaffMemberImageItem|null
     */
    #[DbQuery('staff_member_image_item_by_staff_member_id', type: 'row')]
    public function itemByStaffMemberId(int $staffMemberId): array|null;

    /**
     * @param int<1, max> $companyId
     *
     * @return list<StaffMemberImageItem>
     */
    #[DbQuery('staff_member_image_list_by_company_id')]
    public function listByCompanyId(int $companyId): array;

    /**
     * @param int<1, max> $shopId
     *
     * @return list<StaffMemberImageItem>
     */
    #[DbQuery('staff_member_image_list_by_shop_id')]
    public function listByShopId(int $shopId): array;
}
