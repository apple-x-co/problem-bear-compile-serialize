<?php

declare(strict_types=1);

namespace AppCore\Domain\PaymentMethod;

final class PaymentMethod
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /**
     * @param int<0, max> $fee
     * @param int<1, max> $position
     * @param int<0, max> $id
     */
    public function __construct(
        public readonly string $name,
        public readonly PaymentMethodKey $key,
        public readonly PaymentMethodRole $role,
        public readonly int $fee,
        public readonly bool $available,
        public readonly int $position,
        public readonly int $id = self::ID0,
    ) {
    }

    /**
     * @param int<0, max> $fee
     * @param int<1, max> $position
     * @param int<1, max> $id
     */
    public static function reconstruct(
        int $id,
        string $name,
        PaymentMethodKey $key,
        PaymentMethodRole $role,
        int $fee,
        bool $available,
        int $position,
    ): PaymentMethod {
        return new self(
            $name,
            $key,
            $role,
            $fee,
            $available,
            $position,
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
}
