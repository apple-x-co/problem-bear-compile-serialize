<?php

declare(strict_types=1);

namespace AppCore\Domain\DiscountCode;

use DateTimeImmutable;

use function array_intersect;
use function array_map;
use function count;
use function in_array;

final class DiscountCode
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /**
     * @param int<1, max>      $storeId
     * @param int<1, max>      $value
     * @param int<0, max>      $usageCount
     * @param int<0, max>|null $usageLimit
     * @param int<0, max>|null $minimumPrice
     * @param int<0, max>      $id
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        public readonly int $storeId,
        public readonly string $title,
        public readonly string $code,
        public readonly DiscountValueType $valueType,
        public readonly int $value,
        public readonly DateTimeImmutable|null $startDate,
        public readonly DateTimeImmutable|null $endDate,
        public readonly int $usageCount,
        public readonly int|null $usageLimit,
        public readonly int|null $minimumPrice,
        public readonly bool $oncePerCustomer,
        public readonly DiscountTargetSelection $targetSelection,
        public readonly DiscountEntitledProducts|null $entitledProducts,
        public readonly DiscountCodeStatus $status,
        public readonly int $id = self::ID0,
    ) {
    }

    /**
     * @param int<1, max>      $storeId
     * @param int<1, max>      $value
     * @param int<0, max>      $usageCount
     * @param int<0, max>|null $usageLimit
     * @param int<0, max>|null $minimumPrice
     * @param int<1, max>      $id
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public static function reconstruct(
        int $id,
        int $storeId,
        string $title,
        string $code,
        DiscountValueType $valueType,
        int $value,
        DateTimeImmutable|null $startDate,
        DateTimeImmutable|null $endDate,
        int $usageCount,
        int|null $usageLimit,
        int|null $minimumPrice,
        bool $oncePerCustomer,
        DiscountTargetSelection $targetSelection,
        DiscountEntitledProducts|null $entitledProducts,
        DiscountCodeStatus $status,
    ): DiscountCode {
        return new self(
            $storeId,
            $title,
            $code,
            $valueType,
            $value,
            $startDate,
            $endDate,
            $usageCount,
            $usageLimit,
            $minimumPrice,
            $oncePerCustomer,
            $targetSelection,
            $entitledProducts,
            $status,
            $id,
        );
    }

    private function changeStatus(DiscountCodeStatus $status): self
    {
        return new self(
            $this->storeId,
            $this->title,
            $this->code,
            $this->valueType,
            $this->value,
            $this->startDate,
            $this->endDate,
            $this->usageCount,
            $this->usageLimit,
            $this->minimumPrice,
            $this->oncePerCustomer,
            $this->targetSelection,
            $this->entitledProducts,
            $this->status->transitionTo($status),
            $this->id,
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

    public function private(): self
    {
        return $this->changeStatus(DiscountCodeStatus::PRIVATE);
    }

    public function public(): self
    {
        return $this->changeStatus(DiscountCodeStatus::PUBLIC);
    }

    /** 使用日が期間内であればOK */
    private function checkDuration(DateTimeImmutable $useDate): bool
    {
        $stardDate = $this->startDate ?? new DateTimeImmutable('1970-01-01 00:00:00');
        $endDate = $this->endDate ?? new DateTimeImmutable('2100-12-31 23:59:59');

        return $stardDate <= $useDate && $endDate >= $useDate;
    }

    /** 使用制限回数以内であればOK */
    private function checkUsageLimit(): bool
    {
        if ($this->usageLimit === null) {
            return true;
        }

        return $this->usageLimit > $this->usageCount;
    }

    /**
     * 未使用であればOK
     *
     * @param list<string> $usedCodes
     */
    private function checkUnused(array $usedCodes): bool
    {
        if (! $this->oncePerCustomer) {
            return true;
        }

        return ! in_array($this->code, $usedCodes, true);
    }

    /** 最低金額より大きければOK */
    private function checkMinimumPrice(int $price): bool
    {
        if ($this->minimumPrice === null) {
            return true;
        }

        return $this->minimumPrice <= $price;
    }

    /**
     * 対象製品が含まれていればOK
     *
     * @param list<int<1, max>> $productIds
     */
    private function checkEntitledProduct(array $productIds): bool
    {
        if ($this->targetSelection === DiscountTargetSelection::ALL) {
            return true;
        }

        if ($this->entitledProducts === null) {
            return false;
        }

        $entitledProductIds = array_map(
            static function (DiscountEntitledProduct $item) {
                return $item->productId;
            },
            (array) $this->entitledProducts->getIterator(),
        );

        return count(array_intersect($entitledProductIds, $productIds)) > 0;
    }

    /**
     * @param list<string>      $usedCodes
     * @param list<int<1, max>> $productIds
     */
    public function canUse(DateTimeImmutable $useDate, int $price, array $usedCodes, array $productIds): bool
    {
        return $this->status === DiscountCodeStatus::PUBLIC &&
            $this->checkDuration($useDate) &&
            $this->checkUsageLimit() &&
            $this->checkMinimumPrice($price) &&
            $this->checkUnused($usedCodes) &&
            $this->checkEntitledProduct($productIds);
    }
}
