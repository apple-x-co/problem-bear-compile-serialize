<?php

declare(strict_types=1);

namespace AppCore\Domain\Store;

final class StoreHero
{
    /** @param int<1, max> $position */
    public function __construct(
        public readonly string $title,
        public readonly string $subtitle,
        public readonly string $linkUrl,
        public readonly StoreHeroImage $image,
        public readonly int $position,
    ) {
    }

    /** @param int<1, max> $position */
    public static function reconstruct(
        string $title,
        string $subtitle,
        string $linkUrl,
        StoreHeroImage $image,
        int $position,
    ): StoreHero {
        return new self(
            $title,
            $subtitle,
            $linkUrl,
            $image,
            $position,
        );
    }
}
