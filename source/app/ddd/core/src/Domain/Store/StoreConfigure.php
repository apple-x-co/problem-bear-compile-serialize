<?php

declare(strict_types=1);

namespace AppCore\Domain\Store;

final class StoreConfigure
{
    /**
     * @param int<1, 100>|null $pointRate
     *
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function __construct(
        public readonly string|null $primaryColor,
        public readonly string|null $termText,
        public readonly string|null $legalText,
        public readonly string|null $privacyText,
        public readonly string|null $contactText,
        public readonly bool $pointEnabled,
        public readonly int|null $pointRate,
        public readonly string|null $orderNotificationRecipient,
    ) {
    }

    /**
     * @param int<1, 100>|null $pointRate
     *
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public static function reconstruct(
        string|null $primaryColor,
        string|null $termText,
        string|null $legalText,
        string|null $privacyText,
        string|null $contactText,
        bool $pointEnabled,
        int|null $pointRate,
        string|null $orderNotificationRecipient,
    ): StoreConfigure {
        return new self(
            $primaryColor,
            $termText,
            $legalText,
            $privacyText,
            $contactText,
            $pointEnabled,
            $pointRate,
            $orderNotificationRecipient,
        );
    }

    /** @param int<1, 100> $pointRate */
    public function enablePoint(int $pointRate): self
    {
        return new self(
            $this->primaryColor,
            $this->termText,
            $this->legalText,
            $this->privacyText,
            $this->contactText,
            true,
            $pointRate,
            $this->orderNotificationRecipient,
        );
    }

    public function disablePoint(): self
    {
        return new self(
            $this->primaryColor,
            $this->termText,
            $this->legalText,
            $this->privacyText,
            $this->contactText,
            false,
            null,
            $this->orderNotificationRecipient,
        );
    }
}
