<?php

declare(strict_types=1);

namespace AppCore\Domain\Stock;

final class Stock
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /**
     * @param int<1, max> $storeId
     * @param int<1, max> $productVariantId
     * @param int<0, max> $quantity
     * @param int<0, max> $id
     */
    public function __construct(
        public readonly int $storeId,
        public readonly int $productVariantId,
        public readonly string $idempotencyToken,
        public readonly int $quantity,
        public readonly int $id = self::ID0,
    ) {
    }

    /**
     * @param int<1, max> $storeId
     * @param int<1, max> $productVariantId
     * @param int<0, max> $quantity
     * @param int<1, max> $id
     */
    public static function reconstruct(
        int $id,
        int $storeId,
        int $productVariantId,
        string $idempotencyToken,
        int $quantity,
    ): Stock {
        return new self(
            $storeId,
            $productVariantId,
            $idempotencyToken,
            $quantity,
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

    /** @param int<1, max> $quantity */
    public function changeQuantity(string $idempotencyToken, int $quantity): self
    {
        if ($this->idempotencyToken !== $idempotencyToken) {
            throw new IdempotencyException();
        }

        return new self(
            $this->storeId,
            $this->productVariantId,
            (new IdempotencyToken())(),
            $quantity,
            $this->id,
        );
    }
}
