<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\AccessControl\Access;
use AppCore\Domain\AccessControl\Permission;
use AppCore\Domain\StaffMember\StaffMember;
use AppCore\Domain\StaffMember\StaffMemberHeadShotImage;
use AppCore\Domain\StaffMember\StaffMemberImageGroup;
use AppCore\Domain\StaffMember\StaffMemberNotFoundException;
use AppCore\Domain\StaffMember\StaffMemberPermission;
use AppCore\Domain\StaffMember\StaffMemberPermissions;
use AppCore\Domain\StaffMember\StaffMemberRepositoryInterface;
use AppCore\Domain\StaffMember\StaffMemberStatus;
use AppCore\Infrastructure\Query\StaffMemberCommandInterface;
use AppCore\Infrastructure\Query\StaffMemberImageCommandInterface;
use AppCore\Infrastructure\Query\StaffMemberImageQueryInterface;
use AppCore\Infrastructure\Query\StaffMemberPermissionCommandInterface;
use AppCore\Infrastructure\Query\StaffMemberPermissionQueryInterface;
use AppCore\Infrastructure\Query\StaffMemberQueryInterface;

use function array_map;
use function array_reduce;
use function array_walk;
use function assert;
use function iterator_to_array;

/**
 * @psalm-import-type StaffMemberImageItem from StaffMemberImageQueryInterface
 * @psalm-import-type StaffMemberPermissionItem from StaffMemberPermissionQueryInterface
 * @psalm-import-type StaffMemberItem from StaffMemberQueryInterface
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class StaffMemberRepository implements StaffMemberRepositoryInterface
{
    public function __construct(
        private readonly StaffMemberCommandInterface $staffMemberCommand,
        private readonly StaffMemberImageCommandInterface $staffMemberImageCommand,
        private readonly StaffMemberImageQueryInterface $staffMemberImageQuery,
        private readonly StaffMemberPermissionCommandInterface $staffMemberPermissionCommand,
        private readonly StaffMemberPermissionQueryInterface $staffMemberPermissionQuery,
        private readonly StaffMemberQueryInterface $staffMemberQuery,
    ) {
    }

    public function findById(int $id): StaffMember
    {
        $staffMemberItem = $this->staffMemberQuery->item($id);
        if ($staffMemberItem === null) {
            throw new StaffMemberNotFoundException();
        }

        $staffMemberImageItem = $this->staffMemberImageQuery->itemByStaffMemberId($id);
        $staffMemberPermissionItems = $this->staffMemberPermissionQuery->listByStaffMemberId($id);

        return $this->toModel(
            $staffMemberItem,
            $staffMemberImageItem,
            $staffMemberPermissionItems,
        );
    }

    /**
     * @param int<1, max> $companyId
     *
     * @return list<StaffMember>
     */
    public function findByCompanyId(int $companyId): array
    {
        $staffMemberItems = $this->staffMemberQuery->listByCompanyId($companyId);

        $staffMemberImageItems = $this->staffMemberImageQuery->listByCompanyId($companyId);
        $staffMemberImageItemMap = array_reduce(
            $staffMemberImageItems,
            static function (array $carry, array $item) {
                $carry[(int) $item['staff_member_id']] = $item;

                return $carry;
            },
            [],
        );

        $staffMemberPermissionItems = $this->staffMemberPermissionQuery->listByCompanyId($companyId);
        $staffMemberPermissionItemMap = array_reduce(
            $staffMemberPermissionItems,
            static function (array $carry, array $item) {
                $carry[(int) $item['staff_member_id']][] = $item;

                return $carry;
            },
            [],
        );

        return array_map(
            function (array $staffMemberItem) use ($staffMemberImageItemMap, $staffMemberPermissionItemMap) {
                $staffMemberId = (int) $staffMemberItem['id'];

                return $this->toModel(
                    $staffMemberItem,
                    $staffMemberImageItemMap[$staffMemberId] ?? null,
                    $staffMemberPermissionItemMap[$staffMemberId] ?? [],
                );
            },
            $staffMemberItems,
        );
    }

    /**
     * @param int<1, max> $shopId
     *
     * @return list<StaffMember>
     */
    public function findByShopId(int $shopId): array
    {
        $staffMemberItems = $this->staffMemberQuery->listByShopId($shopId);

        $staffMemberImageItems = $this->staffMemberImageQuery->listByShopId($shopId);
        $staffMemberImageItemMap = array_reduce(
            $staffMemberImageItems,
            static function (array $carry, array $item) {
                $carry[(int) $item['staff_member_id']] = $item;

                return $carry;
            },
            [],
        );

        $staffMemberPermissionItems = $this->staffMemberPermissionQuery->listByShopId($shopId);
        $staffMemberPermissionItemMap = array_reduce(
            $staffMemberPermissionItems,
            static function (array $carry, array $item) {
                $carry[(int) $item['staff_member_id']][] = $item;

                return $carry;
            },
            [],
        );

        return array_map(
            function (array $staffMemberItem) use ($staffMemberImageItemMap, $staffMemberPermissionItemMap) {
                $staffMemberId = (int) $staffMemberItem['id'];

                return $this->toModel(
                    $staffMemberItem,
                    $staffMemberImageItemMap[$staffMemberId] ?? null,
                    $staffMemberPermissionItemMap[$staffMemberId] ?? [],
                );
            },
            $staffMemberItems,
        );
    }

    /**
     * @psalm-param StaffMemberItem                 $staffMemberItem
     * @psalm-param StaffMemberImageItem|null       $staffMemberImageItem
     * @psalm-param list<StaffMemberPermissionItem> $staffMemberPermissionItems
     */
    private function toModel(
        array $staffMemberItem,
        array|null $staffMemberImageItem,
        array $staffMemberPermissionItems,
    ): StaffMember {
        $id = (int) $staffMemberItem['id'];
        assert($id > 0);

        $companyId = (int) $staffMemberItem['company_id'];
        assert($companyId > 0);

        $shopId = empty($staffMemberItem['shop_id']) ? null : (int) $staffMemberItem['shop_id'];
        assert($shopId > 0 || $shopId === null);

        $position = (int) $staffMemberItem['position'];
        assert($position > 0);

        return StaffMember::reconstruct(
            $id,
            $companyId,
            $shopId,
            $staffMemberItem['name'],
            $staffMemberItem['code'],
            $staffMemberItem['email'],
            $staffMemberItem['password'],
            $staffMemberItem['message'],
            $position,
            StaffMemberStatus::from($staffMemberItem['status']),
            $staffMemberImageItem === null ? null : $this->toStaffMemberImage($staffMemberImageItem),
            $this->toStaffMemberPermissions($staffMemberPermissionItems),
        );
    }

    /** @psalm-param StaffMemberImageItem $staffMemberImageItem */
    private function toStaffMemberImage(array $staffMemberImageItem): StaffMemberHeadShotImage
    {
        $id = (int) $staffMemberImageItem['id'];
        assert($id > 0);

        $size = (int) $staffMemberImageItem['size'];
        assert($size > 0);

        $width = (int) $staffMemberImageItem['width'];
        assert($width > 0);

        $height = (int) $staffMemberImageItem['height'];
        assert($height > 0);

        return StaffMemberHeadShotImage::reconstruct(
            $id,
            $size,
            $staffMemberImageItem['media_type'],
            $width,
            $height,
            $staffMemberImageItem['original_file_name'],
            $staffMemberImageItem['url'],
            $staffMemberImageItem['path'],
            $staffMemberImageItem['file_name'],
            $staffMemberImageItem['md5'],
        );
    }

    /** @param list<StaffMemberPermissionItem> $staffMemberPermissionItems */
    private function toStaffMemberPermissions(array $staffMemberPermissionItems): StaffMemberPermissions
    {
        return new StaffMemberPermissions(
            array_map(
                static function (array $staffMemberPermissionItem) {
                    $id = (int) $staffMemberPermissionItem['id'];
                    assert($id > 0);

                    return StaffMemberPermission::reconstruct(
                        $id,
                        Access::from($staffMemberPermissionItem['access']),
                        $staffMemberPermissionItem['resource_name'],
                        Permission::from($staffMemberPermissionItem['permission']),
                    );
                },
                $staffMemberPermissionItems,
            ),
        );
    }

    public function insert(StaffMember $staffMember): void
    {
        $result = $this->staffMemberCommand->add(
            $staffMember->companyId,
            $staffMember->shopId,
            $staffMember->name,
            $staffMember->staffCode,
            $staffMember->email,
            $staffMember->password,
            $staffMember->message,
            $staffMember->position,
            $staffMember->status->value,
        );

        $staffMemberId = $result['id'];
        $staffMember->setNewId($staffMemberId);

        $staffMemberImage = $staffMember->headShotImage;
        if ($staffMemberImage !== null) {
            $this->insertStaffMemberHeadShotImage($staffMemberId, $staffMemberImage);
        }

        $this->insertStaffMemberPermission($staffMemberId, iterator_to_array($staffMember->permissions->getIterator(), false));
    }

    /** @param int<1, max> $staffMemberId */
    private function insertStaffMemberHeadShotImage(
        int $staffMemberId,
        StaffMemberHeadShotImage $staffMemberHeadShotImage,
    ): void {
        $result = $this->staffMemberImageCommand->add(
            $staffMemberId,
            StaffMemberImageGroup::HEAD_SHOT->value,
            $staffMemberHeadShotImage->size,
            $staffMemberHeadShotImage->mediaType,
            $staffMemberHeadShotImage->width,
            $staffMemberHeadShotImage->height,
            $staffMemberHeadShotImage->originalFileName,
            $staffMemberHeadShotImage->url,
            $staffMemberHeadShotImage->path,
            $staffMemberHeadShotImage->fileName,
            $staffMemberHeadShotImage->md5,
        );

        $staffMemberHeadShotImage->setNewId($result['id']);
    }

    /**
     * @param int<1, max>                 $staffMemberId
     * @param list<StaffMemberPermission> $staffMemberPermissions
     */
    private function insertStaffMemberPermission(int $staffMemberId, array $staffMemberPermissions): void
    {
        array_walk(
            $staffMemberPermissions,
            function (StaffMemberPermission $item) use ($staffMemberId): void {
                $this->staffMemberPermissionCommand->add(
                    $staffMemberId,
                    $item->access->value,
                    $item->resourceName,
                    $item->permission->value,
                );
            },
        );
    }

    public function update(StaffMember $staffMember): void
    {
        if (empty($staffMember->id)) {
            return;
        }

        $this->staffMemberCommand->update(
            $staffMember->id,
            $staffMember->shopId,
            $staffMember->name,
            $staffMember->staffCode,
            $staffMember->email,
            $staffMember->password,
            $staffMember->message,
            $staffMember->position,
            $staffMember->status->value,
        );

        $staffMemberImage = $staffMember->headShotImage;
        if ($staffMemberImage !== null) {
            $this->upsertStaffMemberHeadShotImage($staffMember->id, $staffMemberImage);
        }

        $this->upsertStaffMemberPermission($staffMember->id, iterator_to_array($staffMember->permissions->getIterator(), false));
    }

    /** @param int<1, max> $staffMemberId */
    private function upsertStaffMemberHeadShotImage(
        int $staffMemberId,
        StaffMemberHeadShotImage $staffMemberHeadShotImage,
    ): void {
        if (! empty($staffMemberHeadShotImage->id)) {
            return;
        }

        $result = $this->staffMemberImageCommand->add(
            $staffMemberId,
            StaffMemberImageGroup::HEAD_SHOT->value,
            $staffMemberHeadShotImage->size,
            $staffMemberHeadShotImage->mediaType,
            $staffMemberHeadShotImage->width,
            $staffMemberHeadShotImage->height,
            $staffMemberHeadShotImage->originalFileName,
            $staffMemberHeadShotImage->url,
            $staffMemberHeadShotImage->path,
            $staffMemberHeadShotImage->fileName,
            $staffMemberHeadShotImage->md5,
        );

        $id = $result['id'];

        $staffMemberHeadShotImage->setNewId($id);

        $this->staffMemberImageCommand->deleteOld($staffMemberId, [$id]);
    }

    /**
     * @param int<1, max>                 $staffMemberId
     * @param list<StaffMemberPermission> $staffMemberPermissions
     */
    private function upsertStaffMemberPermission(int $staffMemberId, array $staffMemberPermissions): void
    {
        $aliveIds = [];

        array_walk(
            $staffMemberPermissions,
            function (StaffMemberPermission $item) use ($staffMemberId, &$aliveIds): void {
                if (empty($item->id)) {
                    $result = $this->staffMemberPermissionCommand->add(
                        $staffMemberId,
                        $item->access->value,
                        $item->resourceName,
                        $item->permission->value,
                    );

                    $id = $result['id'];

                    $item->setNewId($id);

                    $aliveIds[] = $id;

                    return;
                }

                $this->staffMemberPermissionCommand->update(
                    $item->id,
                    $item->access->value,
                    $item->resourceName,
                    $item->permission->value,
                );

                $aliveIds[] = $item->id;
            },
        );

        $this->staffMemberPermissionCommand->deleteOld($staffMemberId, $aliveIds);
    }
}
