<?php

declare(strict_types=1);

namespace AppCore\Domain\Store;

use DateTimeImmutable;

use function round;

final class Store
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /**
     * @param int<0, max> $id
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        public readonly string $url,
        public readonly string $key,
        public readonly string $name,
        public readonly StoreConfigure $configure,
        public readonly StoreLogoImage|null $logoImage,
        public readonly StoreHeroes|null $heroes,
        public readonly StoreStatus $status = StoreStatus::DRAFT,
        public readonly DateTimeImmutable|null $leaveDate = null,
        public readonly DateTimeImmutable|null $voidDate = null,
        public readonly int $id = self::ID0,
    ) {
    }

    /**
     * @param int<1, max> $id
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public static function reconstruct(
        int $id,
        string $url,
        string $key,
        string $name,
        StoreConfigure $configure,
        StoreLogoImage|null $logoImage,
        StoreHeroes|null $heroes,
        StoreStatus $status,
        DateTimeImmutable|null $leaveDate,
        DateTimeImmutable|null $voidDate,
    ): Store {
        return new self(
            $url,
            $key,
            $name,
            $configure,
            $logoImage,
            $heroes,
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

    public function changeStoreLogo(StoreLogoImage $logoImage): self
    {
        return new self(
            $this->url,
            $this->key,
            $this->name,
            clone $this->configure,
            $logoImage,
            $this->heroes === null ? null : clone $this->heroes,
            $this->status,
            $this->leaveDate === null ? null : clone $this->leaveDate,
            $this->voidDate === null ? null : clone $this->voidDate,
            $this->id,
        );
    }

    /** @param int<1, 100> $pointRate */
    public function enablePoint(int $pointRate): self
    {
        return new self(
            $this->url,
            $this->key,
            $this->name,
            $this->configure->enablePoint($pointRate),
            $this->logoImage === null ? null : clone $this->logoImage,
            $this->heroes === null ? null : clone $this->heroes,
            $this->status,
            $this->leaveDate === null ? null : clone $this->leaveDate,
            $this->voidDate === null ? null : clone $this->voidDate,
            $this->id,
        );
    }

    public function disablePoint(): self
    {
        return new self(
            $this->url,
            $this->key,
            $this->name,
            $this->configure->disablePoint(),
            $this->logoImage === null ? null : clone $this->logoImage,
            $this->heroes === null ? null : clone $this->heroes,
            $this->status,
            $this->leaveDate === null ? null : clone $this->leaveDate,
            $this->voidDate === null ? null : clone $this->voidDate,
            $this->id,
        );
    }

    public function calculatePoint(int $price): int
    {
        if (
            ! (
                $this->configure->pointEnabled &&
                $this->configure->pointRate > 0 &&
                $price > 0
            )
        ) {
            return 0;
        }

        return (int) round($price * $this->configure->pointRate / 100);
    }

    private function changeStatus(StoreStatus $status): self
    {
        $leaveDate = match ($status) {
            StoreStatus::LEAVED => new DateTimeImmutable(),
            default => $this->leaveDate === null ? null : clone $this->leaveDate,
        };

        $voidDate = match ($status) {
            StoreStatus::VOID => new DateTimeImmutable(),
            default => $this->leaveDate === null ? null : clone $this->leaveDate,
        };

        return new self(
            $this->url,
            $this->key,
            $this->name,
            clone $this->configure,
            $this->logoImage === null ? null : clone $this->logoImage,
            $this->heroes === null ? null : clone $this->heroes,
            $this->status->transitionTo($status),
            $leaveDate,
            $voidDate,
            $this->id,
        );
    }

    public function private(): self
    {
        return $this->changeStatus(StoreStatus::PRIVATE);
    }

    public function active(): self
    {
        return $this->changeStatus(StoreStatus::ACTIVE);
    }

    public function deleted(): self
    {
        return $this->changeStatus(StoreStatus::LEAVED);
    }

    public function paymentError(): self
    {
        return $this->changeStatus(StoreStatus::PAYMENT_ERROR);
    }
}
