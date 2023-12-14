<?php

declare(strict_types=1);

namespace AppCore\Domain\StaffMember;

interface StaffMemberRepositoryInterface
{
    /** @param int<1, max> $id */
    public function findById(int $id): StaffMember;

    /**
     * @param int<1, max> $companyId
     *
     * @return list<StaffMember>
     */
    public function findByCompanyId(int $companyId): array;

    /**
     * @param int<1, max> $shopId
     *
     * @return list<StaffMember>
     */
    public function findByShopId(int $shopId): array;

    public function insert(StaffMember $staffMember): void;

    public function update(StaffMember $staffMember): void;
}
