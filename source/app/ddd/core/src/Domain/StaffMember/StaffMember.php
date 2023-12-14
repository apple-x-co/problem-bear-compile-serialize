<?php

declare(strict_types=1);

namespace AppCore\Domain\StaffMember;

final class StaffMember
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /**
     * @param int<1, max>      $companyId
     * @param int<1, max>|null $shopId
     * @param int<1, max>      $position
     * @param int<0, max>      $id
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        public readonly int $companyId,
        public readonly int|null $shopId,
        public readonly string $name,
        public readonly string $staffCode,
        public readonly string|null $email,
        public readonly string $password,
        public readonly string $message,
        public readonly int $position,
        public readonly StaffMemberStatus $status,
        public readonly StaffMemberHeadShotImage|null $headShotImage,
        public readonly StaffMemberPermissions $permissions,
        public readonly int $id = self::ID0,
    ) {
    }

    /**
     * @param int<1, max>      $companyId
     * @param int<1, max>|null $shopId
     * @param int<1, max>      $position
     * @param int<1, max>      $id
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public static function reconstruct(
        int $id,
        int $companyId,
        int|null $shopId,
        string $name,
        string $staffCode,
        string|null $email,
        string $password,
        string $message,
        int $position,
        StaffMemberStatus $status,
        StaffMemberHeadShotImage|null $headShotImage,
        StaffMemberPermissions $permissions,
    ): StaffMember {
        return new self(
            $companyId,
            $shopId,
            $name,
            $staffCode,
            $email,
            $password,
            $message,
            $position,
            $status,
            $headShotImage,
            $permissions,
            $id,
        );
    }

    /** @return int<1, max>|null */
    public function getNewId(): int|null
    {
        return $this->newId;
    }

    /** @param int<1, max> $newId */
    public function setNewId(int $newId): void
    {
        $this->newId = $newId;
    }

    private function changeStatus(StaffMemberStatus $status): self
    {
        return new self(
            $this->companyId,
            $this->shopId,
            $this->name,
            $this->staffCode,
            $this->email,
            $this->password,
            $this->message,
            $this->position,
            $this->status->transitionTo($status),
            $this->headShotImage === null ? null : clone $this->headShotImage,
            clone $this->permissions,
            $this->id,
        );
    }

    public function public(): self
    {
        return $this->changeStatus(StaffMemberStatus::PUBLIC);
    }

    public function private(): self
    {
        return $this->changeStatus(StaffMemberStatus::PRIVATE);
    }

    /** @param int<1, max> $position */
    public function changePosition(int $position): self
    {
        return new self(
            $this->companyId,
            $this->shopId,
            $this->name,
            $this->staffCode,
            $this->email,
            $this->password,
            $this->message,
            $position,
            $this->status,
            $this->headShotImage === null ? null : clone $this->headShotImage,
            clone $this->permissions,
            $this->id,
        );
    }
}
