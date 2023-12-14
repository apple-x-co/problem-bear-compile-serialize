<?php

declare(strict_types=1);

namespace AppCore\Domain\ShopNotificationRecipient;

final class ShopNotificationRecipient
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /**
     * @param int<1, max> $shopId
     * @param int<0, max> $id
     */
    public function __construct(
        public readonly int $shopId,
        public readonly ShopNotificationRecipientType $type,
        public readonly string $email,
        public readonly int $id = self::ID0,
    ) {
    }

    /**
     * @param int<1, max> $shopId
     * @param int<1, max> $id
     */
    public static function reconstruct(
        int $id,
        int $shopId,
        ShopNotificationRecipientType $type,
        string $email,
    ): ShopNotificationRecipient {
        return new self(
            $shopId,
            $type,
            $email,
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
