<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface StaffMemberImageCommandInterface
{
    /**
     * @param int<1, max> $staffMemberId
     * @param int<1, max> $size
     * @param int<1, max> $width
     * @param int<1, max> $height
     *
     * @return array{id: int<1, max>}
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    #[DbQuery('staff_member_image_add', type: 'row')]
    public function add(
        int $staffMemberId,
        string $group,
        int $size,
        string $mediaType,
        int $width,
        int $height,
        string $originalFileName,
        string $url,
        string $path,
        string $fileName,
        string $md5,
    ): array;

    /**
     * @param int<1, max>       $staffMemberId
     * @param list<int<1, max>> $aliveIds
     */
    #[DbQuery('staff_member_image_delete_old', type: 'row')]
    public function deleteOld(int $staffMemberId, array $aliveIds): void;
}
