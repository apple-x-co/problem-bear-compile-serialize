<?php

declare(strict_types=1);

namespace AppCore\Domain\Company;

use DateTimeImmutable;

final class Company
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /**
     * @param int<1, max>|null $storeId
     * @param int<1, max>|null $paymentMethodId
     * @param int<0, max>      $id
     *
     * @SuppressWarnings(PHPMD.LongVariable)
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        public readonly string $name,
        public readonly CompanyContact $contact,
        public readonly string $sellerSlug,
        public readonly string $sellerUrl,
        public readonly string $consumerSlug,
        public readonly string $consumerUrl,
        public readonly int|null $storeId = null,
        public readonly int|null $paymentMethodId = null,
        public readonly CompanyStatus $status = CompanyStatus::IN_PREPARATION,
        public readonly DateTimeImmutable|null $leaveDate = null,
        public readonly DateTimeImmutable|null $voidDate = null,
        public readonly int $id = self::ID0,
    ) {
    }

    /**
     * @param int<1, max>|null $storeId
     * @param int<1, max>|null $paymentMethodId
     * @param int<1, max>      $id
     *
     * @SuppressWarnings(PHPMD.LongVariable)
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public static function reconstruct(
        int $id,
        string $name,
        CompanyContact $contact,
        string $sellerSlug,
        string $sellerUrl,
        string $consumerSlug,
        string $consumerUrl,
        int|null $storeId,
        int|null $paymentMethodId,
        CompanyStatus $status,
        DateTimeImmutable|null $leaveDate,
        DateTimeImmutable|null $voidDate,
    ): Company {
        return new self(
            $name,
            $contact,
            $sellerSlug,
            $sellerUrl,
            $consumerSlug,
            $consumerUrl,
            $storeId,
            $paymentMethodId,
            $status,
            $leaveDate,
            $voidDate,
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

    /** @param int<1, max>|null $storeId */
    public function changeStoreId(int|null $storeId): self
    {
        return new self(
            $this->name,
            clone $this->contact,
            $this->sellerSlug,
            $this->sellerUrl,
            $this->consumerSlug,
            $this->consumerUrl,
            $storeId,
            $this->paymentMethodId,
            $this->status,
            $this->leaveDate === null ? null : clone $this->leaveDate,
            $this->voidDate === null ? null : clone $this->voidDate,
            $this->id,
        );
    }

    /**
     * @param int<1, max>|null $paymentMethodId
     *
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function changePaymentMethodId(int|null $paymentMethodId): self
    {
        return new self(
            $this->name,
            clone $this->contact,
            $this->sellerSlug,
            $this->sellerUrl,
            $this->consumerSlug,
            $this->consumerUrl,
            $this->storeId,
            $paymentMethodId,
            $this->status,
            $this->leaveDate === null ? null : clone $this->leaveDate,
            $this->voidDate === null ? null : clone $this->voidDate,
            $this->id,
        );
    }

    private function changeStatus(CompanyStatus $status): self
    {
        $leaveDate = match ($status) {
            CompanyStatus::LEAVED => new DateTimeImmutable(),
            default => $this->leaveDate === null ? null : clone $this->leaveDate,
        };

        $voidDate = match ($status) {
            CompanyStatus::VOID => new DateTimeImmutable(),
            default => $this->leaveDate === null ? null : clone $this->leaveDate,
        };

        return new self(
            $this->name,
            clone $this->contact,
            $this->sellerSlug,
            $this->sellerUrl,
            $this->consumerSlug,
            $this->consumerUrl,
            $this->storeId,
            $this->paymentMethodId,
            $this->status->transitionTo($status),
            $leaveDate,
            $voidDate,
            $this->id,
        );
    }

    public function active(): self
    {
        return $this->changeStatus(CompanyStatus::ACTIVE);
    }

    public function deleted(): self
    {
        return $this->changeStatus(CompanyStatus::LEAVED);
    }
}
