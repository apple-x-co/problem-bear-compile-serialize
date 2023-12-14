<?php

declare(strict_types=1);

namespace AppCore\Domain\Shop;

use AppCore\Domain\GeometryLocation;

final class Shop
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /**
     * @param int<1, max> $companyId
     * @param int<1, max> $areaId
     * @param int<1, max> $position
     * @param int<0, max> $id
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        public readonly int $companyId,
        public readonly int $areaId,
        public readonly string $name,
        public readonly ShopContact $contact,
        public readonly int $position,
        public readonly ShopExteriorImage $exteriorImage,
        public readonly GeometryLocation|null $geometryLocation,
        public readonly ShopHolidays|null $holidays,
        public readonly ShopRegularHolidays|null $regularHolidays,
        public readonly int $id = self::ID0,
    ) {
    }

    /**
     * @param int<1, max> $companyId
     * @param int<1, max> $areaId
     * @param int<1, max> $position
     * @param int<1, max> $id
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public static function reconstruct(
        int $id,
        int $companyId,
        int $areaId,
        string $name,
        ShopContact $contact,
        int $position,
        ShopExteriorImage $exteriorImage,
        GeometryLocation|null $geometryLocation,
        ShopHolidays|null $holidays,
        ShopRegularHolidays|null $regularHolidays,
    ): Shop {
        return new self(
            $companyId,
            $areaId,
            $name,
            $contact,
            $position,
            $exteriorImage,
            $geometryLocation,
            $holidays,
            $regularHolidays,
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

    /** @param int<1, max> $position */
    public function changePosition(int $position): self
    {
        return new self(
            $this->companyId,
            $this->areaId,
            $this->name,
            clone $this->contact,
            $position,
            clone $this->exteriorImage,
            $this->geometryLocation === null ? null : clone $this->geometryLocation,
            $this->holidays,
            $this->regularHolidays,
            $this->id,
        );
    }
}
